<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$perms = [
    'view flight requirements',
    'comment on request',
    'recommend dispatch to flight operations',
];

foreach ($perms as $p) {
    Permission::firstOrCreate(['name' => $p]);
}

$role = Role::where('name', 'Flight Dispatcher')->first();
if (! $role) {
    echo "Flight Dispatcher role not found\n";
    exit(1);
}

$existing = $role->permissions->pluck('name')->toArray();
$merged = array_unique(array_merge($existing, $perms));
$role->syncPermissions($merged);

echo "Synced Flight Dispatcher permissions (now: " . count($merged) . ")\n";
foreach ($role->permissions as $p) {
    echo " - " . $p->name . "\n";
}
