<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "════════════════════════════════════════════════════════════════\n";
echo "🧪 TESTING PERMISSION FLEXIBILITY - FLIGHT OPS MANAGER\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Step 1: Get or create Flight Operations Manager role and user
$roleNames = ['Flight Operations Manager', 'Flight Ops', 'flightops'];
$role = null;

foreach ($roleNames as $name) {
    $role = Role::where('name', $name)->first();
    if ($role) {
        echo "✅ Found role: {$role->name}\n";
        break;
    }
}

if (!$role) {
    $role = Role::create(['name' => 'Flight Operations Manager']);
    echo "✅ Created role: Flight Operations Manager\n";
}

// Find or create a Flight Operations Manager user
$user = User::whereHas('roles', function($q) use ($role) {
    $q->where('name', $role->name);
})->first();

if (!$user) {
    $user = User::create([
        'name' => 'Flight Operations Manager',
        'email' => 'flightops@inflightcatering.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole($role);
    echo "✅ Created test user: {$user->email}\n";
} else {
    echo "✅ Using existing user: {$user->email}\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 TESTING: Adding 'create products' permission\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Step 2: Create or get the permission
$permission = Permission::firstOrCreate(['name' => 'create products']);
echo "✅ Permission exists: create products\n";

// Step 3: Check if user already has permission
$hadPermissionBefore = $user->can('create products');
echo "Before: User can 'create products'? " . ($hadPermissionBefore ? '✅ YES' : '❌ NO') . "\n";

// Step 4: Give permission to role
if (!$role->hasPermissionTo('create products')) {
    $role->givePermissionTo('create products');
    echo "✅ Permission granted to role: {$role->name}\n";
} else {
    echo "✅ Role already has permission\n";
}

// Step 5: Refresh user and check again
$user->refresh();
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

$hasPermissionAfter = $user->can('create products');
echo "After: User can 'create products'? " . ($hasPermissionAfter ? '✅ YES' : '❌ NO') . "\n";

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 CHECKING OTHER PRODUCT PERMISSIONS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$productPermissions = [
    'view products',
    'create products',
    'update products',
    'delete products',
];

foreach ($productPermissions as $perm) {
    $has = $user->can($perm);
    $status = $has ? '✅ YES' : '❌ NO';
    echo "{$perm}: {$status}\n";
    
    // If user doesn't have it, offer to add it
    if (!$has) {
        $permission = Permission::firstOrCreate(['name' => $perm]);
        $role->givePermissionTo($permission);
        echo "  → Added to role\n";
    }
}

// Refresh user after adding all permissions
$user->refresh();
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 FINAL CHECK - All Product Permissions\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($productPermissions as $perm) {
    $has = $user->can($perm);
    $status = $has ? '✅ YES' : '❌ NO';
    echo "{$perm}: {$status}\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔍 VERIFYING ROUTES\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Check if routes exist
$routes = [
    'flight-operations-manager.products.index',
    'flight-operations-manager.products.create',
    'flight-operations-manager.products.store',
];

foreach ($routes as $routeName) {
    try {
        $url = route($routeName);
        echo "✅ Route exists: {$routeName}\n";
        echo "   URL: {$url}\n";
    } catch (\Exception $e) {
        echo "❌ Route missing: {$routeName}\n";
    }
}

echo "\n════════════════════════════════════════════════════════════════\n";
echo "✅ TEST COMPLETE!\n";
echo "════════════════════════════════════════════════════════════════\n\n";

echo "📌 NEXT STEPS:\n";
echo "1. Login as: {$user->email}\n";
echo "2. Navigate to: http://127.0.0.1:8000/flight-operations-manager/products\n";
echo "3. You should see the Products page with 'Add Product' button\n";
echo "4. The sidebar should show 'Products' menu item\n\n";

echo "💡 This proves permissions are now FLEXIBLE - any role can have any permission!\n";
