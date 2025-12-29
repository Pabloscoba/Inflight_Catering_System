<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Admin Permissions Check ===\n\n";

$admin = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Admin'))->first();

if ($admin) {
    echo "Admin User: {$admin->name} (ID: {$admin->id})\n";
    echo "Role: " . $admin->roles->pluck('name')->join(', ') . "\n\n";
    
    echo "Permissions:\n";
    $permissions = $admin->getAllPermissions()->pluck('name')->sort();
    foreach ($permissions as $perm) {
        echo "  ✓ {$perm}\n";
    }
    
    echo "\n\nChecking specific permission:\n";
    echo "  'view approved requests': " . ($admin->can('view approved requests') ? '✅ YES' : '❌ NO') . "\n";
} else {
    echo "No admin user found\n";
}
