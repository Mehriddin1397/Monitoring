<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index() {
        $user = auth()->user();

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()->with('creator')->get();
        } else {
            $tasks = Task::with('assignedUsers', 'creator')->get();
        }

        return view('admin.project.index', compact('tasks'));
    }

    public function create() {
        $employees = User::where('role', 'xodim')->get();
        return view('tasks.create', compact('employees'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string',
            'document' => 'nullable|file|mimes:pdf',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'assigned_users' => 'required|array',
        ]);

        $filePath = $request->file('document')?->store('documents');

        $task = Task::create([
            'title' => $validated['title'],
            'document' => $filePath,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'created_by' => auth()->id(),
        ]);

        $task->assignedUsers()->attach($validated['assigned_users']);

        return redirect()->route('tasks.index');
    }

    public function updateStatus(Request $request, Task $task) {
        if (auth()->id() !== $task->created_by) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:yangi,bajarilmoqda,bajarildi']);
        $task->status = $request->status;
        $task->save();

        return back();
    }
}
