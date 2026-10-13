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
        // Har bir so'rovda butun jadvalni skaner qilmaslik uchun 15 daqiqada faqat 1 marta bajariladi
        if (!\Illuminate\Support\Facades\Cache::has('task_status_checked_recently')) {
            \Illuminate\Support\Facades\Cache::put('task_status_checked_recently', true, now()->addMinutes(15));

            $today = Carbon::today();
            $threeDaysLater = $today->copy()->addDays(3);

            // 1. 3 kundan kam qolgan va hali "yangi" bo‘lgan topshiriqlar -> "bajarilmoqda"
            Task::where('status', 'yangi')
                ->where('end_date', '<=', $threeDaysLater)
                ->update(['status' => 'bajarilmoqda']);

            // 2. Muddatidan o'tib ketgan va hali bajarilmagan topshiriqlar -> "bajarilmadi"
            Task::whereNotIn('status', ['bajarildi', 'bajarilmadi'])
                ->where('end_date', '<', $today)
                ->update(['status' => 'bajarilmadi']);
        }

        return $next($request);
    }

}
