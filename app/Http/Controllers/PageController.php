<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Foydalanuvchini topamiz
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Bunday foydalanuvchi mavjud emas.']);
        }

        // Parolni tekshiramiz
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Noto‘g‘ri parol!']);
        }

        // Login qilish
        if (Auth::attempt($request->only('email', 'password'))) {

//            $projects = Project::with('participants')->get();
            return view('pages.auth_code');
        }

        return back()->withErrors(['email' => 'Login amalga oshmadi, iltimos tekshirib qaytadan urinib ko‘ring.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function dashboard()
    {
        $month = now()->format('Y-m');

        $tasks = Task::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get();

        $total = $tasks->count();
        $completed = $tasks->where('status', 'bajarildi')->count();
        $incomplete = $total - $completed;

        // Har bir xodim va unga tegishli topshiriqlarni olish
        $tasksWithUsers = User::where('role', 'xodim')
            ->with(['assignedTasks' => function ($query) {
                $query->select('tasks.id', 'title', 'status'); // ustunlarni aniqlab beramiz
            }])
            ->get();


        return view('admin.dashboard',  compact('tasksWithUsers', 'total', 'completed', 'incomplete', 'month'));
    }

    public function showFile($id, $type)
    {
        $project = Task::findOrFail($id);

        if ($type === 'buyruq') {
            $filePath = $project->document;

        } else {
            abort(404, 'Fayl topilmadi');
        }



        return view('admin.project.pdf', compact('project', 'filePath'));
    }



    public function hisobot(Request $request)
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $allCategories = Category::where('object_type', 'tasks')->get()->keyBy('id');

        if (!$from || !$to) {
            return view('pages.hisobot')->with([
                'userStats' => [],
                'allCategories' => $allCategories,
                'globalSummary' => [],
            ]);
        }

        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();
        $today = Carbon::today();

        $tasks = Task::with(['statuses', 'categories', 'assignedUsers'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $users = User::with('assignedTasks')->get();

        $userStats = [];

        foreach ($users as $user) {
            $userTasks = $tasks->filter(function ($task) use ($user) {
                return $task->assignedUsers->contains('id', $user->id);
            });

            if ($userTasks->isEmpty()) {
                continue;
            }

            $summary = [
                'full_name' => $user->name,
                'total' => 0,
                'completed' => 0,
                'extended' => 0,
                'extended_then_completed' => 0,
                'in_process' => 0,
                'not_completed' => 0,
                'categories' => [],
            ];

            foreach ($allCategories as $catId => $cat) {
                $summary['categories'][$catId] = 0;
            }

            foreach ($userTasks as $task) {
                $summary['total']++;

                $statuses = $task->statuses->sortBy('created_at')->pluck('status')->values()->all();

                $seenExtended = false;
                $isExtendedThenCompleted = false;

                foreach ($statuses as $status) {
                    if ($status === 'uzaytirildi') {
                        $seenExtended = true;
                    }
                    if ($seenExtended && $status === 'bajarildi') {
                        $isExtendedThenCompleted = true;
                        break;
                    }
                }

                if ($isExtendedThenCompleted) {
                    $summary['extended_then_completed']++;
                }

                if (in_array($task->status, ['yangi', 'bajarilmoqda']) && $task->end_date >= $today) {
                    $summary['in_process']++;
                }

                if ($task->status === 'uzaytirildi') {
                    $summary['extended']++;
                }

                if ($task->status === 'bajarildi') {
                    $summary['completed']++;
                }

                if ($task->status === 'bajarilmadi') {
                    $summary['not_completed']++;
                }

                foreach ($task->categories as $cat) {
                    if (isset($summary['categories'][$cat->id])) {
                        $summary['categories'][$cat->id]++;
                    }
                }
            }

            $userStats[] = $summary;
        }

        // 🔁 GLOBAL STATISTIKA (BARCHA TASKLAR BO‘YICHA, UNIKAL)
        $globalSummary = [
            'total' => 0,
            'completed' => 0,
            'extended' => 0,
            'extended_then_completed' => 0,
            'in_process' => 0,
            'not_completed' => 0,
            'categories' => [],
        ];

        foreach ($allCategories as $catId => $cat) {
            $globalSummary['categories'][$catId] = 0;
        }

        foreach ($tasks as $task) {
            $globalSummary['total']++;

            $statuses = $task->statuses->sortBy('created_at')->pluck('status')->values()->all();

            $seenExtended = false;
            $isExtendedThenCompleted = false;

            foreach ($statuses as $status) {
                if ($status === 'uzaytirildi') {
                    $seenExtended = true;
                }
                if ($seenExtended && $status === 'bajarildi') {
                    $isExtendedThenCompleted = true;
                    break;
                }
            }

            if ($isExtendedThenCompleted) {
                $globalSummary['extended_then_completed']++;
            }

            if (in_array($task->status, ['yangi', 'bajarilmoqda']) && $task->end_date >= $today) {
                $globalSummary['in_process']++;
            }

            if ($task->status === 'uzaytirildi') {
                $globalSummary['extended']++;
            }

            if ($task->status === 'bajarildi') {
                $globalSummary['completed']++;
            }

            if ($task->status === 'bajarilmadi') {
                $globalSummary['not_completed']++;
            }

            foreach ($task->categories as $cat) {
                if (isset($globalSummary['categories'][$cat->id])) {
                    $globalSummary['categories'][$cat->id]++;
                }
            }
        }

        return view('pages.hisobot', compact('userStats', 'allCategories', 'globalSummary'));
    }

    public function showFilee($id, $type)
    {
        $project = Project::findOrFail($id);

        if ($type === 'buyruq') {
            $filePath = $project->file_buyruq;
        } elseif ($type === 'qushimcha') {
            $filePath =  $project->file_qushimcha;
        } else {
            abort(404, 'Fayl topilmadi');
        }



        return view('admin.projectt.pdf', compact('project', 'filePath'));
    }



}
