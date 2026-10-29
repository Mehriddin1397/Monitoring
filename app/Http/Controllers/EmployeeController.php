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
        $employee = Employee::with([
            'user.assignedTasks.statuses',
            'user.assignedTasks.files',
            'user.articles.articleScore',
            'user.projects',
            'works'
        ])->where('id', $id)->orWhere('user_id', $id)->firstOrFail();

        $user = $employee->user;
        $tasks = $user ? $user->assignedTasks->unique('id') : collect();
        $articles = $user ? $user->articles : collect();
        $projects = $user ? $user->projects : collect();
        $works = $employee->works;

        // Turniket attendance
        $turniketProfile = null;
        $turniketLogs = [];
        $turniketLeaves = [];
        if (!empty($employee->turniket_id)) {
            $turniketProfileRes = $turniketService->getEmployee($employee->turniket_id);
            $turniketProfile = $turniketProfileRes['data'] ?? null;

            $turniketLogsRes = $turniketService->getAttendance(['employee_no' => $employee->turniket_id, 'limit' => 20]);
            $turniketLogs = $turniketLogsRes['data'] ?? [];

            $turniketLeavesRes = $turniketService->getLeaves(['employee_filter' => $employee->turniket_id]);
            $turniketLeaves = $turniketLeavesRes['data'] ?? [];
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
