<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
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
        $categories = Category::forObjectType('tasks');
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




        return view('admin.project.index', compact('tasks', 'users','status','categories'));
    }

    public function statusFilter($status)
    {
        $user = auth()->user();
        $users = User::all();
        $now = now();
        $categories = Category::forObjectType('tasks');

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

        return view('admin.project.index', compact('tasks', 'users', 'status','categories'));
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
            'categories' => 'array',  // Kategoriyalar array bo‘lishi kerak
            'task_type' => 'required|in:once,yearly',
            'categories.*' => 'exists:categories,id',// Kategoriyalar faqat mavjud IDlar bo‘lishi kerak
        ]);

        if (auth()->user()->id == 26) {
            $task = Task::create([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['task_type'] === 'yearly'
                    ? Carbon::parse($validated['end_date'])->endOfMonth()
                    : $validated['end_date'],
                'created_by' => 2,
                'task_type' => $validated['task_type'],
                'status' => 'yangi'
            ]);

            $task->assignedUsers()->attach($validated['assigned_users']);
        }
        else {

            $task = Task::create([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['task_type'] === 'yearly'
                    ? Carbon::parse($validated['end_date'])->endOfMonth()
                    : $validated['end_date'],
                'created_by' => auth()->id(),
                'task_type' => $validated['task_type'],
                'status' => 'yangi'
            ]);

            $task->assignedUsers()->attach($validated['assigned_users']);
        }





        if ($request->has('categories')) {
            $task->categories()->attach($request->categories);
        }

        return redirect()->route('tasks.index');
    }



    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:yangi,bajarilmoqda,uzaytirildi,bajarildi,bajarilmadi',
            'end_date' => 'nullable|date',
        ]);

        if (auth()->id() !== $task->created_by) {
            abort(403);
        }

        $oldStatus = $task->status;
        $newStatus = $request->status;
        $today = now();

        // 1. Titlega status o'zgargan sanani yozish
        $task->title = $task->title . " (Status o'zgardi: " . $today->format('d.m.Y') . ")";
        $task->status = $newStatus;

        if ($newStatus === 'uzaytirildi') {
            $task->end_date = $request->end_date;
        }

        $task->save();

        // Statuslar tarixini saqlash
        $task->statuses()->create(['status' => $newStatus]);

        // 2. YILLIK TOPSHIRIQ MANTIQI
        // Agar topshiriq yillik bo'lsa va u yakunlansa (bajarildi yoki bajarilmadi)
        if ($task->task_type === 'yearly' && in_array($newStatus, ['bajarildi', 'bajarilmadi'])) {

            $currentDeadline = Carbon::parse($task->end_date);
            $nextMonthDeadline = $currentDeadline->copy()->addMonth()->endOfMonth();

            // Agar keyingi oy hali shu yilning ichida bo'lsa
            if ($nextMonthDeadline->year == $currentDeadline->year) {

                // Yangi topshiriq nusxasini yaratish
                $newTask = Task::create([
                    'title' => $this->cleanTitle($task->getOriginal('title')), // Qavssiz original nomni olish
                    'start_date' => $currentDeadline->copy()->addDay(), // Keyingi kun boshlanadi
                    'end_date' => $nextMonthDeadline,
                    'created_by' => $task->created_by,
                    'status' => 'yangi',
                    'task_type' => 'yearly',
                ]);

                // Xodimlarni biriktirish
                $newTask->assignedUsers()->attach($task->assignedUsers->pluck('id')->toArray());

                // Kategoriyalarni biriktirish
                $newTask->categories()->attach($task->categories->pluck('id')->toArray());
            }
        }

        return redirect()->back()->with('success', 'Статус янгиланди va keyingi oy uchun topshiriq yaratildi');
    }

    /**
     * Titleni tozalash (oldingi sanalarni olib tashlash uchun yordamchi funksiya)
     */
    private function cleanTitle($title) {
        return preg_replace('/\s\(Status o\'zgardi:.*?\)/', '', $title);
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
            'task_type' => 'required|in:once,yearly',
        ]);


        // Ma'lumotlarni yangilash
        $task->update([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['task_type'] === 'yearly'
                ? Carbon::parse($validated['end_date'])->endOfMonth()
                : $validated['end_date'],
            'task_type' => $validated['task_type'],
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


        // Kategoriyalarni yangilash (eski kategoriyalarni o‘chirib, yangilarini qo‘shish)
        if ($request->has('categories') && !empty($request->categories)) {

            $task->categories()->sync($request->categories);
        } else {
            // Agar hech narsa tanlanmasa, barcha bog‘lanishlarni olib tashlaydi
            $task->categories()->detach();
        }

        return redirect()->back()->with('success', 'Статус янгиланди');
    }

    public function destroy(Task $task){
        if (auth()->check() && auth()->user()->role === 'admin') {
        $task->delete();

        return back();
        }
        else
            abort(403, 'Sizga bu sahifaga kirish taqiqlangan.');
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

        return view('pages.monitoring', compact( 'summary','xodimlar'));
    }







}
