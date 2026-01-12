<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Ensure permission exists
$perm = Permission::firstOrCreate(['name' => 'view settings']);

// Ensure role exists and has the permission
$role = Role::firstOrCreate(['name' => 'Flight Operations Manager']);
if (! $role->hasPermissionTo('view settings')) {
    $role->givePermissionTo('view settings');
    echo "Granted 'view settings' permission to Flight Operations Manager role\n";
} else {
    echo "Flight Operations Manager already has 'view settings' permission\n";
}

// Also ensure Admin has it (optional but useful)
$admin = Role::firstOrCreate(['name' => 'Admin']);
if (! $admin->hasPermissionTo('view settings')) {
    $admin->givePermissionTo('view settings');
    echo "Granted 'view settings' permission to Admin role\n";
}

echo "Done.\n";
