<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictLoginByIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $forwarded_ip = $request->header('X-Forwarded-For');
        $real_ip = $forwarded_ip ? explode(',', $forwarded_ip)[0] : $request->ip();

        $allowed_ips = ['213.230.99.98', ]; // sizga kerakli IPlar

        if (!in_array($real_ip, $allowed_ips)) {
            abort(403, 'Sizning IP manzilingiz (' . $real_ip . ') orqali kirish taqiqlangan.');
        }

        return $next($request);
    }

}
