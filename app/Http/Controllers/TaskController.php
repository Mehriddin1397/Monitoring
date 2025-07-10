<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TaskController extends Controller
{

    public function index() {

        $user = auth()->user();
        $users = User::all();
        $now = now();

        $statuses = ['yangi', 'bajarilmoqda'];

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()
                ->with('creator')
                ->whereIn('status', $statuses)
                ->whereDate('end_date', '>=', $now)
                ->orderBy('end_date', 'asc')
                ->get();
        } else {
            $tasks = Task::with(['assignedUsers', 'creator'])
                ->whereIn('status', $statuses)
                ->whereDate('end_date', '>=', $now)
                ->orderBy('end_date', 'asc')
                ->get();
        }




        return view('admin.project.index', compact('tasks', 'users'));
    }

    public function statusFilter($status)
    {
        $user = auth()->user();
        $users = User::all();
        $now = now();

        if ($status === 'bajarilmoqda') {
            $statuses = ['yangi', 'bajarilmoqda'];
        } else {
            $statuses = [$status];
        }

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()
                ->with('creator')
                ->whereIn('status', $statuses)
                ->whereDate('end_date', '>=', $now)
                ->orderBy('end_date', 'asc')
                ->get();
        } else {
            $tasks = Task::with(['assignedUsers', 'creator'])
                ->whereIn('status', $statuses)
                ->whereDate('end_date', '>=', $now)
                ->orderBy('end_date', 'asc')
                ->get();
        }

        return view('admin.project.index', compact('tasks', 'users', 'status'));
    }

    public function failedTasks()
    {
        $user = auth()->user();
        $users = User::all();

        $now = now();

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()
                ->with('creator')
                ->where('status', '!=', 'bajarildi')
                ->whereDate('end_date', '<', $now)
                ->orderBy('end_date', 'asc')
                ->get();
        } else {
            $tasks = Task::with(['assignedUsers', 'creator'])
                ->where('status', '!=', 'bajarildi')
                ->whereDate('end_date', '<', $now)
                ->orderBy('end_date', 'asc')
                ->get();
        }

        return view('admin.project.index', compact('tasks', 'users'))->with('status', 'bajarilmagan');
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:10000',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'assigned_users' => 'required|array',
        ]);




        $task = Task::create([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'created_by' => auth()->user()->id,
        ]);

        $task->assignedUsers()->attach($validated['assigned_users']);

        return redirect()->route('tasks.index');
    }


    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:yangi,bajarilmoqda,uzaytirildi,bajarildi',
            'end_date' => 'nullable|date',
        ]);

        if (auth()->id() !== $task->created_by) {
            abort(403);
        }

        if (!$task->document && $request->status == "bajarildi") {
            abort(403, 'Хужжат юкланмаган');
        }



        $task->status = $request->status;

        if ($request->status === 'uzaytirildi') {
            if (!$request->filled('end_date')) {
                return back()->withErrors(['end_date' => 'Илтимос, янги муддатни танланг']);
            }
            $task->end_date = $request->end_date; // Eslatma: bu ustun mavjud bo‘lishi kerak
        }

        $task->save();

        return redirect()->back()->with('success', 'Статус янгиланди');
    }


    public function update(Request $request, Task $task)
    {
        // Faqat o‘zining yaratgan topshirig‘ini tahrirlash huquqi
        if (auth()->id() !== $task->created_by ?? auth()->role === 'admin') {
            abort(403);
        }
        // Validatsiya
        $validated = $request->validate([
            'title' => 'required|string|max:10000',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'assigned_users' => 'required|array',
        ]);


        // Ma'lumotlarni yangilash
        $task->update([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // Assigned user-larni yangilash
        $task->assignedUsers()->sync($validated['assigned_users']);

        return redirect()->back()->with('success', 'Статус янгиланди');
    }

    public function destroy(Task $task){
        $task->delete();

        return back();
    }


    public function search(Request $request)
    {
        $search = $request->input('query');
        $users = User::all();


        $tasks = Task::with('assignedUsers')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhereHas('assignedUsers', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->get();


        return view('admin.project.search', compact('tasks','search', 'users'));
    }

    public function uploadfile(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,png,txt|max:20480',
            'task_id' => 'required|exists:tasks,id',
        ]);

        $task = Task::find($request->task_id);
        $user = $request->user();

        if (!$task->assignedUsers->contains($user->id)) {
            return redirect()->back()->with('error', 'Siz bu topshiriqqa fayl yuklay olmaysiz.');
        }

        $path = $request->file('document')->store('documents', 'public');
        $task->document = $path;
        $task->save();

        return redirect()->back()->with('success', 'Fayl muvaffaqiyatli yuklandi!');
    }




}
