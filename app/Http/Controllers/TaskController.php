<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Article;
use App\Models\ArticleScore;
use App\Models\Project;
use App\Models\Participant;
use App\Models\Pro_document;
use App\Models\Employee;
use App\Services\EskizSmsService;
use App\Services\TurniketService;
use App\Services\EmployeeSyncService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class TaskController extends Controller
{
    private function applyTaskFilters($query, Request $request)
    {
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            $query->whereHas('assignedUsers', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        } elseif ($request->filled('ijrochi')) {
            $ijrochi = $request->ijrochi;
            $query->whereHas('assignedUsers', function ($q) use ($ijrochi) {
                $q->where('name', $ijrochi)->orWhere('users.id', $ijrochi);
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('end_date', '>=', $request->start_date)
                  ->whereDate('end_date', '<=', $request->end_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('end_date', '=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        return $query;
    }

    public function completed(Request $request)
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
            $query = $user->assignedTasks()
                ->with(['assignedUsers', 'creator', 'categories', 'files'])
                ->where('status', 'bajarildi');
        } else {
            $query = Task::with(['assignedUsers', 'creator', 'categories', 'files'])
                ->where('status', 'bajarildi');
        }

        $query = $this->applyTaskFilters($query, $request);
        $tasks = $query->orderBy('end_date', 'desc')->paginate(30)->withQueryString();

        $status = 'bajarildi';

        return view('admin.project.index', compact('tasks', 'users', 'categories', 'now', 'status'));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $now = now();
        $statuses = ['yangi', 'bajarilmoqda'];

        if ($user->role === 'xodim') {
            $query = $user->assignedTasks()
                ->with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $statuses);
        } else {
            $query = \App\Models\Task::with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $statuses);
        }

        if (!$request->filled('end_date') && !$request->filled('start_date')) {
            $query->whereDate('end_date', '>=', $now);
        }

        $query = $this->applyTaskFilters($query, $request);
        $tasks = $query->orderBy('end_date', 'asc')->paginate(30)->withQueryString();

        // USERS (optimizatsiya)
        $specialIds = [4, 83, 6, 70];
        $users = \App\Models\User::where('role', 'xodim')
            ->orderByRaw("FIELD(id, " . implode(',', $specialIds) . ") DESC")
            ->orderBy('name')
            ->get();

        $categories = \App\Models\Category::forObjectType('tasks');

        return view('admin.project.index', compact('tasks', 'users', 'categories', 'now'));
    }

    public function statusFilter(Request $request, $status)
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
                ->with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $statuses);
        } else {
            $query = Task::with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $statuses);
        }

        if ($status !== 'bajarildi' && !$request->filled('end_date') && !$request->filled('start_date')) {
            $query->whereDate('end_date', '>=', $now);
        }

        $query = $this->applyTaskFilters($query, $request);
        $tasks = $query->orderBy('end_date', 'asc')->paginate(30)->withQueryString();

        return view('admin.project.index', compact('tasks', 'users', 'status', 'categories', 'now'));
    }


    public function failedTasks(Request $request)
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
            $query = $user->assignedTasks()
                ->with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $whereStatus);
        } else {
            $query = Task::with(['assignedUsers', 'creator', 'categories', 'files'])
                ->whereIn('status', $whereStatus);
        }

        $query = $this->applyTaskFilters($query, $request);
        $tasks = $query->orderBy('end_date', 'asc')->paginate(30)->withQueryString();

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

        if (!in_array(auth()->user()->role, ['admin', 'boshliq']) && auth()->id() !== $task->created_by) {
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
        // Faqat o‘zining yaratgan topshirig‘ini yoki admin / boshliq tahrirlash huquqi
        if (!in_array(auth()->user()->role, ['admin', 'boshliq']) && auth()->id() !== $task->created_by) {
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

    public function destroy(Task $task)
    {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'boshliq'])) {
            $task->delete();

            return back()->with('success', 'Topshiriq muvaffaqiyatli o\'chirildi');
        } else {
            abort(403, 'Sizga bu sahifaga kirish taqiqlangan.');
        }
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

    public function umumiyStatistika(TurniketService $turniketService)
    {
        $today = Carbon::today();

        // 1. Ensure any User with role 'xodim' has an Employee profile, and link matching names
        try {
            $unlinkedEmployees = Employee::whereNull('user_id')->get();
            foreach ($unlinkedEmployees as $emp) {
                $matchedUser = User::where('name', $emp->full_name)->first();
                if ($matchedUser) {
                    $emp->user_id = $matchedUser->id;
                    $emp->save();
                }
            }

            $xodimUsers = User::where('role', 'xodim')->get();
            foreach ($xodimUsers as $u) {
                if (!Employee::where('user_id', $u->id)->exists() && !Employee::where('full_name', $u->name)->exists()) {
                    Employee::create([
                        'user_id' => $u->id,
                        'full_name' => $u->name,
                        'department' => $u->department ?? 'Бошқарув',
                        'position' => $u->position ?? 'Мутахассис',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Employee-User sync in umumiyStatistika: ' . $e->getMessage());
        }

        // 2. Fetch all employees with full relations
        $employees = Employee::with([
            'user.assignedTasks',
            'user.articles.articleScore',
            'user.projects',
            'works'
        ])->orderBy('full_name')->get();

        // 3. Turniket real-time data
        $insideMap = [];
        $lateMap = [];
        $arrivedMap = [];
        $absentMap = [];

        try {
            $insideRes = $turniketService->getInsideEmployees();
            if (!empty($insideRes['data'])) {
                foreach ($insideRes['data'] as $item) {
                    $no = (string)($item['employee_no'] ?? '');
                    if ($no !== '') $insideMap[$no] = $item;
                    $norm = EmployeeSyncService::normalizeName($item['fullname'] ?? '');
                    if ($norm !== '') $insideMap['name_' . $norm] = $item;
                }
            }

            $lateRes = $turniketService->getLateEmployees();
            if (!empty($lateRes['data'])) {
                foreach ($lateRes['data'] as $item) {
                    $no = (string)($item['employee_no'] ?? '');
                    if ($no !== '') $lateMap[$no] = $item;
                    $norm = EmployeeSyncService::normalizeName($item['fullname'] ?? '');
                    if ($norm !== '') $lateMap['name_' . $norm] = $item;
                }
            }

            $arrivedRes = $turniketService->getArrivedEmployees();
            if (!empty($arrivedRes['data'])) {
                foreach ($arrivedRes['data'] as $item) {
                    $no = (string)($item['employee_no'] ?? '');
                    if ($no !== '') $arrivedMap[$no] = $item;
                    $norm = EmployeeSyncService::normalizeName($item['fullname'] ?? '');
                    if ($norm !== '') $arrivedMap['name_' . $norm] = $item;
                }
            }

            $absentRes = $turniketService->getAbsentEmployees();
            if (!empty($absentRes['data'])) {
                foreach ($absentRes['data'] as $item) {
                    $no = (string)($item['employee_no'] ?? '');
                    if ($no !== '') $absentMap[$no] = $item;
                    $norm = EmployeeSyncService::normalizeName($item['fullname'] ?? '');
                    if ($norm !== '') $absentMap['name_' . $norm] = $item;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Turniket fetch failed in umumiyStatistika: ' . $e->getMessage());
        }

        // 4. Unique tasks collection for global summary
        $allAssignedTasks = collect();

        // 5. Build rich dossiers
        $departments = [];
        $totalScoresAll = 0;
        $totalArticlesAll = 0;
        $totalProjectsAll = 0;
        $totalWorksAll = 0;

        $xodimlar = $employees->map(function ($emp) use (
            $today,
            $insideMap,
            $lateMap,
            $arrivedMap,
            $absentMap,
            &$allAssignedTasks,
            &$departments,
            &$totalScoresAll,
            &$totalArticlesAll,
            &$totalProjectsAll,
            &$totalWorksAll
        ) {
            if (!empty($emp->department)) {
                $departments[$emp->department] = true;
            }

            $user = $emp->user ?? new User(['name' => $emp->full_name, 'role' => 'xodim']);

            // Tasks stats
            $tasks = ($emp->user && $emp->user->assignedTasks) ? $emp->user->assignedTasks->unique('id') : collect();
            foreach ($tasks as $t) {
                $allAssignedTasks->push($t);
            }

            $inProcess = $tasks->filter(function ($task) use ($today) {
                return in_array($task->status, ['yangi', 'bajarilmoqda']) && $task->end_date >= $today;
            });
            $extended = $tasks->where('status', 'uzaytirildi');
            $completed = $tasks->where('status', 'bajarildi');
            $notCompleted = $tasks->filter(function ($task) use ($today) {
                return $task->status === 'bajarilmadi' || (in_array($task->status, ['yangi', 'bajarilmoqda']) && $task->end_date < $today);
            });

            $totalTasks = $tasks->count();
            $completedCount = $completed->count();
            $taskPercent = $totalTasks > 0 ? (int) round(($completedCount / $totalTasks) * 100) : 0;

            // I-index stats
            $articles = ($emp->user && $emp->user->articles) ? $emp->user->articles : collect();
            $articlesCount = $articles->count();
            $iIndexScore = (float) $articles->sum(function ($art) {
                return $art->articleScore ? (float) $art->articleScore->total_score : 0;
            });
            $totalArticlesAll += $articlesCount;
            $totalScoresAll += $iIndexScore;

            // Projects count
            $projectsCount = ($emp->user && $emp->user->projects) ? $emp->user->projects->count() : 0;
            $totalProjectsAll += $projectsCount;

            // Works count
            $worksCount = $emp->works ? $emp->works->count() : 0;
            $totalWorksAll += $worksCount;

            // Birthday stats
            $birthdayInfo = null;
            if (!empty($emp->birth_date)) {
                try {
                    $birthDate = Carbon::parse($emp->birth_date);
                    $age = $birthDate->age;
                    $nextBirthday = $birthDate->copy()->year($today->year);
                    if ($nextBirthday->isPast() && !$nextBirthday->isToday()) {
                        $nextBirthday->addYear();
                    }
                    $daysUntil = (int) $today->diffInDays($nextBirthday, false);
                    $isToday = $nextBirthday->isToday();

                    $birthdayInfo = [
                        'formatted' => $birthDate->format('d.m.Y'),
                        'age' => $age,
                        'days_until' => $daysUntil,
                        'is_today' => $isToday,
                        'badge' => $isToday ? '🎉 Бугун таваллуд куни!' : ($daysUntil === 1 ? 'Эртага' : "{$daysUntil} кун қолди"),
                    ];
                } catch (\Throwable $e) {}
            }

            // Attendance matching: turniket_id first, then normalized name
            $tId = (string) ($emp->turniket_id ?? '');
            $norm = EmployeeSyncService::normalizeName($emp->full_name);
            $normUser = $emp->user ? EmployeeSyncService::normalizeName($emp->user->name) : '';

            $isInside = false;
            $isLate = false;
            $isArrived = false;
            $isAbsent = false;
            $hasExcuse = false;
            $excuseReason = null;
            $excuseType = null;
            $entryTime = null;
            $lateMinutes = 0;

            // Check inside
            if (($tId !== '' && isset($insideMap[$tId])) || ($norm !== '' && isset($insideMap['name_' . $norm])) || ($normUser !== '' && isset($insideMap['name_' . $normUser]))) {
                $isInside = true;
                $isArrived = true;
                $match = $insideMap[$tId] ?? ($insideMap['name_' . $norm] ?? $insideMap['name_' . $normUser]);
                $entryTime = $match['first_entry'] ?? null;
            }

            // Check late
            if (($tId !== '' && isset($lateMap[$tId])) || ($norm !== '' && isset($lateMap['name_' . $norm])) || ($normUser !== '' && isset($lateMap['name_' . $normUser]))) {
                $isLate = true;
                $match = $lateMap[$tId] ?? ($lateMap['name_' . $norm] ?? $lateMap['name_' . $normUser]);
                $lateMinutes = (int) ($match['late_minutes'] ?? 0);
                $entryTime = $entryTime ?: ($match['first_entry'] ?? null);
                if (!empty($match['has_excuse'])) {
                    $hasExcuse = true;
                    $excuseReason = $match['excuse_reason'] ?? null;
                    $excuseType = $match['excuse_type'] ?? null;
                }
            }

            // Check arrived
            if (!$isArrived && (($tId !== '' && isset($arrivedMap[$tId])) || ($norm !== '' && isset($arrivedMap['name_' . $norm])) || ($normUser !== '' && isset($arrivedMap['name_' . $normUser])))) {
                $isArrived = true;
                $match = $arrivedMap[$tId] ?? ($arrivedMap['name_' . $norm] ?? $arrivedMap['name_' . $normUser]);
                $entryTime = $entryTime ?: ($match['first_entry'] ?? null);
            }

            // Check absent
            if (!$isArrived && (($tId !== '' && isset($absentMap[$tId])) || ($norm !== '' && isset($absentMap['name_' . $norm])) || ($normUser !== '' && isset($absentMap['name_' . $normUser])))) {
                $isAbsent = true;
                $match = $absentMap[$tId] ?? ($absentMap['name_' . $norm] ?? $absentMap['name_' . $normUser]);
                if (!empty($match['has_excuse'])) {
                    $hasExcuse = true;
                    $excuseReason = $match['excuse_reason'] ?? null;
                    $excuseType = $match['excuse_type'] ?? null;
                }
            }

            // Determine attendance display status
            if ($hasExcuse) {
                $attStatus = 'excused';
                $attLabel = 'Сабабли' . ($excuseReason ? ': ' . mb_substr($excuseReason, 0, 20) . '...' : '');
                $attBadge = 'bg-primary text-white';
            } elseif ($isInside) {
                $attStatus = 'inside';
                $attLabel = 'Бинода' . ($entryTime ? ' (' . substr($entryTime, 11, 5) . ')' : '');
                $attBadge = 'bg-success text-white';
            } elseif ($isLate) {
                $attStatus = 'late';
                $attLabel = "Кечиккан (+{$lateMinutes} дақ)";
                $attBadge = 'bg-warning text-dark';
            } elseif ($isArrived) {
                $attStatus = 'arrived';
                $attLabel = 'Келган' . ($entryTime ? ' (' . substr($entryTime, 11, 5) . ')' : '');
                $attBadge = 'bg-info text-white';
            } elseif ($isAbsent) {
                $attStatus = 'absent';
                $attLabel = 'Келмаган';
                $attBadge = 'bg-danger text-white';
            } else {
                $attStatus = 'unknown';
                $attLabel = '—';
                $attBadge = 'bg-secondary text-white';
            }

            return [
                'id' => $emp->id,
                'user_id' => $emp->user_id,
                'employee' => $emp,
                'user' => $user,
                'name' => $emp->full_name,
                'position' => $emp->position ?? 'Мутахассис',
                'department' => $emp->department ?? 'Бошқарув',
                'rank' => $emp->rank,
                'avatar' => $emp->avatar_url,
                'turniket_id' => $emp->turniket_id,
                'attendance' => [
                    'status' => $attStatus,
                    'label' => $attLabel,
                    'badge' => $attBadge,
                    'is_inside' => $isInside,
                    'is_late' => $isLate,
                    'is_arrived' => $isArrived,
                    'is_absent' => $isAbsent,
                    'has_excuse' => $hasExcuse,
                    'excuse_reason' => $excuseReason,
                    'excuse_type' => $excuseType,
                    'entry_time' => $entryTime,
                    'late_minutes' => $lateMinutes,
                ],
                'tasks' => [
                    'total' => $totalTasks,
                    'in_process' => $inProcess->count(),
                    'extended' => $extended->count(),
                    'completed' => $completedCount,
                    'not_completed' => $notCompleted->count(),
                    'percent' => $taskPercent,
                ],
                'i_index' => [
                    'articles_count' => $articlesCount,
                    'score' => $iIndexScore,
                ],
                'projects_count' => $projectsCount,
                'works_count' => $worksCount,
                'birthday' => $birthdayInfo,

                // Backwards-compatible keys
                'total' => $totalTasks,
                'in_process' => $inProcess->count(),
                'extended' => $extended->count(),
                'completed' => $completedCount,
                'not_completed' => $notCompleted->count(),
            ];
        });

        $uniqueTasks = $allAssignedTasks->unique('id');

        $tasksTotal = $uniqueTasks->count();
        $tasksCompleted = $uniqueTasks->where('status', 'bajarildi')->count();
        $tasksInProcess = $uniqueTasks->filter(function ($task) use ($today) {
            return in_array($task->status, ['yangi', 'bajarilmoqda']) && $task->end_date >= $today;
        })->count();
        $tasksExtended = $uniqueTasks->where('status', 'uzaytirildi')->count();
        $tasksNotCompleted = $uniqueTasks->filter(function ($task) use ($today) {
            return $task->status === 'bajarilmadi' || (in_array($task->status, ['yangi', 'bajarilmoqda']) && $task->end_date < $today);
        })->count();
        $tasksPercent = $tasksTotal > 0 ? (int) round(($tasksCompleted / $tasksTotal) * 100) : 0;

        $summary = [
            'total' => $tasksTotal,
            'in_process' => $tasksInProcess,
            'extended' => $tasksExtended,
            'completed' => $tasksCompleted,
            'not_completed' => $tasksNotCompleted,
            'percent' => $tasksPercent,

            'total_employees' => $employees->count(),
            'total_articles' => $totalArticlesAll,
            'total_score' => $totalScoresAll,
            'total_projects' => $totalProjectsAll,
            'total_works' => $totalWorksAll,

            // Attendance metrics
            'inside_count' => count(array_filter($xodimlar->toArray(), fn($x) => $x['attendance']['is_inside'])),
            'late_count' => count(array_filter($xodimlar->toArray(), fn($x) => $x['attendance']['is_late'])),
            'absent_count' => count(array_filter($xodimlar->toArray(), fn($x) => $x['attendance']['is_absent'] && !$x['attendance']['has_excuse'])),
            'excused_count' => count(array_filter($xodimlar->toArray(), fn($x) => $x['attendance']['has_excuse'])),
            'arrived_count' => count(array_filter($xodimlar->toArray(), fn($x) => $x['attendance']['is_arrived'])),

            'departments' => array_keys($departments),
        ];

        // Prepare department rankings for ApexCharts
        $deptMap = [];
        foreach ($xodimlar as $item) {
            $dep = $item['department'] ?: 'Бошқарув';
            if (!isset($deptMap[$dep])) {
                $deptMap[$dep] = [
                    'name' => $dep,
                    'employees_count' => 0,
                    'completed_tasks' => 0,
                    'total_tasks' => 0,
                    'i_index_score' => 0,
                    'inside_count' => 0,
                ];
            }
            $deptMap[$dep]['employees_count']++;
            $deptMap[$dep]['completed_tasks'] += $item['tasks']['completed'] ?? 0;
            $deptMap[$dep]['total_tasks'] += $item['tasks']['total'] ?? 0;
            $deptMap[$dep]['i_index_score'] += $item['i_index']['score'] ?? 0;
            if (!empty($item['attendance']['is_inside'])) {
                $deptMap[$dep]['inside_count']++;
            }
        }
        usort($deptMap, fn($a, $b) => $b['total_tasks'] <=> $a['total_tasks']);
        $summary['dept_stats'] = array_slice($deptMap, 0, 8);

        return view('pages.monitoring', compact('summary', 'xodimlar'));
    }

    public function statistikaDiagramma(Request $request)
    {
        $today = Carbon::today();
        
        $period = $request->get('period', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $categoryId = $request->get('category_id');

        $usersQuery = User::where('role', 'xodim')->with(['assignedTasks' => function ($q) use ($period, $startDate, $endDate, $categoryId) {
            if ($period === 'today') {
                $q->whereDate('tasks.created_at', Carbon::today());
            } elseif ($period === 'this_month') {
                $q->whereYear('tasks.created_at', Carbon::now()->year)
                  ->whereMonth('tasks.created_at', Carbon::now()->month);
            } elseif ($period === 'this_year') {
                $q->whereYear('tasks.created_at', Carbon::now()->year);
            } elseif ($startDate && $endDate) {
                $q->whereBetween('tasks.created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            if ($categoryId) {
                $q->whereHas('categories', function ($catQ) use ($categoryId) {
                    $catQ->where('categories.id', $categoryId);
                });
            }
        }, 'assignedTasks.categories']);

        $users = $usersQuery->orderBy('name')->get();

        $allTasks = $users->flatMap(function ($u) {
            return $u->assignedTasks;
        })->unique('id');

        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'bajarildi')->count();
        $inProcessTasks = $allTasks->filter(function ($t) use ($today) {
            return in_array($t->status, ['yangi', 'bajarilmoqda']) && $t->end_date >= $today;
        })->count();
        $extendedTasks = $allTasks->where('status', 'uzaytirildi')->count();
        $notCompletedTasks = $allTasks->filter(function ($t) use ($today) {
            return $t->status == 'bajarilmadi' || (
                in_array($t->status, ['yangi', 'bajarilmoqda']) && $t->end_date < $today
            );
        })->count();

        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        $summary = [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'in_process' => $inProcessTasks,
            'extended' => $extendedTasks,
            'not_completed' => $notCompletedTasks,
            'completion_rate' => $completionRate,
            'active_users_count' => $users->count(),
        ];

        $xodimlarStats = $users->map(function ($user) use ($today) {
            $tasks = $user->assignedTasks->unique('id');
            $total = $tasks->count();
            $completed = $tasks->where('status', 'bajarildi')->count();
            $inProcess = $tasks->filter(function ($t) use ($today) {
                return in_array($t->status, ['yangi', 'bajarilmoqda']) && $t->end_date >= $today;
            })->count();
            $extended = $tasks->where('status', 'uzaytirildi')->count();
            $notCompleted = $tasks->filter(function ($t) use ($today) {
                return $t->status == 'bajarilmadi' || (
                    in_array($t->status, ['yangi', 'bajarilmoqda']) && $t->end_date < $today
                );
            })->count();

            $rate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;

            return [
                'user' => $user,
                'name' => $user->name,
                'full_name' => $user->full_name ?: $user->name,
                'position' => $user->position ?: 'Xodim',
                'total' => $total,
                'completed' => $completed,
                'in_process' => $inProcess,
                'extended' => $extended,
                'not_completed' => $notCompleted,
                'completion_rate' => $rate,
            ];
        });

        // Top 5 eng namunali xodimlar
        $topPerformers = $xodimlarStats->filter(function ($s) {
            return $s['total'] > 0;
        })->sortByDesc('completion_rate')->sortByDesc('completed')->values()->take(5);

        // Nazorat talab qiluvchi xodimlar
        $attentionUsers = $xodimlarStats->filter(function ($s) {
            return $s['not_completed'] > 0;
        })->sortByDesc('not_completed')->values()->take(5);

        // Kategoriyalar statistikasi (barcha toifalar va toifasiz topshiriqlar)
        $allCategoriesForTasks = Category::all();
        $categories = Category::forObjectType('tasks');
        if ($categories->isEmpty()) {
            $categories = $allCategoriesForTasks;
        }

        $categoryData = [];
        foreach ($allCategoriesForTasks as $cat) {
            $catTasksCount = $allTasks->filter(function ($task) use ($cat) {
                return $task->categories->contains('id', $cat->id);
            })->count();

            if ($catTasksCount > 0) {
                $categoryData[] = [
                    'name' => $cat->name,
                    'count' => $catTasksCount,
                ];
            }
        }

        $uncategorizedCount = $allTasks->filter(function ($task) {
            return $task->categories->isEmpty();
        })->count();

        if ($uncategorizedCount > 0) {
            $categoryData[] = [
                'name' => 'Бириктирилмаган',
                'count' => $uncategorizedCount,
            ];
        }

        if (empty($categoryData) && $totalTasks > 0) {
            $categoryData[] = [
                'name' => 'Умумий топшириқлар',
                'count' => $totalTasks,
            ];
        }

        // ----------------------------------------------------
        // И-индекс (Илмий мақолалар) статистикаси
        // ----------------------------------------------------
        $articlesQuery = Article::query();
        if ($period === 'today') {
            $articlesQuery->whereDate('created_at', Carbon::today());
        } elseif ($period === 'this_month') {
            $articlesQuery->whereYear('created_at', Carbon::now()->year)
                          ->whereMonth('created_at', Carbon::now()->month);
        } elseif ($period === 'this_year') {
            $articlesQuery->whereYear('created_at', Carbon::now()->year);
        } elseif ($startDate && $endDate) {
            $articlesQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $allArticles = $articlesQuery->with(['user', 'articleScore'])->get();
        $articlesTotal = $allArticles->count();
        $articlesChecked = $allArticles->where('status', 'checked')->count();
        $articlesPending = $allArticles->where('status', 'pending')->count();
        $articlesTotalScore = round($allArticles->sum(function ($a) {
            return $a->articleScore ? (float)$a->articleScore->total_score : 0;
        }), 2);
        $articlesAvgScore = $articlesChecked > 0 ? round($articlesTotalScore / $articlesChecked, 2) : 0;

        $topArticleAuthors = User::whereHas('articles')->with(['articles.articleScore'])->get()->map(function ($u) {
            $arts = $u->articles;
            $score = $arts->sum(function ($a) {
                return $a->articleScore ? (float)$a->articleScore->total_score : 0;
            });
            return [
                'name' => $u->name,
                'full_name' => $u->full_name ?: $u->name,
                'position' => $u->position ?: 'Ходим / Ўқитувчи',
                'articles_count' => $arts->count(),
                'checked_count' => $arts->where('status', 'checked')->count(),
                'pending_count' => $arts->where('status', 'pending')->count(),
                'total_score' => round($score, 2),
            ];
        })->sortByDesc('total_score')->values()->take(5);

        $articleStats = [
            'total' => $articlesTotal,
            'checked' => $articlesChecked,
            'pending' => $articlesPending,
            'total_score' => $articlesTotalScore,
            'avg_score' => $articlesAvgScore,
            'top_authors' => $topArticleAuthors,
        ];

        // ----------------------------------------------------
        // Лойиҳалар статистикаси
        // ----------------------------------------------------
        $allProjects = Project::with(['user', 'participants', 'pro_documents'])->latest()->get();
        $projectsTotal = $allProjects->count();
        $projectsParticipantsCount = Participant::count();
        $projectsDocumentsCount = Pro_document::count();

        $projectList = $allProjects->take(5)->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => trim(strip_tags($p->name)),
                'leader' => trim(strip_tags($p->pro_bos_name)) ?: ($p->user?->name ?: 'Кўрсатилмаган'),
                'job' => trim(strip_tags($p->job)),
                'participants_count' => $p->participants->count(),
                'docs_count' => $p->pro_documents->count(),
                'created_at' => $p->created_at ? $p->created_at->format('d.m.Y') : '',
            ];
        });

        $projectStats = [
            'total' => $projectsTotal,
            'participants_count' => $projectsParticipantsCount,
            'documents_count' => $projectsDocumentsCount,
            'list' => $projectList,
        ];

        // Oylik dinamika
        $uzMonthNames = [
            1 => 'Yan', 2 => 'Fev', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avg',
            9 => 'Sen', 10 => 'Okt', 11 => 'Noy', 12 => 'Dek'
        ];
        $monthlyTrends = [
            'months' => [],
            'total' => [],
            'completed' => []
        ];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $label = $uzMonthNames[$m->month] . ' ' . $m->format('Y');
            $yearMonth = $m->format('Y-m');

            $mTasks = $allTasks->filter(function ($task) use ($yearMonth) {
                return Carbon::parse($task->created_at)->format('Y-m') === $yearMonth;
            });

            $monthlyTrends['months'][] = $label;
            $monthlyTrends['total'][] = $mTasks->count();
            $monthlyTrends['completed'][] = $mTasks->where('status', 'bajarildi')->count();
        }

        $chartEmployees = [
            'names' => $xodimlarStats->pluck('name')->toArray(),
            'completed' => $xodimlarStats->pluck('completed')->toArray(),
            'in_process' => $xodimlarStats->pluck('in_process')->toArray(),
            'extended' => $xodimlarStats->pluck('extended')->toArray(),
            'not_completed' => $xodimlarStats->pluck('not_completed')->toArray(),
            'rates' => $xodimlarStats->pluck('completion_rate')->toArray(),
        ];

        return view('pages.statistika_diagramma', compact(
            'summary',
            'xodimlarStats',
            'topPerformers',
            'attentionUsers',
            'categories',
            'categoryData',
            'monthlyTrends',
            'chartEmployees',
            'articleStats',
            'projectStats',
            'period',
            'startDate',
            'endDate',
            'categoryId'
        ));
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
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('end_date', '>=', $request->start_date)
                  ->whereDate('end_date', '<=', $request->end_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('end_date', '=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        // 4. Status (Holat) bo'yicha filtr
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Natijalarni olish (xotira to'lib ketmasligi uchun eng so'nggi 50 ta)
        $tasks = $query->orderBy('end_date', 'desc')->limit(50)->get();

        $specialIds = [4, 83, 6, 70];
        $users = User::where('role', 'xodim')
            ->orderByRaw("FIELD(id, " . implode(',', $specialIds) . ") DESC")
            ->orderBy('name')
            ->get();

        $categories = Category::forObjectType('tasks');

        // Partial orqali HTML qaytarish
        $html = view('admin.project.partials.search_results', compact('tasks', 'now'))->render();
        $modals = view('components.admin.project.project-modal-edit', compact('tasks', 'users', 'categories'))->render();

        return response()->json([
            'html' => $html,
            'modals' => $modals
        ]);
    }




}
