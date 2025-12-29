<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "🔁 Syncing Flight Dispatcher permissions...\n";

$flightDispatcher = Role::where('name', 'Flight Dispatcher')->first();
if (!$flightDispatcher) {
    echo "✖ Role 'Flight Dispatcher' not found.\n";
    exit(1);
}

$ramp = Role::where('name', 'Ramp Dispatcher')->first();
$purser = Role::where('name', 'Flight Purser')->first();

$desired = $flightDispatcher->permissions->pluck('name')->toArray();

if ($ramp) {
    $desired = array_merge($desired, $ramp->permissions->pluck('name')->toArray());
}
if ($purser) {
    $desired = array_merge($desired, $purser->permissions->pluck('name')->toArray());
}

$desired = array_unique($desired);

// Ensure all permissions exist (should already)
foreach ($desired as $permName) {
    Permission::firstOrCreate(['name' => $permName]);
}

$flightDispatcher->syncPermissions($desired);

echo "✅ Synced permissions to 'Flight Dispatcher' (" . count($desired) . " permissions)\n";
foreach ($flightDispatcher->permissions as $p) {
    echo " - {$p->name}\n";
}

echo "\nDone.\n";
