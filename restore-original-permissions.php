<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "════════════════════════════════════════════════════════════════\n";
echo "🔄 REVERTING TO ORIGINAL PERMISSION STATE\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Get permission
$viewProductsPermission = Permission::where('name', 'view products')->first();

if (!$viewProductsPermission) {
    echo "❌ Permission 'view products' not found!\n";
    exit;
}

echo "Found permission: {$viewProductsPermission->name} (ID: {$viewProductsPermission->id})\n\n";

// Original state - only these 3 roles should have the permission
$rolesShouldHavePermission = [
    'Inventory Personnel',
    'Inventory Supervisor',
    'Catering Staff'
];

// Roles that should NOT have the permission
$rolesShouldNotHavePermission = [
    'Admin',
    'Cabin Crew',
    'Catering Incharge',
    'Security Staff',
    'Ramp Dispatcher',
    'Flight Purser'
];

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "REMOVING PERMISSION FROM ROLES THAT SHOULDN'T HAVE IT\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($rolesShouldNotHavePermission as $roleName) {
    $role = Role::where('name', $roleName)->first();
    
    if ($role) {
        if ($role->hasPermissionTo('view products')) {
            $role->revokePermissionTo('view products');
            echo "  ✅ Removed from: {$roleName}\n";
        } else {
            echo "  ✓ {$roleName} - already doesn't have it\n";
        }
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "ENSURING THESE ROLES KEEP THE PERMISSION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($rolesShouldHavePermission as $roleName) {
    $role = Role::where('name', $roleName)->first();
    
    if ($role) {
        if (!$role->hasPermissionTo('view products')) {
            $role->givePermissionTo('view products');
            echo "  ✅ Added to: {$roleName}\n";
        } else {
            echo "  ✓ {$roleName} - already has it\n";
        }
    }
}

echo "\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "✅ PERMISSIONS RESTORED TO ORIGINAL STATE\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Verify final state
echo "Final verification:\n\n";
$allRoles = Role::all();
foreach ($allRoles as $role) {
    $role->refresh();
    $hasPermission = $role->hasPermissionTo('view products');
    
    if ($hasPermission) {
        echo "  ✅ {$role->name} - HAS permission\n";
    } else {
        echo "  ⭕ {$role->name} - no permission\n";
    }
}

echo "\n";
echo "🎯 WHAT WAS THE REAL PROBLEM:\n\n";
echo "The issue was NOT the permissions - Catering Staff had it!\n";
echo "The issue was the SIDEBAR STRUCTURE:\n\n";
echo "BEFORE (dropdown with empty content for Catering Staff):\n";
echo "  - Dropdown button appears (because of @can check)\n";
echo "  - BUT inside dropdown, only Admin/Inventory links\n";
echo "  - Catering Staff sees empty dropdown\n\n";
echo "AFTER FIX (direct link):\n";
echo "  - Single 'Products' link\n";
echo "  - Dynamic route based on user role\n";
echo "  - Works for ALL roles that have the permission\n\n";
echo "Now only these 3 roles will see 'Products' in sidebar:\n";
echo "  1. Inventory Personnel\n";
echo "  2. Inventory Supervisor\n";
echo "  3. Catering Staff\n\n";
echo "To add permission to other roles later:\n";
echo "  → Go to http://127.0.0.1:8000/admin/roles\n";
echo "  → Edit the role\n";
echo "  → Check 'view products'\n";
echo "  → Link will appear automatically!\n\n";
