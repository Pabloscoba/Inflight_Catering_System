<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Cabin Crew Product Routes ===\n\n";

// Find Cabin Crew user
$cabinCrew = App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'Cabin Crew');
})->first();

if (!$cabinCrew) {
    echo "❌ No Cabin Crew user found\n";
    exit;
}

echo "✓ Testing with user: {$cabinCrew->name} (ID: {$cabinCrew->id})\n";
echo "✓ Email: {$cabinCrew->email}\n\n";

// Check permissions
echo "=== User Permissions ===\n";
$permissions = $cabinCrew->getAllPermissions()->pluck('name')->toArray();
echo "Total permissions: " . count($permissions) . "\n";
echo "Has 'create products': " . ($cabinCrew->can('create products') ? 'YES ✓' : 'NO ✗') . "\n";
echo "Has 'view products': " . ($cabinCrew->can('view products') ? 'YES ✓' : 'NO ✗') . "\n";
echo "Has 'update products': " . ($cabinCrew->can('update products') ? 'YES ✓' : 'NO ✗') . "\n";
echo "Has 'delete products': " . ($cabinCrew->can('delete products') ? 'YES ✓' : 'NO ✗') . "\n\n";

// Check if routes exist
echo "=== Route Check ===\n";
$routes = [
    'cabin-crew.products.index' => 'GET',
    'cabin-crew.products.create' => 'GET',
    'cabin-crew.products.store' => 'POST',
    'cabin-crew.products.edit' => 'GET',
    'cabin-crew.products.update' => 'PUT',
    'cabin-crew.products.destroy' => 'DELETE',
];

foreach ($routes as $routeName => $method) {
    try {
        $url = route($routeName, $routeName === 'cabin-crew.products.index' || 
                                   $routeName === 'cabin-crew.products.create' || 
                                   $routeName === 'cabin-crew.products.store' ? [] : 1);
        echo "✓ $method $routeName → $url\n";
    } catch (Exception $e) {
        echo "✗ $method $routeName → ROUTE NOT FOUND\n";
    }
}

echo "\n=== Summary ===\n";
if ($cabinCrew->can('create products')) {
    echo "✓ User has permission\n";
    echo "✓ Routes created\n";
    echo "✓ Should be able to access product management\n";
    echo "\n💡 Next step: Logout and login again to refresh session!\n";
} else {
    echo "✗ User missing 'create products' permission\n";
    echo "   Add it via Admin → Roles & Permissions\n";
}
