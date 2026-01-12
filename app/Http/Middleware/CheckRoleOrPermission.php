<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrPermission
{
    /**
     * Handle an incoming request.
     * 
     * This middleware checks if the user has the specified role OR has any permissions.
     * This allows permission-based access control while maintaining role-based routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized - Please login');
        }

        $user = auth()->user();

        // Allow common aliases for role names (so route params like 'flightops' still work)
        $param = trim($role);
        $lower = strtolower($param);
        $aliasMap = [
            'flightops' => 'Flight Operations Manager',
            'flight ops' => 'Flight Operations Manager',
            'flight-operations-manager' => 'Flight Operations Manager',
            'flight-ops' => 'Flight Operations Manager',
        ];

        // If user has the specified role directly or via alias mapping, allow
        if ($user->hasRole($param) || (isset($aliasMap[$lower]) && $user->hasRole($aliasMap[$lower]))) {
            return $next($request);
        }

        // Determine which key to use when checking the permission map
        $mapKey = $param;
        if (isset($aliasMap[$lower])) {
            $mapKey = $aliasMap[$lower];
        }

        // Load role→permissions mapping from config so it's editable without code changes
        $rolePermissionMap = config('role_permission_map', []);

        // Check if user has any of the permissions associated with this role (use the map key)
        if (isset($rolePermissionMap[$mapKey]) && is_array($rolePermissionMap[$mapKey])) {
            foreach ($rolePermissionMap[$mapKey] as $permission) {
                if ($user->can($permission)) {
                    return $next($request);
                }
            }
        }

        // If user has neither the role nor any relevant permissions, deny access
        abort(403, 'Unauthorized - You do not have permission to access this resource');
    }
}
