<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "════════════════════════════════════════════════════════════════\n";
echo "🔍 CHECKING WHICH PERMISSIONS ARE NOT DYNAMIC\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Get all roles
$roles = Role::with('permissions')->get();

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 ALL PERMISSIONS IN SYSTEM\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$allPermissions = Permission::orderBy('name')->get();
foreach ($allPermissions as $perm) {
    $rolesWithThisPermission = $perm->roles->pluck('name')->toArray();
    $count = count($rolesWithThisPermission);
    
    echo "• {$perm->name}\n";
    echo "  Assigned to {$count} role(s): " . implode(', ', $rolesWithThisPermission) . "\n\n";
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎯 TESTING ACTUAL PERMISSION BEHAVIOR\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Test specific scenarios
$testPermissions = [
    'view products',
    'create products',
    'view activity logs',
    'manage users',
    'view reports',
];

foreach ($roles as $role) {
    echo "▼ {$role->name}:\n";
    
    $user = User::role($role->name)->first();
    
    if (!$user) {
        echo "  ⚠️  No user with this role (can't test)\n\n";
        continue;
    }
    
    $hasAnyPermission = false;
    
    foreach ($testPermissions as $permission) {
        $can = $user->can($permission);
        if ($can) {
            echo "  ✅ Can: {$permission}\n";
            $hasAnyPermission = true;
        }
    }
    
    if (!$hasAnyPermission) {
        echo "  ⭕ No permissions from test list\n";
    }
    
    echo "\n";
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔍 CHECKING DASHBOARD ACTIONS CONFIG\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$dashboardActionsFile = __DIR__ . '/config/dashboard-actions.php';
if (file_exists($dashboardActionsFile)) {
    $dashboardActions = include $dashboardActionsFile;
    
    echo "Permissions configured in dashboard-actions.php:\n\n";
    
    foreach ($dashboardActions as $permissionName => $config) {
        $title = $config['title'] ?? 'N/A';
        $route = $config['route'] ?? 'N/A';
        $isDynamic = isset($config['dynamic_route']) && $config['dynamic_route'] ? '🔄 Dynamic' : '🔒 Static';
        
        echo "• {$permissionName}\n";
        echo "  Title: {$title}\n";
        echo "  Route: {$route} {$isDynamic}\n";
        
        // Check how many roles have this permission
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $rolesCount = $permission->roles->count();
            $rolesNames = $permission->roles->pluck('name')->toArray();
            echo "  Assigned to: {$rolesCount} role(s) - " . implode(', ', $rolesNames) . "\n";
        } else {
            echo "  ⚠️  Permission doesn't exist in database!\n";
        }
        
        echo "\n";
    }
} else {
    echo "❌ dashboard-actions.php not found!\n\n";
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "⚠️  POTENTIAL ISSUES DETECTED\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$issues = [];

// Check if dashboard-actions.php exists
if (!file_exists($dashboardActionsFile)) {
    $issues[] = "dashboard-actions.php config file missing";
}

// Check if permission-actions component exists
$componentFile = __DIR__ . '/resources/views/components/permission-actions.blade.php';
if (!file_exists($componentFile)) {
    $issues[] = "permission-actions.blade.php component missing";
}

// Check which dashboards are using the component
$dashboardFiles = [
    'admin/dashboard.blade.php',
    'cabin-crew/dashboard.blade.php',
    'catering-staff/dashboard.blade.php',
    'inventory-personnel/dashboard.blade.php',
    'inventory-supervisor/dashboard.blade.php',
    'catering-incharge/dashboard.blade.php',
    'security-staff/dashboard.blade.php',
    'ramp-dispatcher/dashboard.blade.php',
    'flight-purser/dashboard.blade.php',
];

foreach ($dashboardFiles as $dashboardFile) {
    $fullPath = __DIR__ . '/resources/views/' . $dashboardFile;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        if (strpos($content, '<x-permission-actions') === false) {
            $issues[] = "{$dashboardFile} - NOT using <x-permission-actions> component";
        }
    } else {
        $issues[] = "{$dashboardFile} - File doesn't exist";
    }
}

if (count($issues) > 0) {
    echo "Found " . count($issues) . " issue(s):\n\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". {$issue}\n";
    }
} else {
    echo "✅ No structural issues found!\n";
    echo "\nIf permissions still not working, the issue might be:\n";
    echo "1. User needs to log out and log back in\n";
    echo "2. Browser cache needs to be cleared\n";
    echo "3. Specific permission not assigned to the role\n";
}

echo "\n\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "Please share exactly:\n";
echo "1. Which ROLE is having the problem?\n";
echo "2. Which PERMISSION is not working?\n";
echo "3. What happens when you add the permission?\n";
echo "════════════════════════════════════════════════════════════════\n";
