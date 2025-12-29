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

        // Check if user has the specified role
        if ($user->hasRole($role)) {
            return $next($request);
        }

        // If user doesn't have the role, check if they have relevant permissions
        // This allows users with specific permissions to access routes without having the full role
        $rolePermissionMap = [
            'Admin' => [
                'manage users', 'manage roles', 'manage products', 'manage categories',
                'view all requests', 'approve requests', 'manage flights',
                'manage system settings', 'view activity logs', 'manage backups'
            ],
            'Inventory Personnel' => [
                'view products', 'create products', 'edit products', 'delete products',
                'manage stock movements', 'transfer stock', 'view stock levels'
            ],
            'Inventory Supervisor' => [
                'view products', 'approve stock movements', 'view all requests',
                'approve requests', 'manage stock movements', 'view stock levels'
            ],
            'Catering Incharge' => [
                'view all requests', 'final approve requests', 'approve requests',
                'view products', 'manage catering operations'
            ],
            'Catering Staff' => [
                'create catering request', 'view own requests', 'manage meals',
                'view products', 'view flights'
            ],
            'Ramp Dispatcher' => [
                'manage returns', 'view products', 'view flight schedule',
                'dispatch returns'
            ],
            'Flight Dispatcher' => [
                'dispatch flights', 'manage dispatches', 'manage messages',
                'view flight schedule', 'view products'
            ],
            'Security Staff' => [
                'create catering request', 'view own requests', 'manage security requests',
                'view products'
            ],
            'Cabin Crew' => [
                'view flight schedule', 'submit served form', 'view products',
                'view assigned flights'
            ],
            'Flight Purser' => [
                'view flight schedule', 'manage cabin crew', 'view products',
                'view assigned flights'
            ],
        ];

        // Check if user has any of the permissions associated with this role
        if (isset($rolePermissionMap[$role])) {
            foreach ($rolePermissionMap[$role] as $permission) {
                if ($user->can($permission)) {
                    return $next($request);
                }
            }
        }

        // If user has neither the role nor any relevant permissions, deny access
        abort(403, 'Unauthorized - You do not have permission to access this resource');
    }
}
