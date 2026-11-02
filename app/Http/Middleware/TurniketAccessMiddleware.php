<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TurniketAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'kadr', 'boshliq'])) {
            abort(403, 'Ушбу бўлим фақат администратор, кадрлар бўлими ходими ва раҳбар (бошлиқ) учун мўлжалланган.');
        }

        // Бошлиқ роли фақат маълумотларни кўришга (Read-only) рухсат этилган
        if (Auth::user()->role === 'boshliq' && !$request->isMethodSafe()) {
            abort(403, 'Бошлиқ роли учун маълумотларни ўзгартириш чекланган. Фақат кўриш мумкин.');
        }

        return $next($request);
    }
}
