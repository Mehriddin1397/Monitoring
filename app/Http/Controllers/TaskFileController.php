<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
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
                'original_name' => $file->getClientOriginalName()
            ]
        );

        return back()->with('success', 'Hujjat yuklandi');
    }
}
