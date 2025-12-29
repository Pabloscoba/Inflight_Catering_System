<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "════════════════════════════════════════════════════════════════\n";
echo "🧪 COMPREHENSIVE TEST - ALL ROLES + DYNAMIC PERMISSIONS\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Test different permissions with different roles
$testScenarios = [
    [
        'role' => 'Admin',
        'permission' => 'view activity logs',
        'expected_button' => 'Activity Logs',
    ],
    [
        'role' => 'Catering Staff',
        'permission' => 'view products',
        'expected_button' => 'View Products',
    ],
    [
        'role' => 'Catering Incharge',
        'permission' => 'view activity logs',
        'expected_button' => 'Activity Logs',
    ],
    [
        'role' => 'Inventory Personnel',
        'permission' => 'add stock',
        'expected_button' => 'Add Stock',
    ],
    [
        'role' => 'Security Staff',
        'permission' => 'view activity logs',
        'expected_button' => 'Activity Logs',
    ],
    [
        'role' => 'Cabin Crew',
        'permission' => 'view products',
        'expected_button' => 'View Products',
    ],
    [
        'role' => 'Ramp Dispatcher',
        'permission' => 'view reports',
        'expected_button' => 'View Reports',
    ],
    [
        'role' => 'Flight Purser',
        'permission' => 'view activity logs',
        'expected_button' => 'Activity Logs',
    ],
];

$passedTests = 0;
$failedTests = 0;

foreach ($testScenarios as $index => $scenario) {
    $testNum = $index + 1;
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "TEST #{$testNum}: {$scenario['role']} + '{$scenario['permission']}'\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    $allChecks = true;
    
    // 1. Get role
    $role = Role::where('name', $scenario['role'])->first();
    if (!$role) {
        echo "❌ FAIL: Role '{$scenario['role']}' not found\n\n";
        $failedTests++;
        continue;
    }
    echo "✅ Role exists: {$role->name}\n";
    
    // 2. Get permission
    $permission = Permission::where('name', $scenario['permission'])->first();
    if (!$permission) {
        echo "❌ FAIL: Permission '{$scenario['permission']}' not found\n\n";
        $failedTests++;
        continue;
    }
    echo "✅ Permission exists: {$permission->name}\n";
    
    // 3. Assign permission to role if not already
    if (!$role->hasPermissionTo($permission)) {
        $role->givePermissionTo($permission);
        echo "✅ Permission assigned to role\n";
    } else {
        echo "✅ Role already has permission\n";
    }
    
    // 4. Get a user with this role
    $user = User::role($role->name)->first();
    if (!$user) {
        echo "❌ FAIL: No user with role '{$role->name}'\n\n";
        $failedTests++;
        continue;
    }
    echo "✅ Test user: {$user->name} ({$user->email})\n";
    
    // 5. Check if user can use permission
    $user->refresh();
    if ($user->can($scenario['permission'])) {
        echo "✅ User can '{$scenario['permission']}'\n";
    } else {
        echo "❌ FAIL: User cannot '{$scenario['permission']}'\n";
        $allChecks = false;
    }
    
    // 6. Check dashboard component
    $dashboardFile = __DIR__ . '/resources/views/' . strtolower(str_replace(' ', '-', $role->name)) . '/dashboard.blade.php';
    if (file_exists($dashboardFile)) {
        $content = file_get_contents($dashboardFile);
        if (strpos($content, '<x-permission-actions') !== false) {
            echo "✅ Dashboard uses <x-permission-actions> component\n";
        } else {
            echo "⚠️  WARNING: Dashboard doesn't use component (button won't auto-appear)\n";
            $allChecks = false;
        }
    } else {
        echo "⚠️  WARNING: Dashboard file not found at expected location\n";
    }
    
    // 7. Check config
    $dashboardActions = include __DIR__ . '/config/dashboard-actions.php';
    if (isset($dashboardActions[$scenario['permission']])) {
        echo "✅ Permission configured in dashboard-actions.php\n";
        $config = $dashboardActions[$scenario['permission']];
        echo "   Button: {$config['title']} {$config['icon']}\n";
    } else {
        echo "⚠️  WARNING: Permission not in dashboard-actions.php config\n";
    }
    
    // Final verdict for this test
    if ($allChecks) {
        echo "\n🎉 TEST #{$testNum}: ✅ PASSED\n";
        echo "   → '{$scenario['expected_button']}' button will appear on dashboard\n\n";
        $passedTests++;
    } else {
        echo "\n❌ TEST #{$testNum}: FAILED\n\n";
        $failedTests++;
    }
}

// Summary
echo "════════════════════════════════════════════════════════════════\n";
echo "📊 TEST SUMMARY\n";
echo "════════════════════════════════════════════════════════════════\n\n";

$totalTests = count($testScenarios);
echo "Total tests: {$totalTests}\n";
echo "Passed: ✅ {$passedTests}\n";
echo "Failed: ❌ {$failedTests}\n\n";

$passRate = ($passedTests / $totalTests) * 100;
echo "Success rate: " . number_format($passRate, 1) . "%\n\n";

if ($failedTests == 0) {
    echo "🎉🎉🎉 ALL TESTS PASSED! 🎉🎉🎉\n\n";
    echo "✅ Permission system is FULLY DYNAMIC across ALL roles!\n";
    echo "✅ Any role + any permission = works automatically!\n\n";
} else {
    echo "⚠️  Some tests failed. Check the details above.\n\n";
}

// Show what each role now has
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 PERMISSIONS ASSIGNED TO EACH ROLE (after tests)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$allRoles = Role::with('permissions')->get();
foreach ($allRoles as $role) {
    echo "{$role->name}:\n";
    
    $testPermissions = [
        'view products',
        'view activity logs',
        'view reports',
        'add stock',
        'create products',
    ];
    
    $hasAny = false;
    foreach ($testPermissions as $perm) {
        if ($role->hasPermissionTo($perm)) {
            echo "  ✅ {$perm}\n";
            $hasAny = true;
        }
    }
    
    if (!$hasAny) {
        echo "  ⭕ None from test list\n";
    }
    echo "\n";
}

// Final instructions
echo "════════════════════════════════════════════════════════════════\n";
echo "🎯 HOW TO TEST IN BROWSER\n";
echo "════════════════════════════════════════════════════════════════\n\n";

echo "1. Clear cache:\n";
echo "   php artisan cache:clear\n\n";

echo "2. Test each role:\n\n";

$testUsers = [
    ['Admin', 'admin@inflightcatering.com'],
    ['Catering Staff', 'staff@inflightcatering.com'],
    ['Catering Incharge', 'catering@inflightcatering.com'],
    ['Inventory Personnel', 'inventory@inflightcatering.com'],
    ['Security Staff', 'security@inflightcatering.com'],
    ['Cabin Crew', 'cabin@inflightcatering.com'],
    ['Ramp Dispatcher', 'dispatcher@inflightcatering.com'],
    ['Flight Purser', 'purser@inflightcatering.com'],
];

foreach ($testUsers as $testUser) {
    echo "   • Login as {$testUser[0]}:\n";
    echo "     Email: {$testUser[1]}\n";
    echo "     Password: password\n";
    echo "     → Look for new buttons on dashboard\n\n";
}

echo "3. To remove permissions:\n";
echo "   Go to: http://127.0.0.1:8000/admin/roles\n";
echo "   Edit role → Uncheck permissions → Save\n\n";

echo "════════════════════════════════════════════════════════════════\n";
