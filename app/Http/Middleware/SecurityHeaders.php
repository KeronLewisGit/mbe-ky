<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Browser hardening headers on every response. A Content-Security-Policy is
 * deliberately left out: Alpine evaluates inline expressions, which would need
 * 'unsafe-eval' and 'unsafe-inline' and make the policy meaningless.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // PHP adds this at the SAPI level, before Laravel's response headers exist.
        header_remove('X-Powered-By');

        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
