<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CctvAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'kadr', 'boshliq'])) {
            abort(403, 'Ushbu bo‘lim faqat administrator, kadrlar bo‘limi va rahbar (boshliq) uchun mo‘ljallangan.');
        }

        return $next($request);
    }
}
