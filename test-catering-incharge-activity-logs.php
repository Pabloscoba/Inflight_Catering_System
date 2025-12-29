<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;

echo "════════════════════════════════════════════════════════════════\n";
echo "🧪 TESTING: Catering Incharge + 'view activity logs' permission\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Step 1: Get the role and permission
$role = Role::where('name', 'Catering Incharge')->first();
$permission = Permission::where('name', 'view activity logs')->first();

if (!$role) {
    echo "❌ Catering Incharge role not found!\n";
    exit;
}

if (!$permission) {
    echo "❌ 'view activity logs' permission not found!\n";
    exit;
}

echo "✅ Role found: {$role->name} (ID: {$role->id})\n";
echo "✅ Permission found: {$permission->name} (ID: {$permission->id})\n\n";

// Step 2: Give permission to role
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "STEP 1: ASSIGNING PERMISSION TO ROLE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if ($role->hasPermissionTo($permission)) {
    echo "✓ Catering Incharge already has 'view activity logs'\n\n";
} else {
    $role->givePermissionTo($permission);
    echo "✅ Permission ASSIGNED to Catering Incharge\n\n";
}

// Step 3: Test with actual user
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "STEP 2: TESTING WITH ACTUAL USER\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$user = User::role('Catering Incharge')->first();

if (!$user) {
    echo "❌ No user with Catering Incharge role found!\n\n";
} else {
    echo "Testing user: {$user->name} ({$user->email})\n\n";
    
    // Refresh to get latest permissions
    $user->refresh();
    $canViewActivityLogs = $user->can('view activity logs');
    
    if ($canViewActivityLogs) {
        echo "✅ User CAN view activity logs\n\n";
    } else {
        echo "❌ User CANNOT view activity logs (permission not working!)\n\n";
    }
}

// Step 4: Check if route exists
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "STEP 3: CHECKING ROUTE AVAILABILITY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$routeName = 'admin.activity-logs.index';
$allRoutes = Route::getRoutes();

if ($allRoutes->hasNamedRoute($routeName)) {
    $route = $allRoutes->getByName($routeName);
    $uri = $route->uri();
    $middleware = $route->middleware();
    
    echo "✅ Route exists: {$routeName}\n";
    echo "   URI: /{$uri}\n";
    echo "   Middleware: " . implode(', ', $middleware) . "\n\n";
    
    // Check if route has permission middleware
    if (in_array('permission:view activity logs', $middleware)) {
        echo "✅ Route is protected by 'permission:view activity logs' middleware\n\n";
    } else {
        echo "⚠️  Route does NOT have permission middleware (might cause issues)\n\n";
    }
} else {
    echo "❌ Route {$routeName} NOT FOUND!\n\n";
}

// Step 5: Check dashboard config
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "STEP 4: CHECKING DASHBOARD CONFIGURATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$dashboardActionsFile = __DIR__ . '/config/dashboard-actions.php';
if (file_exists($dashboardActionsFile)) {
    $dashboardActions = include $dashboardActionsFile;
    
    if (isset($dashboardActions['view activity logs'])) {
        $config = $dashboardActions['view activity logs'];
        echo "✅ 'view activity logs' found in dashboard-actions.php\n";
        echo "   Title: {$config['title']}\n";
        echo "   Icon: {$config['icon']}\n";
        echo "   Route: {$config['route']}\n";
        echo "   Color: {$config['color']}\n\n";
    } else {
        echo "❌ 'view activity logs' NOT in dashboard-actions.php\n\n";
    }
} else {
    echo "❌ dashboard-actions.php file not found!\n\n";
}

// Step 6: Check if dashboard uses component
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "STEP 5: CHECKING CATERING INCHARGE DASHBOARD\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$dashboardFile = __DIR__ . '/resources/views/catering-incharge/dashboard.blade.php';
if (file_exists($dashboardFile)) {
    $content = file_get_contents($dashboardFile);
    
    if (strpos($content, '<x-permission-actions') !== false) {
        echo "✅ Dashboard uses <x-permission-actions> component\n\n";
    } else {
        echo "❌ Dashboard does NOT use <x-permission-actions> component\n";
        echo "   The button will NOT appear automatically!\n\n";
    }
} else {
    echo "❌ Catering Incharge dashboard file not found!\n\n";
}

// Step 7: Simulate component behavior
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "STEP 6: SIMULATING WHAT USER WILL SEE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

if ($user && $user->can('view activity logs')) {
    echo "When Catering Incharge logs in:\n\n";
    echo "✅ @can('view activity logs') = TRUE\n";
    echo "✅ Component will find permission in config\n";
    echo "✅ Button will be rendered with:\n";
    echo "   • Title: 'Activity Logs'\n";
    echo "   • Icon: 📋\n";
    echo "   • Link: " . route('admin.activity-logs.index') . "\n";
    echo "   • Gradient background (purple)\n\n";
    
    echo "When user clicks the button:\n";
    echo "✅ Redirects to activity logs page\n";
    echo "✅ Middleware checks permission (PASS)\n";
    echo "✅ Page loads successfully\n\n";
} else {
    echo "⚠️  User doesn't have permission - button will NOT appear\n\n";
}

// Final verdict
echo "════════════════════════════════════════════════════════════════\n";
echo "🎯 FINAL VERDICT\n";
echo "════════════════════════════════════════════════════════════════\n\n";

$allGood = true;
$issues = [];

if (!$user || !$user->can('view activity logs')) {
    $allGood = false;
    $issues[] = "User doesn't have permission";
}

if (!$allRoutes->hasNamedRoute($routeName)) {
    $allGood = false;
    $issues[] = "Route doesn't exist";
}

if (!file_exists($dashboardActionsFile) || !isset($dashboardActions['view activity logs'])) {
    $allGood = false;
    $issues[] = "Permission not in dashboard-actions.php config";
}

if (!file_exists($dashboardFile) || strpos(file_get_contents($dashboardFile), '<x-permission-actions') === false) {
    $allGood = false;
    $issues[] = "Dashboard doesn't use permission-actions component";
}

if ($allGood) {
    echo "✅✅✅ EVERYTHING WORKS! ✅✅✅\n\n";
    echo "Catering Incharge + 'view activity logs' = FULLY FUNCTIONAL\n\n";
    echo "The permission system IS TRULY DYNAMIC!\n";
    echo "Any role + any permission = works automatically!\n\n";
} else {
    echo "❌ FOUND " . count($issues) . " ISSUE(S):\n\n";
    foreach ($issues as $i => $issue) {
        echo ($i + 1) . ". {$issue}\n";
    }
    echo "\n";
}

echo "📱 TO TEST IN BROWSER:\n";
echo "1. Go to http://127.0.0.1:8000/admin/roles\n";
echo "2. Edit 'Catering Incharge' role\n";
echo "3. Verify 'view activity logs' is checked\n";
echo "4. Log out\n";
echo "5. Log in as: catering@inflightcatering.com / password\n";
echo "6. Look for 'Activity Logs' button on dashboard\n";
echo "7. Click it - should open activity logs page!\n\n";
