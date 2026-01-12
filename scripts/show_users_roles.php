<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userModel = '\App\Models\User';
$users = $userModel::where('email', 'like', '%@inflightcatering.com')->get();
if ($users->isEmpty()) {
    echo "No users found with @inflightcatering.com\n";
    exit;
}
foreach ($users as $u) {
    echo "User [{$u->id}] {$u->email} ({$u->name})\n";
    echo " - Roles: " . implode(', ', $u->getRoleNames()->toArray()) . "\n";
    echo " - Direct permissions: " . implode(', ', $u->getDirectPermissions()->pluck('name')->toArray()) . "\n";
    echo " - All permissions: " . implode(', ', $u->getAllPermissions()->pluck('name')->toArray()) . "\n";
    echo "\n";
}
