<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Get Admin role
$adminRole = Role::where('name', 'Admin')->first();

if (!$adminRole) {
    echo "❌ Admin role not found!\n";
    exit(1);
}

echo "Fixing Admin role permissions...\n\n";

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
    if (!$adminRole->hasPermissionTo($permName)) {
        $adminRole->givePermissionTo($permName);
        echo "✅ Added permission to Admin: {$permName}\n";
        $addedCount++;
    } else {
        echo "ℹ️  Admin already has: {$permName}\n";
    }
}

echo "\n✅ Admin role permissions updated!\n";
echo "Total permissions added: {$addedCount}\n";

// Show all Admin permissions
echo "\nAll Admin permissions:\n";
foreach ($adminRole->permissions as $perm) {
    echo "  - {$perm->name}\n";
}
