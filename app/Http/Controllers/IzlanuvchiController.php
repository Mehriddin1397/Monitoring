<?php

namespace App\Http\Controllers;

use App\Services\IzlanuvchiService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IzlanuvchiController extends Controller
{
    protected IzlanuvchiService $service;

    public function __construct(IzlanuvchiService $service)
    {
        $this->service = $service;
    }

    /**
     * Мустақил изланувчилар тизими умумий бошқарув панели (Dashboard)
     */
    public function dashboard()
    {
        $isOnline = $this->service->isOnline();
        $overview = $isOnline ? $this->service->getOverview() : [];
        $workload = $isOnline ? $this->service->getStaffWorkload() : [];

        return view('admin.izlanuvchilar.dashboard', compact('isOnline', 'overview', 'workload'));
    }

    /**
     * Мустақил изланувчилар рўйхати
     */
    public function researchers(Request $request)
    {
        $isOnline = $this->service->isOnline();
        $search = $request->query('search', '');
        $hasProject = $request->query('has_project', '');
        $page = (int) $request->query('page', 1);

        $params = [
            'page'     => $page,
            'per_page' => 20,
        ];

        if ($search !== '') {
            $params['search'] = $search;
        }

        if ($hasProject !== '' && in_array($hasProject, ['0', '1'], true)) {
            $params['has_project'] = (int) $hasProject;
        }

        $res = $isOnline ? $this->service->getResearchers($params) : ['data' => [], 'pagination' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 20, 'total' => 0]];

        $researchers = $res['data'] ?? [];
        $pagination = $res['pagination'] ?? [
            'current_page' => 1,
            'last_page'    => 1,
            'per_page'     => 20,
            'total'        => 0,
        ];

        return view('admin.izlanuvchilar.researchers', compact(
            'isOnline',
            'researchers',
            'pagination',
            'search',
            'hasProject'
        ));
    }

    /**
     * Кўриб чиқилиши керак бўлган вазифалар / ҳужжатлар рўйхати
     */
    public function tasks(Request $request)
    {
        $isOnline = $this->service->isOnline();
        $status = $request->query('status', '');
        $search = $request->query('search', '');
        $page = (int) $request->query('page', 1);

        $params = [
            'page' => $page,
        ];

        if ($status !== '' && in_array($status, ['jarayonda', 'kutilmoqda', 'tasdiqlandi', 'qaytarildi'], true)) {
            $params['status'] = $status;
        }

        if ($search !== '') {
            $params['search'] = $search;
        }

        $res = $isOnline ? $this->service->getKotibTasks($params) : ['data' => [], 'pagination' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 20, 'total' => 0]];

        $tasks = $res['data'] ?? [];
        $pagination = $res['pagination'] ?? [
            'current_page' => 1,
            'last_page'    => 1,
            'per_page'     => 20,
            'total'        => 0,
        ];

        return view('admin.izlanuvchilar.tasks', compact(
            'isOnline',
            'tasks',
            'pagination',
            'status',
            'search'
        ));
    }

    /**
     * Вазифанинг батафсил маълумоти, ҳужжатлар тарихи ва изоҳлар
     */
    public function taskDetail(int $id)
    {
        $isOnline = $this->service->isOnline();
        if (!$isOnline) {
            return redirect()->route('admin.izlanuvchilar.tasks')
                ->with('error', 'Мустақил изланувчилар сервери (192.168.40.104) билан алоқа мавжуд эмас.');
        }

        $task = $this->service->getTask($id);

        if (!$task) {
            return redirect()->route('admin.izlanuvchilar.tasks')
                ->with('error', 'Вазифа маълумотлари топилмади.');
        }

        return view('admin.izlanuvchilar.task_detail', compact('task', 'isOnline'));
    }

    /**
     * Ҳужжатни юклаб олиш (прокси)
     */
    public function download(int $id)
    {
        $response = $this->service->downloadDocument($id);

        if (!$response || !$response->successful()) {
            return back()->with('error', 'Ҳужжат файлини юклаб олишда хатолик юз берди.');
        }

        $contentType = $response->header('Content-Type') ?: 'application/octet-stream';
        $contentDisposition = $response->header('Content-Disposition') ?: 'attachment; filename="document_' . $id . '"';

        return response($response->body(), 200, [
            'Content-Type'        => $contentType,
            'Content-Disposition' => $contentDisposition,
        ]);
    }

    /**
     * Вазифани / ҳужжатни тасдиқлаш (Approve)
     */
    public function approve(Request $request, int $id)
    {
        $staffPhone = $request->user()->tel_number ?? null;
        $result = $this->service->approveTask($id, $staffPhone);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Вазифани / ҳужжатни рад этиш / қайтариш (Reject)
     */
    public function reject(Request $request, int $id)
    {
        $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ], [
            'comment.required' => 'Қайтариш сабаби (изоҳ) кўрсатилиши шарт.',
            'comment.min'      => 'Изоҳ камида 3 та белгидан иборат бўлиши керак.',
        ]);

        $staffPhone = $request->user()->tel_number ?? null;
        $result = $this->service->rejectTask($id, $request->input('comment'), $staffPhone);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
