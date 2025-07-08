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
        // Bugungi sanadan 3 kun keyingi sana
        $threeDaysLater = Carbon::today()->addDays(3);

        // 3 kundan kam qolgan va hali "yangi" bo‘lgan topshiriqlarni olish
        $tasksToUpdate = Task::where('status', 'yangi')
            ->whereDate('end_date', '<', $threeDaysLater)
            ->get();

        // Agar mavjud bo‘lsa, ularning statusini o‘zgartirish
        if ($tasksToUpdate->isNotEmpty()) {
            Task::whereIn('id', $tasksToUpdate->pluck('id'))
                ->update(['status' => 'bajarilmoqda']);
        }

        return $next($request);
    }
}
