<?php

namespace App\Http\Controllers;

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

        if (!$from || !$to) {
            return view('pages.hisobot')->with('summary', null);
        }

        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();

        $tasks = Task::with('statuses')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get()
            ->unique('id');

        $today = Carbon::today();

        $summary = [
            'total' => 0,
            'in_process' => 0,
            'extended' => 0,
            'completed' => 0,
            'not_completed' => 0,
            'extended_then_completed' => 0,
        ];

        foreach ($tasks as $task) {
            $summary['total']++;

            // Statuslar ketma-ketligini tekshirish
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
                continue;
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

            if ($task->status !== 'bajarildi' && $task->end_date < $today) {
                $summary['not_completed']++;
            }
        }

        return view('pages.hisobot', compact('summary'));
    }





}
