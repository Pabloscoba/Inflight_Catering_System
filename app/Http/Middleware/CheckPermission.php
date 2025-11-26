<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Activitylog\Facades\Activity;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            Activity::log('Unauthorized access attempt');
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if (auth()->user()->can($permission)) {
                // Log successful access
                activity()
                    ->causedBy(auth()->user())
                    ->log("Accessed resource with permission: {$permission}");
                
                return $next($request);
            }
        }

        // Log unauthorized access
        activity()
            ->causedBy(auth()->user())
            ->log('Attempted to access resource without permission: ' . implode(', ', $permissions));

        abort(403, 'You do not have permission to access this resource.');
    }
}
