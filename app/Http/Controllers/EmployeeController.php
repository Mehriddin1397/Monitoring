<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\TurniketService;
use App\Services\EmployeeSyncService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // 1. READ: Barcha xodimlarni ro'yxatini ko'rsatish
    public function index()
    {
        $employees = Employee::latest('id')->get();
        return view('admin.brith.index', compact('employees'));
    }

    /**
     * Xodimning 360° to'liq dosyesi va barcha ma'lumotlari:
     * Topshiriqlari, I-index natijasi, Tug'ilgan kun, Ijodi, Davomati, Loyihalari
     */
    public function show($id, TurniketService $turniketService)
    {
        // 1. Employee id bo'yicha qat'iy qidirish (chalkashliklarning oldini oladi)
        $employee = Employee::with([
            'user.assignedTasks.statuses',
            'user.assignedTasks.files',
            'user.articles.articleScore',
            'user.projects',
            'works'
        ])->find($id);

        if (!$employee) {
            // Agar User ID orqali murojaat qilingan bo'lsa
            $employee = Employee::with([
                'user.assignedTasks.statuses',
                'user.assignedTasks.files',
                'user.articles.articleScore',
                'user.projects',
                'works'
            ])->where('user_id', $id)->firstOrFail();
        }

        $user = $employee->user;
        $tasks = $user ? $user->assignedTasks->unique('id') : collect();
        $articles = $user ? $user->articles : collect();
        $projects = $user ? $user->projects : collect();
        $works = $employee->works;

        // Turniket davomat ma'lumotlari va kunlik soddalashtirilgan hisobot
        $turniketProfile = null;
        $turniketLogs = [];
        $turniketLeaves = [];
        $dailyAttendance = [];

        if (!empty($employee->turniket_id)) {
            $turniketProfileRes = $turniketService->getEmployee($employee->turniket_id);
            $turniketProfile = $turniketProfileRes['data'] ?? null;

            $turniketLogsRes = $turniketService->getAttendance(['employee_no' => $employee->turniket_id, 'limit' => 100]);
            $turniketLogs = $turniketLogsRes['data'] ?? [];

            $turniketLeavesRes = $turniketService->getLeaves(['employee_filter' => $employee->turniket_id]);
            $turniketLeaves = $turniketLeavesRes['data'] ?? [];

            // Kunlar bo'yicha guruhlash (Y-m-d)
            $groupedByDay = [];
            foreach ($turniketLogs as $log) {
                if (empty($log['event_time'])) continue;
                $dateStr = substr($log['event_time'], 0, 10);
                if (!isset($groupedByDay[$dateStr])) {
                    $groupedByDay[$dateStr] = [
                        'entries' => [],
                        'exits'   => [],
                        'devices' => [],
                    ];
                }
                if (($log['direction'] ?? '') === 'entry') {
                    $groupedByDay[$dateStr]['entries'][] = $log['event_time'];
                } else {
                    $groupedByDay[$dateStr]['exits'][] = $log['event_time'];
                }
                if (!empty($log['device_name'])) {
                    $groupedByDay[$dateStr]['devices'][$log['device_name']] = true;
                }
            }

            // Bugungi kun holatini qo'shish
            $todayStr = Carbon::today()->format('Y-m-d');
            $stToday = $turniketProfile['status_today'] ?? null;
            if (!empty($stToday['first_entry_time']) && !isset($groupedByDay[$todayStr])) {
                $groupedByDay[$todayStr] = [
                    'entries' => [$stToday['first_entry_time']],
                    'exits'   => [],
                    'devices' => ['Турникет' => true],
                ];
            }

            // Rasmiy sabablar va ta'tillarni sanalar bo'yicha indekslash
            $leaveDays = [];
            foreach ($turniketLeaves as $lv) {
                $start = !empty($lv['start_date']) ? Carbon::parse($lv['start_date']) : null;
                $end = !empty($lv['end_date']) ? Carbon::parse($lv['end_date']) : $start;
                if ($start && $end) {
                    $cur = $start->copy();
                    while ($cur->lte($end)) {
                        $leaveDays[$cur->format('Y-m-d')] = $lv;
                        $cur->addDay();
                    }
                }
            }

            // Agar ta'til bo'lib, o'sha kuni turniketdan o'tmagan bo'lsa ham ro'yxatga qo'shish
            foreach ($leaveDays as $lDate => $lvData) {
                if (!isset($groupedByDay[$lDate])) {
                    $groupedByDay[$lDate] = [
                        'entries' => [],
                        'exits'   => [],
                        'devices' => ['Расмий таътил' => true],
                    ];
                }
            }

            // Sanalarni yangisidan eskisiga qarab saralash
            krsort($groupedByDay);

            foreach ($groupedByDay as $date => $dayData) {
                $cDate = Carbon::parse($date);
                $isToday = $cDate->isToday();
                $firstEntry = !empty($dayData['entries']) ? min($dayData['entries']) : null;
                $lastExit = !empty($dayData['exits']) ? max($dayData['exits']) : null;

                $hasLeave = isset($leaveDays[$date]);
                $leaveInfo = $leaveDays[$date] ?? null;

                $statusKey = 'absent';
                $statusLabel = 'Келмаган';
                $badgeClass = 'bg-danger text-white';
                $lateMinutes = 0;

                if ($firstEntry) {
                    $entryTimeOnly = substr($firstEntry, 11, 8); // e.g. 09:15:00
                    if ($entryTimeOnly > '09:00:00') {
                        $diffMin = (int) round((strtotime($entryTimeOnly) - strtotime('09:00:00')) / 60);
                        $lateMinutes = max(1, $diffMin);
                        if ($hasLeave) {
                            $statusKey = 'excused_late';
                            $statusLabel = "Сабабли кечиккан (+{$lateMinutes} дақ)";
                            $badgeClass = 'bg-warning text-dark';
                        } else {
                            $statusKey = 'late';
                            $statusLabel = "Кечиккан (+{$lateMinutes} дақ)";
                            $badgeClass = 'bg-warning text-dark';
                        }
                    } else {
                        $statusKey = 'on_time';
                        $statusLabel = 'Вақтида келган';
                        $badgeClass = 'bg-success text-white';
                    }
                } elseif ($hasLeave) {
                    $statusKey = 'leave';
                    $statusLabel = ($leaveInfo['type_label'] ?? 'Сабабли') . (!empty($leaveInfo['reason']) ? ': ' . mb_substr($leaveInfo['reason'], 0, 30) : '');
                    $badgeClass = 'bg-primary text-white';
                }

                $deviceNames = !empty($dayData['devices']) ? implode(', ', array_keys($dayData['devices'])) : 'Турникет';

                $dailyAttendance[] = [
                    'date'           => $date,
                    'day_name'       => $cDate->format('d.m.Y'),
                    'day_title'      => ($isToday ? 'Бугун, ' : '') . $cDate->format('d.m.Y'),
                    'is_today'       => $isToday,
                    'first_entry'    => $firstEntry ? Carbon::parse($firstEntry)->timezone('Asia/Tashkent')->format('H:i:s') : null,
                    'last_exit'      => $lastExit ? Carbon::parse($lastExit)->timezone('Asia/Tashkent')->format('H:i:s') : null,
                    'status_key'     => $statusKey,
                    'status_label'   => $statusLabel,
                    'badge_class'    => $badgeClass,
                    'late_minutes'   => $lateMinutes,
                    'has_leave'      => $hasLeave,
                    'leave_info'     => $leaveInfo,
                    'device_name'    => $deviceNames,
                ];
            }
        }

        // Stats calculation
        $today = Carbon::today();
        $taskStats = [
            'total'         => $tasks->count(),
            'completed'     => $tasks->where('status', 'bajarildi')->count(),
            'in_process'    => $tasks->filter(fn($t) => in_array($t->status, ['yangi', 'bajarilmoqda']) && $t->end_date >= $today)->count(),
            'overdue'       => $tasks->filter(fn($t) => $t->status === 'bajarilmadi' || (in_array($t->status, ['yangi', 'bajarilmoqda']) && $t->end_date < $today))->count(),
            'extended'      => $tasks->where('status', 'uzaytirildi')->count(),
        ];
        $taskStats['pct'] = $taskStats['total'] > 0 ? round(($taskStats['completed'] / $taskStats['total']) * 100) : 0;

        $articleStats = [
            'total'       => $articles->count(),
            'approved'    => $articles->where('status', 'tasdiqlandi')->count(),
            'total_score' => round($articles->sum(fn($a) => $a->articleScore ? (float)$a->articleScore->total_score : 0), 2),
        ];

        // Birthday countdown
        $birthdayInfo = null;
        if (!empty($employee->birth_date)) {
            $bdate = Carbon::parse($employee->birth_date);
            $now = Carbon::now();
            $nextBirthday = $bdate->copy()->year($now->year);
            if ($nextBirthday->isPast() && !$nextBirthday->isToday()) {
                $nextBirthday->addYear();
            }
            $daysLeft = $now->diffInDays($nextBirthday, false);
            $birthdayInfo = [
                'formatted' => $bdate->format('d.m.Y'),
                'age'       => $bdate->age,
                'days_left' => max(0, ceil($daysLeft)),
                'is_today'  => $bdate->isBirthday(),
            ];
        }

        return view('admin.employees.show', compact(
            'employee',
            'user',
            'tasks',
            'taskStats',
            'articles',
            'articleStats',
            'projects',
            'works',
            'turniketProfile',
            'turniketLogs',
            'turniketLeaves',
            'dailyAttendance',
            'birthdayInfo'
        ));
    }

    /**
     * Turniket rasmlari va ID raqamlarini sinxronlash
     */
    public function syncTurniket(EmployeeSyncService $syncService)
    {
        $res = $syncService->syncTurniketPhotos();
        return back()->with($res['success'] ? 'success' : 'error', $res['message']);
    }


    // 3. STORE: Yangi xodimni bazaga saqlash
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10120',
            'theme' => 'nullable|string|max:50',
            'custom_wish' => 'nullable|string',
            'gender' => 'nullable|string|in:male,female',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Xodim muvaffaqiyatli qo\'shildi!');
    }



    // 6. UPDATE: Xodim ma'lumotlarini yangilash
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10120',
            'theme' => 'nullable|string|max:50',
            'custom_wish' => 'nullable|string',
            'gender' => 'nullable|string|in:male,female',
        ]);

        // Agar yangi rasm yuklansa
        if ($request->hasFile('photo')) {
            // Eskisini o'chirish
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Xodim ma\'lumotlari yangilandi!');
    }

    // 7. DELETE: Xodimni o'chirish
    public function destroy(Employee $employee)
    {
        // Xodimning rasmini xotiradan o'chirish
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Xodim o\'chirildi!');
    }
}
