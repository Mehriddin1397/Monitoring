<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->last_activity || abs(now()->diffInSeconds(\Carbon\Carbon::parse($user->last_activity))) >= 60) {
                $user->updateLastActivity();
            }

            $last = session('last_activity');

            if ($last && now()->diffInMinutes($last) >= 1) {
                session(['authenticated' => false]);
                return redirect()->route('auth.code')->withErrors(['auth_code' => 'Faol bo‘lmaganingiz uchun qaytadan kod kiriting']);
            }

            // Agar 60 minutdan kam bo‘lsa, yangilaymiz
            session(['last_activity' => now()]);
        }

        return $next($request);
    }

}
