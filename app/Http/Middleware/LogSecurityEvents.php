<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogSecurityEvents
{
    /**
     * Handle an incoming request and log security-sensitive actions.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log all state-changing requests (POST, PUT, DELETE, PATCH)
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            
            // Get input but exclude sensitive fields
            $input = $request->except([
                'password',
                'password_confirmation',
                'current_password',
                'new_password',
                '_token',
                '_method',
            ]);

            // Log the action
            activity('security')
                ->causedBy(auth()->user())
                ->withProperties([
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'route' => $request->route()?->getName(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'input' => $input,
                ])
                ->log('Sensitive action: ' . $request->method() . ' ' . $request->path());
        }

        $response = $next($request);

        // Log failed authorization attempts (403)
        if ($response->status() === 403) {
            activity('security')
                ->causedBy(auth()->user())
                ->withProperties([
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'route' => $request->route()?->getName(),
                ])
                ->log('Unauthorized access attempt - 403 Forbidden');
        }

        return $response;
    }
}
