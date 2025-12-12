<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Inventory Supervisor Permissions ===\n\n";

$supervisor = App\Models\User::where('email', 'supervisor@inflightcatering.com')->first();

if (!$supervisor) {
    echo "❌ Inventory Supervisor user not found!\n";
    exit;
}

echo "User: {$supervisor->name} ({$supervisor->email})\n";
echo "Roles: " . $supervisor->roles->pluck('name')->join(', ') . "\n\n";

echo "=== All Permissions ===\n";
$allPermissions = $supervisor->getAllPermissions();
foreach ($allPermissions as $perm) {
    echo "✓ {$perm->name}\n";
}

echo "\n=== Checking Specific Permissions ===\n";
$permissionsToCheck = [
    'approve deny catering requests',
    'view incoming requests from catering staff',
    'approve products',
    'approve stock movements',
];

foreach ($permissionsToCheck as $permName) {
    $has = $supervisor->hasPermissionTo($permName);
    $icon = $has ? '✅' : '❌';
    echo "{$icon} {$permName}\n";
}

echo "\n=== Testing Route Permissions ===\n";
echo "Dashboard approve button uses: 'approve deny catering requests'\n";
echo "Request view uses: 'view incoming requests from catering staff'\n";
