<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTaskStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $today = Carbon::today();
        $threeDaysLater = $today->copy()->addDays(3);

        // 1. 3 kundan kam qolgan va hali "yangi" bo‘lgan topshiriqlar -> "bajarilmoqda"
        $tasksToUpdate = Task::where('status', 'yangi')
            ->whereDate('end_date', '<=', $threeDaysLater)
            ->get();

        if ($tasksToUpdate->isNotEmpty()) {
            Task::whereIn('id', $tasksToUpdate->pluck('id'))
                ->update(['status' => 'bajarilmoqda']);
        }

        // 2. Muddatidan o'tib ketgan va hali bajarilmagan topshiriqlar -> "bajarilmadi"
        $today = Carbon::today();

        $expiredTasks = Task::whereNotIn('status', ['bajarildi', 'bajarilmadi'])
            ->whereDate('end_date', '<', $today)
            ->get();

        if ($expiredTasks->isNotEmpty()) {
            Task::whereIn('id', $expiredTasks->pluck('id'))
                ->update(['status' => 'bajarilmadi']);
        }


        return $next($request);
    }

}
