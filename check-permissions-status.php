<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== PERMISSION SYSTEM STATUS ===\n\n";

echo "Total Permissions: " . Permission::count() . "\n";
echo "Total Roles: " . Role::count() . "\n\n";

echo "=== INVENTORY SUPERVISOR PERMISSIONS ===\n";
$invSupervisor = Role::where('name', 'Inventory Supervisor')->first();
if ($invSupervisor) {
    $perms = $invSupervisor->permissions->pluck('name')->sort()->values();
    echo "Count: " . $perms->count() . "\n";
    echo "Permissions:\n";
    foreach ($perms as $perm) {
        echo "  - $perm\n";
    }
} else {
    echo "Role not found!\n";
}

echo "\n=== CHECKING KEY PERMISSIONS ===\n";
$keyPerms = [
    'view stock levels',
    'add stock',
    'issue stock',
    'approve stock movements',
    'manage stock',
];

foreach ($keyPerms as $perm) {
    $exists = Permission::where('name', $perm)->exists();
    $hasIt = $invSupervisor && $invSupervisor->hasPermissionTo($perm);
    echo sprintf("  %-30s exists: %-3s | Inv.Supervisor has: %s\n", 
        $perm, 
        $exists ? 'YES' : 'NO',
        $hasIt ? 'YES' : 'NO'
    );
}

echo "\n=== FLIGHT DISPATCHER PERMISSIONS ===\n";
$flightDispatcher = Role::where('name', 'Flight Dispatcher')->first();
if ($flightDispatcher) {
    $perms = $flightDispatcher->permissions->pluck('name')->sort()->values();
    echo "Count: " . $perms->count() . "\n";
    echo "Permissions:\n";
    foreach ($perms as $perm) {
        echo "  - $perm\n";
    }
}

echo "\n✅ Done!\n";
