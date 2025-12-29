<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Adding 'view approved requests' Permission to Admin ===\n\n";

// Get or create permission
$permission = Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view approved requests']);
echo "Permission: {$permission->name}\n";

// Get Admin role
$adminRole = Spatie\Permission\Models\Role::where('name', 'Admin')->first();

if ($adminRole) {
    // Add permission to Admin role
    $adminRole->givePermissionTo($permission);
    echo "✅ Added to Admin role\n\n";
    
    // Verify
    $admin = App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'Admin'))->first();
    echo "Verification:\n";
    echo "  Admin can 'view approved requests': " . ($admin->can('view approved requests') ? '✅ YES' : '❌ NO') . "\n";
} else {
    echo "❌ Admin role not found\n";
}
