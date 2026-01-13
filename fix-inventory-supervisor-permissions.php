<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "🔧 Fixing Inventory Supervisor Permissions...\n\n";

// Get or create permissions
$permissions = [
    'approve deny catering requests',
    'view incoming requests from catering staff',
    'approve products',
    'approve stock movements',
    'view stock levels'
];

foreach ($permissions as $permName) {
    $perm = Permission::firstOrCreate(['name' => $permName]);
    echo "✓ Permission: {$permName}\n";
}

// Get Inventory Supervisor role
$role = Role::where('name', 'Inventory Supervisor')->first();
if ($role) {
    echo "\n✓ Found Inventory Supervisor role\n";
    
    // Give all permissions to role
    foreach ($permissions as $permName) {
        if (!$role->hasPermissionTo($permName)) {
            $role->givePermissionTo($permName);
            echo "✓ Given permission: {$permName}\n";
        } else {
            echo "  Already has: {$permName}\n";
        }
    }
    
    // Find all Inventory Supervisor users and sync permissions
    $users = User::role('Inventory Supervisor')->get();
    echo "\n✓ Found {$users->count()} Inventory Supervisor user(s)\n";
    
    foreach ($users as $user) {
        echo "  Syncing permissions for: {$user->name}\n";
        foreach ($permissions as $permName) {
            if (!$user->hasPermissionTo($permName)) {
                $user->givePermissionTo($permName);
                echo "    ✓ Given: {$permName}\n";
            }
        }
    }
    
    // Clear cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    echo "\n✅ Permissions cache cleared!\n";
} else {
    echo "❌ Inventory Supervisor role not found!\n";
}

echo "\n✅ Done!\n";
