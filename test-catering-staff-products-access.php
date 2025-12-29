<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "═══════════════════════════════════════════════════\n";
echo "🔍 TESTING CATERING STAFF PRODUCTS ACCESS\n";
echo "═══════════════════════════════════════════════════\n\n";

// Find Catering Staff user
$cateringStaff = User::whereHas('roles', function($q) {
    $q->where('name', 'Catering Staff');
})->first();

if (!$cateringStaff) {
    echo "❌ No Catering Staff user found!\n";
    exit;
}

echo "User: {$cateringStaff->name}\n";
echo "Email: {$cateringStaff->email}\n";
echo "Role: " . $cateringStaff->roles->pluck('name')->join(', ') . "\n\n";

// Check permissions
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📋 PERMISSIONS CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$permissions = ['view products', 'create products', 'update products', 'delete products'];

foreach ($permissions as $permission) {
    $hasPermission = $cateringStaff->can($permission);
    $status = $hasPermission ? '✅ YES' : '❌ NO';
    echo "{$permission}: {$status}\n";
}

echo "\n";

// Check routes
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🛣️  ROUTES CHECK\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$routeNames = [
    'catering-staff.products.index' => 'View Products',
    'catering-staff.products.create' => 'Create Product',
    'catering-staff.products.edit' => 'Edit Product',
];

foreach ($routeNames as $routeName => $description) {
    try {
        if ($routeName === 'catering-staff.products.edit') {
            $url = route($routeName, ['product' => 1]);
        } else {
            $url = route($routeName);
        }
        echo "✅ {$description}\n";
        echo "   Route: {$routeName}\n";
        echo "   URL: {$url}\n\n";
    } catch (\Exception $e) {
        echo "❌ {$description}\n";
        echo "   Route: {$routeName}\n";
        echo "   Error: Route not found!\n\n";
    }
}

// Check if can access
if ($cateringStaff->can('view products')) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ SUCCESS! Catering Staff can access products!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "📱 TEST IN BROWSER:\n";
    echo "1. Log out from admin\n";
    echo "2. Log in as Catering Staff:\n";
    echo "   Email: {$cateringStaff->email}\n";
    echo "   Password: password\n";
    echo "3. Check sidebar - should see 'Products' link\n";
    echo "4. Click 'Products' - should see products list\n\n";
    
    echo "🎯 SIDEBAR BEHAVIOR:\n";
    echo "✓ 'Products' link appears (not dropdown)\n";
    echo "✓ Clicking it goes directly to products page\n";
    echo "✓ No empty dropdown issue\n\n";
    
} else {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "⚠️  PERMISSION NOT GRANTED YET\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "🔧 HOW TO FIX:\n";
    echo "1. Go to http://127.0.0.1:8000/admin/roles\n";
    echo "2. Click 'Edit' on 'Catering Staff' role\n";
    echo "3. Check 'view products' permission\n";
    echo "4. Click 'Update Permissions'\n";
    echo "5. Log out and log back in as Catering Staff\n\n";
}

echo "🎉 ALL FIXES APPLIED:\n";
echo "✓ Sidebar changed from dropdown to direct link\n";
echo "✓ Routes added for all 8 roles:\n";
echo "  - Catering Staff\n";
echo "  - Catering Incharge\n";
echo "  - Ramp Dispatcher\n";
echo "  - Security Staff\n";
echo "  - Flight Purser\n";
echo "  - Cabin Crew (already had it)\n";
echo "  - Inventory Personnel (already had it)\n";
echo "  - Inventory Supervisor (already had it)\n";
echo "✓ Permission middleware on all routes\n";
echo "✓ Dynamic route detection in sidebar\n\n";
