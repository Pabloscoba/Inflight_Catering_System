<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== INVENTORY SUPERVISOR PERMISSIONS CHECK ===\n\n";

$role = Role::where('name', 'Inventory Supervisor')->first();

if (!$role) {
    echo "❌ Inventory Supervisor role not found!\n";
    exit;
}

echo "✅ Inventory Supervisor Role Found\n\n";

$permissions = $role->permissions()->pluck('name')->toArray();

echo "ALL PERMISSIONS (" . count($permissions) . "):\n";
echo "-------------------\n";
foreach ($permissions as $perm) {
    echo "  • $perm\n";
}

echo "\n\nCHECKING SPECIFIC PERMISSIONS:\n";
echo "------------------------------\n";

$checkPermissions = [
    'view audit logs',
    'view inventory report',
    'view activity logs',
    'view reports'
];

foreach ($checkPermissions as $permName) {
    $hasPerm = $role->hasPermissionTo($permName);
    $exists = Permission::where('name', $permName)->exists();
    
    if (!$exists) {
        echo "⚠️  '$permName' - PERMISSION DOESN'T EXIST IN DATABASE\n";
    } elseif ($hasPerm) {
        echo "✅ '$permName' - HAS PERMISSION\n";
    } else {
        echo "❌ '$permName' - MISSING PERMISSION\n";
    }
}

echo "\n";
