<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== Testing Role & Permission System ===\n\n";

// Test 1: Check all roles
echo "1. ALL ROLES:\n";
$roles = Role::with('permissions')->get();
foreach ($roles as $role) {
    echo "   - {$role->name} ({$role->permissions->count()} permissions)\n";
}

// Test 2: Check a specific role's permissions
echo "\n2. INVENTORY SUPERVISOR PERMISSIONS:\n";
$invSupervisor = Role::where('name', 'Inventory Supervisor')->first();
if ($invSupervisor) {
    $permissions = $invSupervisor->permissions;
    echo "   Total: {$permissions->count()} permissions\n";
    foreach ($permissions as $perm) {
        echo "   - {$perm->name}\n";
    }
} else {
    echo "   Role not found!\n";
}

// Test 3: Check if permissions exist
echo "\n3. TOTAL PERMISSIONS IN SYSTEM:\n";
$allPermissions = Permission::count();
echo "   Total: {$allPermissions} permissions\n";

// Test 4: Test sync functionality
echo "\n4. TESTING SYNC (Dry run - no actual changes):\n";
$testRole = Role::where('name', 'Catering Staff')->first();
if ($testRole) {
    echo "   Role: {$testRole->name}\n";
    echo "   Current permissions: {$testRole->permissions->count()}\n";
    echo "   Sync would work: " . (method_exists($testRole, 'syncPermissions') ? 'YES ✓' : 'NO ✗') . "\n";
}

echo "\n=== Test Complete ===\n";
