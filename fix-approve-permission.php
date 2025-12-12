<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== Checking Inventory Supervisor Permissions ===\n\n";

$role = Role::where('name', 'Inventory Supervisor')->first();

if (!$role) {
    echo "❌ Inventory Supervisor role not found\n";
    exit;
}

echo "Role: {$role->name}\n";
echo "Permissions:\n";

$permissions = $role->permissions()->orderBy('name')->get();

foreach ($permissions as $permission) {
    echo "  - {$permission->name}\n";
}

echo "\n\n=== Checking if 'approve requests' permission exists ===\n\n";

$approvePermission = Permission::where('name', 'approve requests')->first();

if ($approvePermission) {
    echo "✅ Permission 'approve requests' exists\n";
    
    if ($role->hasPermissionTo('approve requests')) {
        echo "✅ Inventory Supervisor HAS 'approve requests' permission\n";
    } else {
        echo "❌ Inventory Supervisor DOES NOT HAVE 'approve requests' permission\n";
        echo "\nAdding permission now...\n";
        $role->givePermissionTo('approve requests');
        echo "✅ Permission added!\n";
    }
} else {
    echo "❌ Permission 'approve requests' does not exist\n";
    echo "Creating permission...\n";
    $perm = Permission::create(['name' => 'approve requests']);
    $role->givePermissionTo($perm);
    echo "✅ Permission created and assigned!\n";
}
