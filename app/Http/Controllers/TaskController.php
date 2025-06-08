<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index() {
        $user = auth()->user();
        $users = User::all();

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()->with('creator')->get();
        } else {
            $tasks = Task::with('assignedUsers', 'creator')->get();
        }



        return view('admin.project.index', compact('tasks', 'users'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'document' => 'nullable|file|mimes:pdf',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'assigned_users' => 'required|array',
        ]);


        $filePath = $request->file('document')?->store('documents', 'public');

        $task = Task::create([
            'title' => $validated['title'],
            'document' => $filePath,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'created_by' => auth()->user()->id,
        ]);

        $task->assignedUsers()->attach($validated['assigned_users']);

        return redirect()->route('tasks.index');
    }


    public function updateStatus (Request $request, Task $task) {
        if (auth()->id() !== $task->created_by) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:yangi,bajarilmoqda,bajarildi']);
        $task->status = $request->status;
        $task->save();

        return back();
    }

    public function update(Request $request, Task $task)
    {
        // Faqat o‘zining yaratgan topshirig‘ini tahrirlash huquqi
        if (auth()->id() !== $task->created_by ?? auth()->role === 'admin') {
            abort(403);
        }
        // Validatsiya
        $validated = $request->validate([
            'title' => 'required|string',
            'document' => 'nullable|file|mimes:pdf',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'assigned_users' => 'required|array',
        ]);

        // Faylni yangilash (agar bo‘lsa)
        if ($request->hasFile('document')) {
            // Eski faylni o‘chirishingiz mumkin (ixtiyoriy)
            if ($request->hasFile('document')) {
                Storage::delete($task->document);
                $task->document = $request->file('document')->store('documents');
            }

            $filePath = $request->file('document')->store('documents', 'public');
            $task->document = $filePath;
        }

        // Ma'lumotlarni yangilash
        $task->update([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // Assigned user-larni yangilash
        $task->assignedUsers()->sync($validated['assigned_users']);

        return redirect()->route('tasks.index')->with('success', 'Topshiriq yangilandi');
    }

    public function destroy(Task $task){
        $task->delete();

        return back();
    }
}
