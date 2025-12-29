<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "════════════════════════════════════════════════════════════════\n";
echo "🔧 ADDING 'view products' PERMISSION TO ALL ROLES\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Get or create permission
$viewProductsPermission = Permission::firstOrCreate(['name' => 'view products']);
echo "✅ Permission 'view products' ready (ID: {$viewProductsPermission->id})\n\n";

// Get all roles
$roles = Role::all();

echo "Adding permission to roles:\n\n";

foreach ($roles as $role) {
    if ($role->hasPermissionTo('view products')) {
        echo "  ✓ {$role->name} - already has permission\n";
    } else {
        $role->givePermissionTo('view products');
        echo "  ✅ {$role->name} - permission ADDED\n";
    }
}

echo "\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "✅ DONE! All roles now have 'view products' permission\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Verify
echo "Verification:\n\n";
foreach ($roles as $role) {
    $role->refresh();
    $hasPermission = $role->hasPermissionTo('view products');
    $status = $hasPermission ? '✅' : '❌';
    echo "{$status} {$role->name}: " . ($hasPermission ? 'YES' : 'NO') . "\n";
}

echo "\n";
echo "🎯 NEXT STEPS:\n";
echo "1. Clear cache: php artisan cache:clear\n";
echo "2. Log out and log back in\n";
echo "3. Check sidebar - 'Products' link should appear for ALL roles\n";
echo "4. If you don't want certain roles to see products,\n";
echo "   go to http://127.0.0.1:8000/admin/roles and remove the permission\n\n";
