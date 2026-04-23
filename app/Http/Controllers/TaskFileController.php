<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use App\Services\EskizSmsService;
use Illuminate\Http\Request;

class TaskFileController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|file|max:10240',
            'task_id' => 'required|exists:tasks,id',
        ]);
        $task = Task::find($request->task_id);

        // faqat biriktirilgan xodim yuklay oladi
        abort_unless(
            $task->assignedUsers->contains(auth()->id()),
            403
        );





        $file = $request->file('file');

        $path = $file->store('task_files','public');

        TaskFile::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => auth()->id()
            ],
            [
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'status' => 'pending', // 🔥 har safar qayta pending
                'reject_reason' => null,
                'checked_by' => null
            ]
        );

        return back()->with('success', 'Hujjat yuklandi');
    }
    public function approve($id)
    {
        $file = TaskFile::findOrFail($id);

        $file->update([
            'status' => 'approved',
            'checked_by' => auth()->id(),
            'reject_reason' => null
        ]);

        return back()->with('success', 'Tasdiqlandi');
    }

    public function reject(Request $request, $id, EskizSmsService $smsService)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $file = TaskFile::with('user')->findOrFail($id);

        $file->update([
            'status' => 'rejected',
            'reject_reason' => $request->reason,
            'checked_by' => auth()->id()
        ]);

        // 🔥 agar userda telefon bo‘lsa
        if ($file->user && !empty($file->user->tel_number)) {

            $phone = $file->user->tel_number;

            $message = "Smart-ijro nazorat tizimiga yuklangan hujjat asossiz bo'lganligi sababli rad qilindi. Belgilangan tartibda asosli bildirgi va ilova hujjatni bir faylda yuklashingizni so'raymiz!!!";

            try {
                $smsService->send($phone, $message);
            } catch (\Exception $e) {
                \Log::error("Reject SMS xatolik: " . $e->getMessage());
            }
        }

        return back()->with('error', 'Rad qilindi va SMS yuborildi');
    }


}
