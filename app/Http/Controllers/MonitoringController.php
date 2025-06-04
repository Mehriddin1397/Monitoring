<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index() {
        $month = now()->format('Y-m');

        $tasks = Task::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get();

        $total = $tasks->count();
        $completed = $tasks->where('status', 'bajarildi')->count();
        $incomplete = $total - $completed;

        $userStats = User::where('role', 'xodim')->withCount([
            'assignedTasks as completed_tasks_count' => function ($q) {
                $q->where('status', 'bajarildi');
            }
        ])->get();

        return view('monitoring.index', compact('total', 'completed', 'incomplete', 'userStats'));
    }
}
