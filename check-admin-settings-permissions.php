<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== ADMIN SETTINGS PERMISSIONS CHECK ===\n\n";

// Get Admin role
$adminRole = Role::where('name', 'Admin')->first();

if (!$adminRole) {
    echo "❌ Admin role not found!\n";
    exit;
}

echo "✅ Admin Role Found\n\n";

// Check specific permissions for settings dropdown
$settingsPermissions = [
    'manage system settings',
    'view activity logs',
    'view audit logs',
    'manage backups'
];

echo "CHECKING ADMIN PERMISSIONS FOR SETTINGS DROPDOWN:\n";
echo "------------------------------------------------\n";

foreach ($settingsPermissions as $permName) {
    $permission = Permission::where('name', $permName)->first();
    
    if ($permission) {
        $hasPermission = $adminRole->hasPermissionTo($permName);
        $status = $hasPermission ? "✅ HAS" : "❌ MISSING";
        echo "$status - $permName\n";
    } else {
        echo "⚠️  PERMISSION DOES NOT EXIST - $permName\n";
    }
}

echo "\n\nALL ADMIN PERMISSIONS:\n";
echo "---------------------\n";
$adminPermissions = $adminRole->permissions()->pluck('name')->toArray();
foreach ($adminPermissions as $perm) {
    echo "  • $perm\n";
}

// Check if there are any admins
$admins = User::role('Admin')->get();
echo "\n\nADMIN USERS COUNT: " . $admins->count() . "\n";

if ($admins->count() > 0) {
    echo "\nADMIN USERS:\n";
    foreach ($admins as $admin) {
        echo "  • {$admin->name} ({$admin->email})\n";
        
        // Check direct permissions
        $directPerms = $admin->permissions()->pluck('name')->toArray();
        if (count($directPerms) > 0) {
            echo "    Direct Permissions:\n";
            foreach ($directPerms as $perm) {
                echo "      - $perm\n";
            }
        }
    }
}

echo "\n";
