<?php

/**
 * Test Notification Buttons for All Roles
 * 
 * This script checks if notification action_url routes are accessible
 * for each user role without throwing "user does not have a role" errors.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

echo "\n" . str_repeat("=", 80) . "\n";
echo "TESTING NOTIFICATION BUTTONS FOR ALL ROLES\n";
echo str_repeat("=", 80) . "\n\n";

// Get all notification files
$notificationPath = app_path('Notifications');
$notificationFiles = glob($notificationPath . '/*Notification.php');

echo "Found " . count($notificationFiles) . " notification classes\n\n";

// Get all roles
$roles = DB::table('roles')->get();
echo "Testing for " . count($roles) . " roles:\n";
foreach ($roles as $role) {
    echo "  - {$role->name}\n";
}
echo "\n";

// Get a sample user for each role
$usersByRole = [];
foreach ($roles as $role) {
    $user = DB::table('users')
        ->join('model_has_roles', function ($join) use ($role) {
            $join->on('users.id', '=', 'model_has_roles.model_id')
                ->where('model_has_roles.role_id', '=', $role->id)
                ->where('model_has_roles.model_type', '=', 'App\Models\User');
        })
        ->select('users.*')
        ->first();
    
    if ($user) {
        $userModel = User::find($user->id);
        $usersByRole[$role->name] = $userModel;
        echo "✓ Found user '{$userModel->name}' for role '{$role->name}'\n";
    } else {
        echo "✗ No user found for role '{$role->name}'\n";
    }
}
echo "\n";

// Test each notification class
$issues = [];
foreach ($notificationFiles as $file) {
    $className = basename($file, '.php');
    $fullClassName = "App\\Notifications\\{$className}";
    
    if (!class_exists($fullClassName)) {
        continue;
    }
    
    echo "\n" . str_repeat("-", 80) . "\n";
    echo "Testing: {$className}\n";
    echo str_repeat("-", 80) . "\n";
    
    // Try to create a sample notification
    try {
        $notification = null;
        
        // Create test notification based on class constructor requirements
        if (strpos($className, 'Product') !== false) {
            $product = DB::table('products')->first();
            if ($product) {
                $productModel = \App\Models\Product::find($product->id);
                $reflection = new ReflectionClass($fullClassName);
                $constructor = $reflection->getConstructor();
                
                if ($constructor && count($constructor->getParameters()) > 0) {
                    $notification = new $fullClassName($productModel);
                }
            }
        } elseif (strpos($className, 'Request') !== false || strpos($className, 'Flight') !== false) {
            $request = RequestModel::with('flight')->first();
            if ($request) {
                $reflection = new ReflectionClass($fullClassName);
                $constructor = $reflection->getConstructor();
                
                if ($constructor && count($constructor->getParameters()) > 0) {
                    $notification = new $fullClassName($request);
                }
            }
        } elseif (strpos($className, 'StockMovement') !== false) {
            $movement = DB::table('stock_movements')->first();
            if ($movement) {
                $movementModel = \App\Models\StockMovement::find($movement->id);
                $reflection = new ReflectionClass($fullClassName);
                $constructor = $reflection->getConstructor();
                
                if ($constructor && count($constructor->getParameters()) > 0) {
                    $notification = new $fullClassName($movementModel);
                }
            }
        }
        
        if (!$notification) {
            echo "  ⚠ Could not create test notification instance\n";
            continue;
        }
        
        // Test notification for each role
        foreach ($usersByRole as $roleName => $user) {
            echo "\n  Testing for role: {$roleName}\n";
            
            try {
                // Get notification data
                $data = $notification->toArray($user);
                $actionUrl = $data['action_url'] ?? '#';
                
                echo "    Action URL: {$actionUrl}\n";
                
                if ($actionUrl === '#') {
                    echo "    ⚠ Warning: No action URL defined\n";
                    continue;
                }
                
                // Parse the route name from URL
                $routeName = null;
                foreach (Route::getRoutes() as $route) {
                    if ($route->getName() && str_contains($actionUrl, $route->getName())) {
                        $routeName = $route->getName();
                        break;
                    }
                }
                
                // Check if route exists
                if ($routeName && Route::has($routeName)) {
                    $route = Route::getRoutes()->getByName($routeName);
                    $middleware = $route->middleware();
                    
                    echo "    Route Name: {$routeName}\n";
                    echo "    Middleware: " . implode(', ', $middleware) . "\n";
                    
                    // Check if user has access based on middleware
                    $hasCheckRoleMiddleware = false;
                    $requiredRole = null;
                    
                    foreach ($middleware as $mw) {
                        if (strpos($mw, 'check_role_or_permission:') === 0) {
                            $hasCheckRoleMiddleware = true;
                            $requiredRole = str_replace('check_role_or_permission:', '', $mw);
                            break;
                        }
                    }
                    
                    if ($hasCheckRoleMiddleware) {
                        // Check if user has the required role
                        if ($user->hasRole($requiredRole)) {
                            echo "    ✓ User has required role '{$requiredRole}'\n";
                        } else {
                            // Check if user has relevant permissions
                            $rolePermissionMap = config('role_permission_map', []);
                            $hasPermission = false;
                            
                            if (isset($rolePermissionMap[$requiredRole])) {
                                foreach ($rolePermissionMap[$requiredRole] as $permission) {
                                    if ($user->can($permission)) {
                                        $hasPermission = true;
                                        echo "    ✓ User has permission '{$permission}' for this route\n";
                                        break;
                                    }
                                }
                            }
                            
                            if (!$hasPermission) {
                                echo "    ✗ ERROR: User does NOT have role '{$requiredRole}' or permissions!\n";
                                $issues[] = [
                                    'notification' => $className,
                                    'role' => $roleName,
                                    'url' => $actionUrl,
                                    'route' => $routeName,
                                    'required_role' => $requiredRole,
                                ];
                            }
                        }
                    } else {
                        echo "    ✓ Route has no role restrictions\n";
                    }
                } else {
                    echo "    ⚠ Warning: Could not find route\n";
                }
                
            } catch (\Exception $e) {
                echo "    ✗ Error testing notification: {$e->getMessage()}\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "  ✗ Error creating notification: {$e->getMessage()}\n";
    }
}

// Summary of issues
echo "\n\n" . str_repeat("=", 80) . "\n";
echo "SUMMARY OF ISSUES\n";
echo str_repeat("=", 80) . "\n\n";

if (empty($issues)) {
    echo "✓ No access issues found! All notification buttons should work correctly.\n";
} else {
    echo "Found " . count($issues) . " potential access issues:\n\n";
    
    foreach ($issues as $issue) {
        echo "  • Notification: {$issue['notification']}\n";
        echo "    Role: {$issue['role']}\n";
        echo "    Route: {$issue['route']}\n";
        echo "    Required Role: {$issue['required_role']}\n";
        echo "    URL: {$issue['url']}\n";
        echo "\n";
    }
    
    echo "\nRECOMMENDATIONS:\n";
    echo "1. Update notification classes to check user role before setting action_url\n";
    echo "2. Set action_url to '#' or user's dashboard if they don't have access\n";
    echo "3. Consider adding permission checks in notification toArray() methods\n";
}

echo "\n" . str_repeat("=", 80) . "\n\n";
