<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$admin = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Admin'))->first();
echo "Admin can 'view approved requests': " . ($admin->can('view approved requests') ? 'YES' : 'NO') . PHP_EOL;

// List all admin permissions
echo "\nAll admin permissions:\n";
foreach ($admin->getAllPermissions() as $perm) {
    echo "  - {$perm->name}\n";
}
