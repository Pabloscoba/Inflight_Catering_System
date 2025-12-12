<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSession
{
    /**
     * Handle an incoming request.
     * Prevent session hijacking by validating IP address and user agent.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if IP changed (possible session hijacking)
            if (session('user_ip') && session('user_ip') !== $request->ip()) {
                // Log suspicious activity
                activity('security')
                    ->causedBy($user)
                    ->withProperties([
                        'old_ip' => session('user_ip'),
                        'new_ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'session_id' => session()->getId(),
                    ])
                    ->log('Suspicious IP change detected - session terminated for security');
                
                // Terminate session
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('error', 'Your session has been terminated for security reasons. Please login again.');
            }
            
            // Store current IP for future validation
            session(['user_ip' => $request->ip()]);
            
            // Check if user agent changed (less critical but still suspicious)
            if (session('user_agent') && session('user_agent') !== $request->userAgent()) {
                activity('security')
                    ->causedBy($user)
                    ->withProperties([
                        'old_agent' => session('user_agent'),
                        'new_agent' => $request->userAgent(),
                        'ip' => $request->ip(),
                    ])
                    ->log('User agent change detected - possible browser switching');
            }
            
            // Store user agent
            session(['user_agent' => $request->userAgent()]);
            
            // Regenerate session ID periodically (every 30 minutes)
            if (!session()->has('last_regenerated') || 
                session('last_regenerated') < now()->subMinutes(30)->timestamp) {
                $request->session()->regenerate();
                session(['last_regenerated' => now()->timestamp]);
            }
        }

        return $next($request);
    }
}
