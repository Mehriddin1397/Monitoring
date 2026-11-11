<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request, attach recommended security headers and harden cookies.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Clickjacking prevention
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Cross-Site Scripting protection for legacy browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=()');

        $isHttps = $request->isSecure()
            || strtolower((string) $request->header('X-Forwarded-Proto')) === 'https'
            || env('SESSION_SECURE_COOKIE', false) === true;

        // If running over HTTPS, enforce HSTS
        if ($isHttps) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Cookie hardening: enforce Secure flag, SameSite and HttpOnly
        if (method_exists($response->headers, 'getCookies')) {
            $sessionCookieName = config('session.cookie');
            $defaultSameSite = config('session.same_site', 'lax') ?: 'lax';

            foreach ($response->headers->getCookies() as $cookie) {
                $updated = $cookie;

                // 1. Secure flag: enforce if on HTTPS or if secure session is enabled
                if ($isHttps && !$updated->isSecure()) {
                    $updated = $updated->withSecure(true);
                }

                // 2. SameSite: guarantee it has SameSite attribute (Lax/Strict)
                if (!$updated->getSameSite()) {
                    $updated = $updated->withSameSite($defaultSameSite);
                }

                // 3. HttpOnly: ensure session cookie is HttpOnly
                if ($updated->getName() === $sessionCookieName && !$updated->isHttpOnly()) {
                    $updated = $updated->withHttpOnly(true);
                }

                if ($updated !== $cookie) {
                    $response->headers->setCookie($updated);
                }
            }
        }

        return $response;
    }
}
