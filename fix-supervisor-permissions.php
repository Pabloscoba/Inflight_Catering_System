<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Get Inventory Supervisor role
$supervisorRole = Role::where('name', 'Inventory Supervisor')->first();

if (!$supervisorRole) {
    echo "❌ Inventory Supervisor role not found!\n";
    exit(1);
}

echo "Current Inventory Supervisor permissions:\n";
foreach ($supervisorRole->permissions as $perm) {
    echo "  - {$perm->name}\n";
}

// Permissions needed for admin requests access
$neededPermissions = [
    'view all requests',
    'view pending requests',
    'view approved requests',
    'view rejected requests',
];

$addedCount = 0;
foreach ($neededPermissions as $permName) {
    // Create or get permission
    $permission = Permission::firstOrCreate(['name' => $permName]);
    
    // Check if role already has this permission
    if (!$supervisorRole->hasPermissionTo($permName)) {
        $supervisorRole->givePermissionTo($permName);
        echo "✅ Added permission: {$permName}\n";
        $addedCount++;
    } else {
        echo "ℹ️  Already has: {$permName}\n";
    }
}

echo "\n✅ Inventory Supervisor can now access admin requests pages!\n";
echo "Total permissions added: {$addedCount}\n";
