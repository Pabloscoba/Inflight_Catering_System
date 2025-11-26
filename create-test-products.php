<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Category;
use App\Models\User;

// Get first category
$category = Category::first();
if (!$category) {
    echo "❌ No categories found! Run: php artisan db:seed --class=CategorySeeder\n";
    exit(1);
}

// Get Inventory Personnel user
$inventoryUser = User::whereHas('roles', function($q) {
    $q->where('name', 'Inventory Personnel');
})->first();

if (!$inventoryUser) {
    echo "❌ No Inventory Personnel user found!\n";
    exit(1);
}

echo "📦 Creating test data...\n\n";

// 1. Create PENDING products (need approval)
$pendingProducts = [
    [
        'name' => 'Chicken Sandwich',
        'sku' => 'FOOD-005',
        'category_id' => $category->id,
        'description' => 'Grilled chicken sandwich',
        'currency' => 'TZS',
        'unit_price' => 6000,
        'quantity_in_stock' => 30,
        'reorder_level' => 20,
        'unit_of_measure' => 'pieces',
        'is_active' => true,
        'status' => 'pending',
    ],
    [
        'name' => 'Beef Burger',
        'sku' => 'FOOD-006',
        'category_id' => $category->id,
        'description' => 'Beef burger with cheese',
        'currency' => 'TZS',
        'unit_price' => 8000,
        'quantity_in_stock' => 25,
        'reorder_level' => 15,
        'unit_of_measure' => 'pieces',
        'is_active' => true,
        'status' => 'pending',
    ],
];

echo "Creating PENDING products (need supervisor approval):\n";
foreach ($pendingProducts as $productData) {
    Product::create($productData);
    echo "  ✓ {$productData['name']} - Status: PENDING\n";
}

// 2. Create APPROVED products with LOW STOCK
$approvedProducts = [
    [
        'name' => 'Orange Juice',
        'sku' => 'BEV-002',
        'category_id' => $category->id,
        'description' => 'Fresh orange juice',
        'currency' => 'TZS',
        'unit_price' => 3000,
        'quantity_in_stock' => 8,
        'reorder_level' => 25,
        'unit_of_measure' => 'bottles',
        'is_active' => true,
        'status' => 'approved',
        'approved_by' => 3, // Supervisor
        'approved_at' => now(),
    ],
    [
        'name' => 'Bottled Water',
        'sku' => 'BEV-003',
        'category_id' => $category->id,
        'description' => '500ml water',
        'currency' => 'TZS',
        'unit_price' => 1000,
        'quantity_in_stock' => 12,
        'reorder_level' => 40,
        'unit_of_measure' => 'bottles',
        'is_active' => true,
        'status' => 'approved',
        'approved_by' => 3,
        'approved_at' => now(),
    ],
];

echo "\nCreating APPROVED products with LOW STOCK:\n";
foreach ($approvedProducts as $productData) {
    Product::create($productData);
    echo "  ✓ {$productData['name']} - Stock: {$productData['quantity_in_stock']}/{$productData['reorder_level']} (LOW)\n";
}

// 3. Create PENDING stock movements
$product = Product::where('status', 'approved')->first();
if ($product) {
    $pendingMovements = [
        [
            'type' => 'incoming',
            'product_id' => $product->id,
            'quantity' => 50,
            'reference_number' => 'INV-2025-001',
            'notes' => 'New stock delivery from supplier',
            'user_id' => $inventoryUser->id,
            'movement_date' => now(),
            'status' => 'pending',
        ],
        [
            'type' => 'issued',
            'product_id' => $product->id,
            'quantity' => 10,
            'reference_number' => 'REQ-2025-001',
            'notes' => 'Issued for Flight TZ456',
            'user_id' => $inventoryUser->id,
            'movement_date' => now(),
            'status' => 'pending',
        ],
    ];

    echo "\nCreating PENDING stock movements (need supervisor approval):\n";
    foreach ($pendingMovements as $movementData) {
        StockMovement::create($movementData);
        echo "  ✓ {$movementData['type']} - Qty: {$movementData['quantity']} - Status: PENDING\n";
    }
}

echo "\n✅ Successfully created test data!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Summary:\n";
echo "  • Pending Products: " . Product::where('status', 'pending')->count() . "\n";
echo "  • Approved Products: " . Product::where('status', 'approved')->count() . "\n";
echo "  • Pending Movements: " . StockMovement::where('status', 'pending')->count() . "\n";
echo "  • Low Stock Items: " . Product::where('status', 'approved')->where('quantity_in_stock', '<', \Illuminate\Support\Facades\DB::raw('reorder_level'))->count() . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
