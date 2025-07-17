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
    public function completed(){
        $user = auth()->user();
        $users = User::all();
        $now = now();

        $tasks = Task::all();


        return view('admin.project.index', compact('tasks', 'users'));
    }

    public function index() {

        $user = auth()->user();
        $allUsers = User::where('role', 'xodim')->get();

// 4 ta birinchi foydalanuvchi qo'shilgan vaqti bo'yicha
        $firstUsers = $allUsers->sortBy('created_at')->take(4);

// Qolgan foydalanuvchilarni alfavit tartibida
        $remainingUsers = $allUsers->diff($firstUsers)->sortBy('name');

// Yakuniy ro'yxatni birlashtiramiz
        $users = $firstUsers->concat($remainingUsers);

        $now = now();
        $status = 'bajarilmoqda';

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




        return view('admin.project.index', compact('tasks', 'users','status'));
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
            $query = $user->assignedTasks()
                ->with('creator')
                ->whereIn('status', $statuses);

            if ($status !== 'bajarildi') {
                $query->whereDate('end_date', '>=', $now);
            }

            $tasks = $query->orderBy('end_date', 'asc')->get();
        } else {
            $query = Task::with(['assignedUsers', 'creator'])
                ->whereIn('status', $statuses);

            if ($status !== 'bajarildi') {
                $query->whereDate('end_date', '>=', $now);
            }

            $tasks = $query->orderBy('end_date', 'asc')->get();
        }

        return view('admin.project.index', compact('tasks', 'users', 'status'));
    }


    public function failedTasks()
    {
        $user = auth()->user();
        $users = User::all();

        $now = now();

        $whereStatus = ['bajarilmadi'];

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()
                ->with('creator')
                ->whereIn('status', $whereStatus)
                ->orderBy('end_date', 'asc')
                ->get();
        } else {
            $tasks = Task::with(['assignedUsers', 'creator'])
                ->whereIn('status', $whereStatus)
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
        if ($validated['end_date']){
            if ($task->status == 'bajarilmadi')
            {
                $task->status = 'bajarilmoqda';
                $task->save();
            }
        }


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

    public function umumiyStatistika()
    {
        $today = Carbon::today();

        // 1. Faqat "xodim" rolidagi userlarni olish
        $users = User::where('role', 'xodim')->with('assignedTasks')->get();

        // 2. Barcha takrorlanmas (noyob) topshiriqlarni yig‘ish
        $uniqueTasks = $users->flatMap(function ($user) {
            return $user->assignedTasks;
        })->unique('id');

        // 3. Umumiy statistika faqat noyob topshiriqlar bo‘yicha
        $summary = [
            'total' => $uniqueTasks->count(),
            'in_process' => $uniqueTasks->filter(function ($task) use ($today) {
                return in_array($task->status, ['yangi', 'bajarilmoqda']) &&
                    $task->end_date >= $today;
            })->count(),
            'extended' => $uniqueTasks->where('status', 'uzaytirildi')->count(),
            'completed' => $uniqueTasks->where('status', 'bajarildi')->count(),
            'not_completed' => $uniqueTasks->filter(function ($task) use ($today) {
                return $task->status == 'bajarilmadi' ;
            })->count(),
        ];

        // 4. Har bir xodim uchun statistikani hisoblash
        $xodimlar = $users->map(function ($user) use ($today) {
            $tasks = $user->assignedTasks->unique('id'); // takror topshiriqlarni olib tashlash

            $inProcess = $tasks->filter(function ($task) use ($today) {
                return in_array($task->status, ['yangi', 'bajarilmoqda']) &&
                    $task->end_date >= $today;
            });

            $notCompleted = $tasks->filter(function ($task) use ($today) {
                return $task->status == 'bajarilmadi' ;
            });

            return [
                'user' => $user,
                'total' => $tasks->count(),
                'in_process' => $inProcess->count(),
                'extended' => $tasks->where('status', 'uzaytirildi')->count(),
                'completed' => $tasks->where('status', 'bajarildi')->count(),
                'not_completed' => $notCompleted->count(),
            ];
        });

        return view('pages.monitoring', compact('xodimlar', 'summary'));
    }







}
