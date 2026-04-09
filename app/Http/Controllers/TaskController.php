<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Services\EskizSmsService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TaskController extends Controller
{
    public function completed()
    {
        $user = auth()->user();

        $specialIds = [4, 83, 6, 70];
        $users = User::where('role', 'xodim')
            ->orderByRaw("FIELD(id, " . implode(',', $specialIds) . ") DESC")
            ->orderBy('name')
            ->get();

        $categories = Category::forObjectType('tasks'); // View uchun qo'shildi
        $now = now();

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()
                ->with(['assignedUsers', 'creator', 'categories', 'files'])
                ->where('status', 'bajarildi')
                ->orderBy('end_date', 'desc')
                ->paginate(10); // XATO TUG'IRLANDI
        } else {
            $tasks = Task::with(['assignedUsers', 'creator', 'categories', 'files'])
                ->where('status', 'bajarildi')
                ->orderBy('end_date', 'desc')
                ->paginate(30); // XATO TUG'IRLANDI
        }

        $status = 'bajarildi';

        return view('admin.project.index', compact('tasks', 'users', 'categories', 'now', 'status'));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $now = now();
        $statuses = ['yangi', 'bajarilmoqda'];

        // TASKS (oddiy paginate bilan)
        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()
                ->with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $statuses)
                ->whereDate('end_date', '>=', $now)
                ->orderBy('end_date', 'asc')
                ->paginate(30); // 10 tadan sahifalash
        } else {
            $tasks = \App\Models\Task::with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $statuses)
                ->whereDate('end_date', '>=', $now)
                ->orderBy('end_date', 'asc')
                ->paginate(30);
        }

        // USERS (optimizatsiya)
        $specialIds = [4, 83, 6, 70];
        $users = \App\Models\User::where('role', 'xodim')
            ->orderByRaw("FIELD(id, " . implode(',', $specialIds) . ") DESC")
            ->orderBy('name')
            ->get();

        $categories = \App\Models\Category::forObjectType('tasks');

        return view('admin.project.index', compact('tasks', 'users', 'categories', 'now'));
    }

    public function statusFilter($status)
    {
        $user = auth()->user();

        // Optimizatsiya qilingan userlar
        $specialIds = [4, 83, 6, 70];
        $users = User::where('role', 'xodim')
            ->orderByRaw("FIELD(id, " . implode(',', $specialIds) . ") DESC")
            ->orderBy('name')
            ->get();

        $categories = Category::forObjectType('tasks');
        $now = now();

        if ($status === 'bajarilmoqda') {
            $statuses = ['yangi', 'bajarilmoqda'];
        } else {
            $statuses = [$status];
        }

        if ($user->role === 'xodim') {
            $query = $user->assignedTasks()
                ->with(['assignedUsers', 'creator', 'categories', 'files']) // Barcha bog'lanishlar qo'shildi
                ->whereIn('status', $statuses);

            if ($status !== 'bajarildi') {
                $query->whereDate('end_date', '>=', $now);
            }

            // XATO TUG'IRLANDI: get() o'rniga paginate(10)
            $tasks = $query->orderBy('end_date', 'asc')->paginate(30);
        } else {
            $query = Task::with(['assignedUsers', 'creator', 'categories', 'files']) // Barcha bog'lanishlar qo'shildi
            ->whereIn('status', $statuses);

            if ($status !== 'bajarildi') {
                $query->whereDate('end_date', '>=', $now);
            }

            // XATO TUG'IRLANDI: get() o'rniga paginate(10)
            $tasks = $query->orderBy('end_date', 'asc')->paginate(30);
        }

        return view('admin.project.index', compact('tasks', 'users', 'status', 'categories', 'now'));
    }


    public function failedTasks()
    {
        $user = auth()->user();

        $specialIds = [4, 83, 6, 70];
        $users = User::where('role', 'xodim')
            ->orderByRaw("FIELD(id, " . implode(',', $specialIds) . ") DESC")
            ->orderBy('name')
            ->get();

        $categories = Category::forObjectType('tasks'); // View uchun qo'shildi
        $now = now();
        $whereStatus = ['bajarilmadi'];

        if ($user->role === 'xodim') {
            $tasks = $user->assignedTasks()
                ->with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $whereStatus)
                ->orderBy('end_date', 'asc')
                ->paginate(30); // XATO TUG'IRLANDI: get() o'rniga paginate(10)
        } else {
            $tasks = Task::with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $whereStatus)
                ->orderBy('end_date', 'asc')
                ->paginate(30); // XATO TUG'IRLANDI: get() o'rniga paginate(10)
        }

        $status = 'bajarilmagan';

        return view('admin.project.index', compact('tasks', 'users', 'categories', 'now', 'status'));
    }



    public function store(Request $request,EskizSmsService $smsService)
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

        $taskType = $validated['task_type'] ?? 'once';

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
                'end_date' => $taskType === 'yearly'
                    ? Carbon::parse($validated['end_date'])->endOfMonth()
                    : $validated['end_date'],
                'created_by' => auth()->id(),
                'task_type' => $taskType,
                'status' => 'yangi'
            ]);


            $task->assignedUsers()->attach($validated['assigned_users']);
        }





        if ($request->has('categories')) {
            $task->categories()->attach($request->categories);
        }

        // ========================================================
        // 3. YANGI QO'SHILGAN QISM: SMS YUBORISH MANTIQI
        // ========================================================

        // Topshirish sanasini DD.MM.YYYY formatiga o'tkazish
        $formattedDate = Carbon::parse($task->end_date)->format('d.m.Y');

        // SMS matni
        $message = "Sizga Smart-Ijro Nazorat tizimidan yangi topshiriq berildi. Topshirish sanasi: {$formattedDate}";

        // Biriktirilgan barcha xodimlarni bazadan topib kelish
        $assignedUsers = User::whereIn('id', $validated['assigned_users'])->get();

        foreach ($assignedUsers as $user) {
            // Agar xodimning telefon raqami kiritilgan bo'lsa, SMS jo'natish
            if (!empty($user->tel_number)) {
                try {
                    $smsService->send($user->tel_number, $message);
                } catch (\Exception $e) {
                    // Agar SMS ketmay qolsa, tizim qotib qolmasligi uchun xatoni logga yozamiz
                    \Log::error("Yangi topshiriq SMS yuborishda xatolik: " . $e->getMessage());
                }
            }
        }
        // ========================================================

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
        $taskType = $validated['task_type'] ?? 'once';

        $task->update([
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $taskType === 'yearly'
                ? Carbon::parse($validated['end_date'])->endOfMonth()
                : $validated['end_date'],
            'task_type' => $taskType,
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


    //filtr sahifasi uchun
    public function filterPage()
    {
        // Filtrlar uchun kerakli ma'lumotlar
        $specialIds = [4, 83, 6, 70];
        $users = User::where('role', 'xodim')
            ->orderByRaw("FIELD(id, " . implode(',', $specialIds) . ") DESC")
            ->orderBy('name')
            ->get();

        $categories = Category::forObjectType('tasks');

        return view('admin.project.partials.search', compact('users', 'categories'));
    }

    public function searchPage(Request $request)
    {
        $user = auth()->user();
        $now = now();

        // Asosiy so'rov
        if ($user->role === 'xodim') {
            $query = $user->assignedTasks()->with(['assignedUsers', 'creator', 'categories', 'files']);
        } else {
            $query = \App\Models\Task::with(['assignedUsers', 'creator', 'categories', 'files']);
        }

        // 1. Ижрочи бўйича филтр
        if ($request->filled('ijrochi')) {
            $ijrochi = $request->ijrochi;
            $query->whereHas('assignedUsers', function($q) use ($ijrochi) {
                $q->where('name', $ijrochi);
            });
        }

        // 2. Топшириқни берган бўйича филтр
        if ($request->filled('bergan')) {
            $bergan = $request->bergan;
            $query->whereHas('categories', function($q) use ($bergan) {
                $q->where('name', $bergan);
            });
        }

        // 3. Саналар бўйича филтр
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }
        // 4. Status (Holat) bo'yicha filtr
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Natijalarni olish (Masalan, eng so'nggi 50 ta)
        $tasks = $query->orderBy('end_date', 'desc')->take(50)->get();

        // Partial orqali HTML qaytarish
        $html = view('admin.project.partials.search_results', compact('tasks', 'now'))->render();

        return response()->json(['html' => $html]);
    }




}
