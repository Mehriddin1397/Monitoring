<?php

namespace App\Http\Controllers;

use App\Services\TurniketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TurniketController extends Controller
{
    protected TurniketService $turniketService;

    public function __construct(TurniketService $turniketService)
    {
        $this->turniketService = $turniketService;
    }

    /**
     * Турникет асосий панели (Dashboard)
     */
    public function dashboard(): View
    {
        $metricsRes = $this->turniketService->getMetrics();
        $metrics = $metricsRes['data'] ?? [
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
        $arrived = $arrivedRes['data'] ?? [];

        $lateRes = $this->turniketService->getLate();
        $late = $lateRes['data'] ?? [];

        $insideRes = $this->turniketService->getInside();
        $inside = is_array($insideRes['data'] ?? null) ? array_values($insideRes['data']) : [];

        $absentRes = $this->turniketService->getAbsent();
        $absent = $absentRes['data'] ?? [];

        $recentAttendanceRes = $this->turniketService->getAttendance(['limit' => 12]);
        $recentAttendance = $recentAttendanceRes['data'] ?? [];

        $isOnline = $this->turniketService->isOnline();

        return view('admin.turniket.dashboard', compact(
            'metrics',
            'devices',
            'arrived',
            'late',
            'inside',
            'absent',
            'recentAttendance',
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
     * Ҳисоботлар (Daily, Weekly, Monthly reports)
     */
    public function reports(Request $request): View
    {
        $type = $request->input('type', 'daily');
        $date = $request->input('date', date('Y-m-d'));

        $params = [
            'type'            => $type,
            'date'            => $date,
            'start_date'      => $request->input('start_date'),
            'end_date'        => $request->input('end_date'),
            'employee_filter' => $request->input('employee_filter'),
            'status'          => $request->input('status'),
        ];
        $params = array_filter($params, fn($v) => !is_null($v) && $v !== '');

        $reportsRes = $this->turniketService->getReports($params);
        $records = $reportsRes['data'] ?? [];
        $meta = $reportsRes['meta'] ?? [
            'type'          => $type,
            'start_date'    => $date,
            'end_date'      => $date,
            'total_records' => count($records),
        ];

        $isOnline = $this->turniketService->isOnline();

        return view('admin.turniket.reports', compact('records', 'meta', 'type', 'date', 'isOnline'));
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
     * Real-time polling API (sahifani qayta yuklamasdan metrikalarni yangilash)
     */
    public function liveStats(): JsonResponse
    {
        $metricsRes = $this->turniketService->getMetrics();
        $devicesRes = $this->turniketService->getDevices();

        return response()->json([
            'success'    => true,
            'metrics'    => $metricsRes['data'] ?? [],
            'devices'    => $devicesRes['data'] ?? [],
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}
