<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

echo "=== DEBUGGING CABIN CREW PERMISSIONS ===\n\n";

// 1. Check Cabin Crew role permissions
$cabinCrewRole = Role::where('name', 'Cabin Crew')->first();

if ($cabinCrewRole) {
    echo "CABIN CREW ROLE (ID: {$cabinCrewRole->id}):\n";
    echo str_repeat("-", 60) . "\n";
    
    $permissions = $cabinCrewRole->permissions;
    echo "Total Permissions: {$permissions->count()}\n\n";
    
    echo "Permissions:\n";
    foreach ($permissions as $perm) {
        echo "  - {$perm->name} (ID: {$perm->id})\n";
    }
    
    // Check specifically for 'create products'
    $hasCreateProducts = $cabinCrewRole->hasPermissionTo('create products');
    echo "\n";
    echo "Has 'create products' permission? " . ($hasCreateProducts ? "YES ✓" : "NO ✗") . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// 2. Check actual Cabin Crew user
$cabinUser = User::role('Cabin Crew')->first();

if ($cabinUser) {
    echo "CABIN CREW USER: {$cabinUser->name}\n";
    echo str_repeat("-", 60) . "\n";
    
    $userPermissions = $cabinUser->getAllPermissions();
    echo "Total Permissions: {$userPermissions->count()}\n\n";
    
    echo "User's Permissions:\n";
    foreach ($userPermissions as $perm) {
        echo "  - {$perm->name}\n";
    }
    
    echo "\n";
    echo "User can 'create products'? " . ($cabinUser->can('create products') ? "YES ✓" : "NO ✗") . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// 3. Check if 'create products' permission exists
$createProductsPerm = Permission::where('name', 'create products')->first();
if ($createProductsPerm) {
    echo "'CREATE PRODUCTS' PERMISSION:\n";
    echo str_repeat("-", 60) . "\n";
    echo "ID: {$createProductsPerm->id}\n";
    echo "Name: {$createProductsPerm->name}\n";
    echo "Guard: {$createProductsPerm->guard_name}\n";
    
    // Check which roles have this permission
    $rolesWithPerm = $createProductsPerm->roles;
    echo "\nRoles with this permission:\n";
    foreach ($rolesWithPerm as $role) {
        echo "  - {$role->name}\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// 4. Clear cache and re-check
echo "CLEARING PERMISSION CACHE...\n";
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "Cache cleared ✓\n\n";

// Re-check after cache clear
$cabinUser = User::role('Cabin Crew')->first();
if ($cabinUser) {
    echo "AFTER CACHE CLEAR:\n";
    echo "User can 'create products'? " . ($cabinUser->can('create products') ? "YES ✓" : "NO ✗") . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
