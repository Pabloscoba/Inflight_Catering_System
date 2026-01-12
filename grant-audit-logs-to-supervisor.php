<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== GRANTING AUDIT LOGS PERMISSION TO INVENTORY SUPERVISOR ===\n\n";

$role = Role::where('name', 'Inventory Supervisor')->first();

if (!$role) {
    echo "❌ Inventory Supervisor role not found!\n";
    exit;
}

$permission = Permission::where('name', 'view audit logs')->first();

if (!$permission) {
    echo "❌ 'view audit logs' permission not found!\n";
    exit;
}

if ($role->hasPermissionTo('view audit logs')) {
    echo "ℹ️  Inventory Supervisor ALREADY HAS 'view audit logs' permission\n";
} else {
    $role->givePermissionTo('view audit logs');
    echo "✅ GRANTED 'view audit logs' permission to Inventory Supervisor\n";
}

echo "\n✨ DONE! Inventory Supervisor can now access Audit Logs.\n\n";
