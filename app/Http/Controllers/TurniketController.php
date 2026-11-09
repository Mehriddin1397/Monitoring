<?php

namespace App\Http\Controllers;

use App\Services\TurniketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TurniketController extends Controller
{
    public const GUARD_IDS = ['00100', '0090', '0091', '00101', '100', '90', '91', '101'];

    protected TurniketService $turniketService;

    public function __construct(TurniketService $turniketService)
    {
        $this->turniketService = $turniketService;
    }

    /**
     * Check if an employee ID belongs to the security guard team
     */
    public function isGuardId(?string $employeeNo): bool
    {
        if (!$employeeNo) {
            return false;
        }
        $trimmed = ltrim($employeeNo, '0');
        return in_array($employeeNo, ['00100', '0090', '0091', '00101'], true)
            || in_array($trimmed, ['100', '90', '91', '101'], true);
    }

    /**
     * Турникет асосий панели (Dashboard - Кўрсаткичлар)
     */
    public function dashboard(): View
    {
        $metricsRes = $this->turniketService->getMetrics();
        $rawMetrics = $metricsRes['data'] ?? [
            'total_employees'       => 0,
            'arrived_count'         => 0,
            'absent_count'          => 0,
            'excused_absent_count'   => 0,
            'unexcused_absent_count' => 0,
            'late_count'            => 0,
            'inside_count'          => 0,
            'outside_count'         => 0,
            'on_leave_count'        => 0,
            'devices_online'        => 0,
            'devices_total'         => 0,
            'work_start_time'       => '09:00',
            'server_time'           => now()->toIso8601String(),
        ];

        $devicesRes = $this->turniketService->getDevices();
        $devices = $devicesRes['data'] ?? [];

        $arrivedRes = $this->turniketService->getArrived();
        $allArrived = $arrivedRes['data'] ?? [];

        $lateRes = $this->turniketService->getLate();
        $allLate = $lateRes['data'] ?? [];

        $insideRes = $this->turniketService->getInside();
        $allInside = is_array($insideRes['data'] ?? null) ? array_values($insideRes['data']) : [];

        $absentRes = $this->turniketService->getAbsent();
        $allAbsent = $absentRes['data'] ?? [];

        $recentAttendanceRes = $this->turniketService->getAttendance(['limit' => 20]);
        $recentAttendance = $recentAttendanceRes['data'] ?? [];

        // Ходимлар рўйхати (кэш ёки API)
        $allEmployees = Cache::remember('turniket_employees_list_v2', 300, function () {
            $res = $this->turniketService->getEmployees(['limit' => 500]);
            return $res['data'] ?? [];
        });

        // 🛡️ Қўриқлаш хизмати ва Асосий ходимларни тўлиқ ажратиш
        $mainEmployees = [];
        $guardEmployees = [];
        foreach ($allEmployees as $emp) {
            $eNo = (string)($emp['employee_no'] ?? '');
            if ($this->isGuardId($eNo)) {
                $guardEmployees[] = $emp;
            } else {
                $mainEmployees[] = $emp;
            }
        }

        // Асосий ходимлар рўйхатлари
        $arrived = array_values(array_filter($allArrived, fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));
        $late    = array_values(array_filter($allLate, fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));
        $inside  = array_values(array_filter($allInside, fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));
        $absent  = array_values(array_filter($allAbsent, fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));

        // Қўриқлаш хизмати рўйхатлари
        $guardArrived = array_values(array_filter($allArrived, fn($i) => $this->isGuardId($i['employee_no'] ?? '')));
        $guardInside  = array_values(array_filter($allInside, fn($i) => $this->isGuardId($i['employee_no'] ?? '')));
        $guardLate    = array_values(array_filter($allLate, fn($i) => $this->isGuardId($i['employee_no'] ?? '')));
        $guardAbsent  = array_values(array_filter($allAbsent, fn($i) => $this->isGuardId($i['employee_no'] ?? '')));

        // Асосий ходимлар метрикалари (Қўриқчиларсиз тоза статистика)
        $metrics = $rawMetrics;
        $metrics['total_employees'] = count($mainEmployees);
        $metrics['arrived_count']   = count($arrived);
        $metrics['inside_count']    = count($inside);
        $metrics['late_count']      = count($late);
        $metrics['absent_count']    = count($absent);

        // Қўриқлаш хизмати метрикалари
        $guardMetrics = [
            'total'   => count($guardEmployees),
            'arrived' => count($guardArrived),
            'inside'  => count($guardInside),
            'late'    => count($guardLate),
            'absent'  => count($guardAbsent),
        ];

        // Қўриқлаш хизмати ходимларининг ҳозирги батафсил ҳолати
        $guardStaff = [];
        foreach ($guardEmployees as $ge) {
            $gNo = (string)($ge['employee_no'] ?? '');

            $arrItem = null;
            foreach ($guardArrived as $ga) {
                if (($ga['employee_no'] ?? '') === $gNo) {
                    $arrItem = $ga;
                    break;
                }
            }

            $isInside = false;
            foreach ($guardInside as $gi) {
                if (($gi['employee_no'] ?? '') === $gNo) {
                    $isInside = true;
                    break;
                }
            }

            $guardStaff[] = [
                'employee_no' => $gNo,
                'fullname'    => $ge['fullname'] ?? ('ID: ' . $gNo),
                'photo_url'   => $ge['photo_url'] ?? null,
                'department'  => 'Қўриқлаш хизмати',
                'is_arrived'  => $arrItem !== null,
                'is_inside'   => $isInside,
                'first_entry' => $arrItem['first_entry'] ?? null,
                'last_exit'   => $arrItem['last_exit'] ?? null,
                'device_name' => $arrItem['device_name'] ?? null,
                'is_late'     => !empty($arrItem['is_late']),
            ];
        }

        $isOnline = $this->turniketService->isOnline();

        return view('admin.turniket.dashboard', compact(
            'metrics',
            'guardMetrics',
            'devices',
            'arrived',
            'late',
            'inside',
            'absent',
            'guardStaff',
            'guardArrived',
            'guardInside',
            'recentAttendance',
            'allEmployees',
            'isOnline'
        ));
    }

    /**
     * Давомат журнали (Real-time attendance log)
     */
    public function attendance(Request $request): View
    {
        $params = [
            'date'      => $request->input('date', date('Y-m-d')),
            'direction' => $request->input('direction'),
            'search'    => $request->input('search'),
            'device_id' => $request->input('device_id'),
            'page'      => $request->input('page', 1),
            'limit'     => 50,
        ];

        // Filter out null / empty values
        $params = array_filter($params, fn($v) => !is_null($v) && $v !== '');

        $attendanceRes = $this->turniketService->getAttendance($params);
        $logs = $attendanceRes['data'] ?? [];
        $pagination = $attendanceRes['pagination'] ?? [
            'total'        => count($logs),
            'per_page'     => 50,
            'current_page' => 1,
            'last_page'    => 1,
        ];

        $devicesRes = $this->turniketService->getDevices();
        $devices = $devicesRes['data'] ?? [];

        $isOnline = $this->turniketService->isOnline();

        return view('admin.turniket.attendance', compact(
            'logs',
            'pagination',
            'devices',
            'isOnline'
        ));
    }

    /**
     * Ҳисоботлар (Кунлик, саналар оралиғи ва алоҳида ходим бўйича ҳисоботлар)
     */
    public function reports(Request $request): View
    {
        $today = date('Y-m-d');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Агар алоҳида date параметри келган бўлса (эски ҳаволалар учун)
        if (!$startDate) {
            $startDate = $request->input('date', $today);
        }
        if (!$endDate) {
            $endDate = $request->input('date', $today);
        }

        // Саналар тартибини тўғрилаш (start_date <= end_date)
        if (strtotime($startDate) > strtotime($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $selectedEmployee = $request->input('employee_filter');
        if ($selectedEmployee === '' || $selectedEmployee === 'all') {
            $selectedEmployee = null;
        }

        $isOnline = $this->turniketService->isOnline();

        // Барча ходимлар рўйхатини кэшдан ёки API орқали олиш (5 дақиқа)
        $allEmployees = Cache::remember('turniket_employees_list_v2', 300, function () {
            $res = $this->turniketService->getEmployees(['limit' => 500]);
            return $res['data'] ?? [];
        });

        // Ходимларни алифбо бўйича саралаш
        usort($allEmployees, function ($a, $b) {
            return strcasecmp($a['fullname'] ?? '', $b['fullname'] ?? '');
        });

        // Ходимларни асосий ва қўриқлаш хизматига ажратиш
        $mainEmployees = [];
        $guardEmployees = [];
        foreach ($allEmployees as $emp) {
            $empNo = (string)($emp['employee_no'] ?? '');
            if ($this->isGuardId($empNo)) {
                $guardEmployees[] = $emp;
            } else {
                $mainEmployees[] = $emp;
            }
        }

        $singleEmployeeData = null;
        $mainRecords = [];
        $guardRecords = [];
        $meta = [
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'total_main'   => 0,
            'total_guard'  => 0,
        ];

        // Агар аниқ битта ходим танланган бўлса — Алоҳида батафсил ҳисобот
        if ($selectedEmployee) {
            // 1. Кунлик жамланган ҳисобот (getReports)
            $reportsRes = $this->turniketService->getReports([
                'start_date'  => $startDate,
                'end_date'    => $endDate,
                'employee_no' => $selectedEmployee,
            ]);
            $dailyRecords = $reportsRes['data'] ?? [];

            // 2. Барча кириш ва чиқиш ҳаракатлари (Attendance logs)
            $allEvents = [];
            $page = 1;
            do {
                $attRes = $this->turniketService->getAttendance([
                    'search'     => $selectedEmployee,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                    'page'       => $page,
                    'limit'      => 100,
                ]);
                $items = $attRes['data'] ?? [];
                foreach ($items as $it) {
                    $itNo = (string)($it['employee_no'] ?? '');
                    if ($itNo === $selectedEmployee || ltrim($itNo, '0') === ltrim($selectedEmployee, '0')) {
                        $allEvents[] = $it;
                    }
                }
                $lastPage = $attRes['pagination']['last_page'] ?? 1;
                $page++;
            } while ($page <= $lastPage && $page <= 5);

            // Ҳаракатларни вақт бўйича саралаш (янгидан эскига)
            usort($allEvents, fn($a, $b) => strcmp($b['event_time'] ?? '', $a['event_time'] ?? ''));

            // Ходим маълумотларини аниқлаш
            $empInfo = null;
            foreach ($allEmployees as $e) {
                $eNo = (string)($e['employee_no'] ?? '');
                if ($eNo === $selectedEmployee || ltrim($eNo, '0') === ltrim($selectedEmployee, '0')) {
                    $empInfo = $e;
                    break;
                }
            }
            if (!$empInfo && !empty($dailyRecords)) {
                $empInfo = [
                    'employee_no' => $selectedEmployee,
                    'fullname'    => $dailyRecords[0]['fullname'] ?? ('ID: ' . $selectedEmployee),
                    'department'  => $dailyRecords[0]['department'] ?? 'Институт',
                    'photo_url'   => null,
                ];
            }

            // Ҳаракатлар статистикаси
            $totalEntries = count(array_filter($allEvents, fn($e) => ($e['direction'] ?? '') === 'entry'));
            $totalExits   = count(array_filter($allEvents, fn($e) => ($e['direction'] ?? '') === 'exit'));
            $totalEvents  = count($allEvents);

            // Ҳаракатларни кунлар бўйича гуруҳлаш
            $eventsByDate = [];
            foreach ($allEvents as $ev) {
                $d = substr($ev['event_time'] ?? '', 0, 10);
                if ($d) {
                    $eventsByDate[$d][] = $ev;
                }
            }

            // Жами кечикиш дақиқалари
            $totalLateMinutes = 0;
            foreach ($dailyRecords as $dr) {
                $totalLateMinutes += (int)($dr['late_minutes'] ?? 0);
            }

            $singleEmployeeData = [
                'employee'           => $empInfo,
                'employee_no'        => $selectedEmployee,
                'is_guard'           => $this->isGuardId($selectedEmployee),
                'daily_records'      => $dailyRecords,
                'events'             => $allEvents,
                'events_by_date'     => $eventsByDate,
                'total_entries'      => $totalEntries,
                'total_exits'        => $totalExits,
                'total_events'       => $totalEvents,
                'total_late_minutes' => $totalLateMinutes,
            ];
        } else {
            // Барча ходимлар бўйича умумий ҳисобот
            $reportsRes = $this->turniketService->getReports([
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ]);
            $allRecords = $reportsRes['data'] ?? [];

            foreach ($allRecords as $r) {
                $empNo = (string)($r['employee_no'] ?? '');
                if ($this->isGuardId($empNo)) {
                    $guardRecords[] = $r;
                } else {
                    $mainRecords[] = $r;
                }
            }

            $meta['total_main']  = count($mainRecords);
            $meta['total_guard'] = count($guardRecords);
        }

        return view('admin.turniket.reports', compact(
            'startDate',
            'endDate',
            'selectedEmployee',
            'allEmployees',
            'mainEmployees',
            'guardEmployees',
            'mainRecords',
            'guardRecords',
            'singleEmployeeData',
            'meta',
            'isOnline'
        ));
    }

    /**
     * Турникет қурилмалари мониторинги
     */
    public function devices(): View
    {
        $devicesRes = $this->turniketService->getDevices();
        $devices = $devicesRes['data'] ?? [];
        $onlineCount = $devicesRes['online_count'] ?? 0;
        $offlineCount = $devicesRes['offline_count'] ?? 0;
        $isOnline = $this->turniketService->isOnline();

        return view('admin.turniket.devices', compact('devices', 'onlineCount', 'offlineCount', 'isOnline'));
    }

    /**
     * Ходимлар рўйхати (Turniket bazasi)
     */
    public function employees(Request $request): View
    {
        $params = [
            'search' => $request->input('search'),
            'page'   => $request->input('page', 1),
            'limit'  => 50,
        ];
        $params = array_filter($params, fn($v) => !is_null($v) && $v !== '');

        $employeesRes = $this->turniketService->getEmployees($params);
        $employees = $employeesRes['data'] ?? [];
        $pagination = $employeesRes['pagination'] ?? [
            'total'        => count($employees),
            'per_page'     => 50,
            'current_page' => 1,
            'last_page'    => 1,
        ];

        $isOnline = $this->turniketService->isOnline();

        return view('admin.turniket.employees', compact('employees', 'pagination', 'isOnline'));
    }

    /**
     * Сабаблилар, таътиллар ва хизмат сафарлари рўйхати (Leaves & Excuses)
     */
    public function leaves(Request $request): View
    {
        $params = [
            'type'            => $request->input('type'),
            'employee_filter' => $request->input('employee_filter'),
            'date'            => $request->input('date'),
        ];
        $params = array_filter($params, fn($v) => !is_null($v) && $v !== '');

        $leavesRes = $this->turniketService->getLeaves($params);
        $leaves = $leavesRes['data'] ?? [];

        $employeesRes = $this->turniketService->getEmployees(['limit' => 200]);
        $allEmployees = $employeesRes['data'] ?? [];

        $isOnline = $this->turniketService->isOnline();

        return view('admin.turniket.leaves', compact('leaves', 'allEmployees', 'isOnline'));
    }

    /**
     * Янги сабаб / кечикиш изоҳи / таътил сақлаш
     */
    public function storeLeave(Request $request)
    {
        if (auth()->user()->role === 'boshliq') {
            abort(403, 'Бошлиқ роли учун маълумот киритиш ёки ўзгартириш чекланган.');
        }

        $validated = $request->validate([
            'employee_no' => 'required|string',
            'reason'      => 'required|string|max:500',
            'type'        => 'nullable|string|in:late_excuse,business_trip,vacation,sick_leave,other',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
            'document_no' => 'nullable|string|max:100',
        ]);

        $res = $this->turniketService->createLeave($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($res, !empty($res['success']) ? 200 : 422);
        }

        if (!empty($res['success'])) {
            return back()->with('success', $res['message'] ?? 'Ходим учун сабаб муваффақиятли сақланди.');
        }

        return back()->with('error', $res['message'] ?? ($res['error'] ?? 'Сабабни сақлашда хатолик юз берди.'))->withInput();
    }

    /**
     * Сабабни ўчириш
     */
    public function destroyLeave($id, Request $request)
    {
        if (auth()->user()->role === 'boshliq') {
            abort(403, 'Бошлиқ роли учун маълумотни ўчириш чекланган.');
        }

        $res = $this->turniketService->deleteLeave($id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($res, !empty($res['success']) ? 200 : 422);
        }

        if (!empty($res['success'])) {
            return back()->with('success', $res['message'] ?? 'Сабаб муваффақиятли ўчирилди.');
        }

        return back()->with('error', $res['message'] ?? ($res['error'] ?? 'Сабабни ўчиришда хатолик.'));
    }

    /**
     * Real-time polling API (sahifani qayta yuklamasdan metrikalarni yangilash)
     */
    public function liveStats(): JsonResponse
    {
        $metricsRes = $this->turniketService->getMetrics();
        $metrics = $metricsRes['data'] ?? [];

        // Ходимларни кэшдан олиш
        $allEmployees = Cache::remember('turniket_employees_list_v2', 300, function () {
            $res = $this->turniketService->getEmployees(['limit' => 500]);
            return $res['data'] ?? [];
        });

        $guardEmployees = array_filter($allEmployees, fn($e) => $this->isGuardId($e['employee_no'] ?? ''));
        $mainEmployees  = array_filter($allEmployees, fn($e) => !$this->isGuardId($e['employee_no'] ?? ''));

        $arrivedRes = $this->turniketService->getArrived();
        $guardArrived = array_filter($arrivedRes['data'] ?? [], fn($i) => $this->isGuardId($i['employee_no'] ?? ''));
        $mainArrivedCount = count(array_filter($arrivedRes['data'] ?? [], fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));

        $insideRes = $this->turniketService->getInside();
        $guardInside = array_filter(is_array($insideRes['data'] ?? null) ? $insideRes['data'] : [], fn($i) => $this->isGuardId($i['employee_no'] ?? ''));
        $mainInsideCount = count(array_filter(is_array($insideRes['data'] ?? null) ? $insideRes['data'] : [], fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));

        $lateRes = $this->turniketService->getLate();
        $mainLateCount = count(array_filter($lateRes['data'] ?? [], fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));

        $absentRes = $this->turniketService->getAbsent();
        $mainAbsentCount = count(array_filter($absentRes['data'] ?? [], fn($i) => !$this->isGuardId($i['employee_no'] ?? '')));

        $metrics['total_employees'] = count($mainEmployees);
        $metrics['arrived_count']   = $mainArrivedCount;
        $metrics['inside_count']    = $mainInsideCount;
        $metrics['late_count']      = $mainLateCount;
        $metrics['absent_count']    = $mainAbsentCount;

        $devicesRes = $this->turniketService->getDevices();

        return response()->json([
            'success'       => true,
            'metrics'       => $metrics,
            'guard_metrics' => [
                'total'   => count($guardEmployees),
                'arrived' => count($guardArrived),
                'inside'  => count($guardInside),
            ],
            'devices'       => $devicesRes['data'] ?? [],
            'updated_at'    => now()->format('H:i:s'),
        ]);
    }
}
