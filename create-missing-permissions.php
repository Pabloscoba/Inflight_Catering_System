<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;

echo "════════════════════════════════════════════════════════════════\n";
echo "🔧 CREATING MISSING PERMISSIONS\n";
echo "════════════════════════════════════════════════════════════════\n\n";

$missingPermissions = [
    'view activity logs',
    'view audit logs',
    'view reports',
];

foreach ($missingPermissions as $permissionName) {
    $existing = Permission::where('name', $permissionName)->first();
    
    if ($existing) {
        echo "✓ {$permissionName} - already exists (ID: {$existing->id})\n";
    } else {
        $permission = Permission::create(['name' => $permissionName]);
        echo "✅ {$permissionName} - CREATED (ID: {$permission->id})\n";
    }
}

echo "\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "✅ ALL PERMISSIONS NOW EXIST IN DATABASE\n";
echo "════════════════════════════════════════════════════════════════\n\n";

echo "These permissions are now available in the system.\n";
echo "You can assign them to any role via:\n";
echo "http://127.0.0.1:8000/admin/roles\n\n";

echo "When you assign a permission to a role, the corresponding\n";
echo "button/link will appear AUTOMATICALLY on their dashboard!\n\n";

// Show all permissions now
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 ALL PERMISSIONS IN SYSTEM (" . Permission::count() . " total)\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$allPermissions = Permission::orderBy('name')->get();
foreach ($allPermissions as $perm) {
    $rolesCount = $perm->roles->count();
    echo "• {$perm->name} ({$rolesCount} role" . ($rolesCount != 1 ? 's' : '') . ")\n";
}

echo "\n";
