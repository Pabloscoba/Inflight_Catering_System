<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

echo "=== COMPREHENSIVE ROLE & PERMISSION AUDIT ===\n\n";

$roles = Role::with('permissions')->get();
$allPermissions = Permission::all();

echo "TOTAL PERMISSIONS IN SYSTEM: {$allPermissions->count()}\n";
echo "TOTAL ROLES: {$roles->count()}\n\n";

echo str_repeat("=", 80) . "\n\n";

foreach ($roles as $role) {
    echo "ROLE: {$role->name}\n";
    echo str_repeat("-", 80) . "\n";
    
    $permissions = $role->permissions;
    echo "Total Permissions: {$permissions->count()}\n\n";
    
    if ($permissions->count() > 0) {
        echo "Permissions List:\n";
        foreach ($permissions->sortBy('name') as $perm) {
            echo "  ✓ {$perm->name}\n";
        }
    } else {
        echo "  ⚠️  WARNING: No permissions assigned to this role!\n";
    }
    
    // Check if any users have this role
    $userCount = User::role($role->name)->count();
    echo "\nUsers with this role: {$userCount}\n";
    
    if ($userCount > 0) {
        $users = User::role($role->name)->get();
        foreach ($users as $user) {
            echo "  - {$user->name} ({$user->email})\n";
        }
    }
    
    echo "\n" . str_repeat("=", 80) . "\n\n";
}

// Check for potential issues
echo "POTENTIAL ISSUES CHECK:\n";
echo str_repeat("-", 80) . "\n";

$issues = [];

// 1. Roles with no permissions
$emptyRoles = $roles->filter(function($role) {
    return $role->permissions->count() === 0;
});

if ($emptyRoles->count() > 0) {
    echo "⚠️  Roles with NO permissions:\n";
    foreach ($emptyRoles as $role) {
        echo "   - {$role->name}\n";
    }
    echo "\n";
}

// 2. Users with no roles
$usersNoRole = User::doesntHave('roles')->count();
if ($usersNoRole > 0) {
    echo "⚠️  Users without any role: {$usersNoRole}\n";
    $users = User::doesntHave('roles')->get();
    foreach ($users as $user) {
        echo "   - {$user->name} ({$user->email})\n";
    }
    echo "\n";
}

// 3. Check key permissions exist
$keyPermissions = [
    'view products',
    'create products', 
    'approve products',
    'view stock levels',
    'issue stock',
    'approve stock movements',
    'view own catering requests',
    'create catering request',
    'approve catering staff requests',
    'authenticate requests',
    'receive approved items',
];

echo "KEY PERMISSIONS VERIFICATION:\n";
foreach ($keyPermissions as $permName) {
    $exists = Permission::where('name', $permName)->exists();
    echo ($exists ? "✓" : "✗") . " {$permName}\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "\n=== AUDIT COMPLETE ===\n";
