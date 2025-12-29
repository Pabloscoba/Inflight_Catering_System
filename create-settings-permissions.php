<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== CREATING MISSING SETTINGS PERMISSIONS ===\n\n";

// Permissions needed for Settings dropdown
$settingsPermissions = [
    'manage system settings' => 'Manage system settings and configuration',
    'view activity logs' => 'View system activity logs',
    'view audit logs' => 'View system audit logs',
    'manage backups' => 'Manage system backups'
];

echo "CREATING PERMISSIONS:\n";
echo "-------------------\n";

foreach ($settingsPermissions as $name => $description) {
    $permission = Permission::firstOrCreate(
        ['name' => $name],
        ['guard_name' => 'web']
    );
    
    if ($permission->wasRecentlyCreated) {
        echo "✅ CREATED: $name\n";
    } else {
        echo "ℹ️  EXISTS: $name\n";
    }
}

// Assign all permissions to Admin role
echo "\n\nASSIGNING PERMISSIONS TO ADMIN ROLE:\n";
echo "-----------------------------------\n";

$adminRole = Role::where('name', 'Admin')->first();

if (!$adminRole) {
    echo "❌ Admin role not found!\n";
    exit;
}

foreach (array_keys($settingsPermissions) as $permName) {
    if (!$adminRole->hasPermissionTo($permName)) {
        $adminRole->givePermissionTo($permName);
        echo "✅ GRANTED: $permName\n";
    } else {
        echo "ℹ️  ALREADY HAS: $permName\n";
    }
}

echo "\n\n✨ DONE! Admin role now has all settings permissions.\n";
echo "🔄 Please refresh your browser to see the Settings dropdown working.\n\n";
