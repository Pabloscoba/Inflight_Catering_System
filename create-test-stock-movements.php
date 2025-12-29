<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;

echo "=== Creating Test Stock Movements ===\n\n";

// Get Inventory Personnel user (for user_id)
$inventoryUser = User::role('Inventory Personnel')->first();

if (!$inventoryUser) {
    echo "Error: No Inventory Personnel user found!\n";
    exit(1);
}

// Products to create movements for
$products = [
    ['sku' => 'C001', 'name' => 'Coca Cola'],
    ['sku' => 't-67', 'name' => 'Maji'],
];

foreach ($products as $productData) {
    $product = Product::where('sku', $productData['sku'])->first();
    
    if (!$product) {
        echo "Product {$productData['name']} not found\n";
        continue;
    }

    echo "Creating movements for {$product->name} (ID: {$product->id})...\n";

    // Create 3 issued movements in the last 30 days
    $dates = [
        now()->subDays(5),
        now()->subDays(12),
        now()->subDays(20),
    ];

    foreach ($dates as $index => $date) {
        $movement = StockMovement::create([
            'type' => 'issued',
            'product_id' => $product->id,
            'quantity' => 10 + ($index * 5), // 10, 15, 20
            'reference_number' => 'TEST-' . $product->sku . '-' . ($index + 1),
            'notes' => 'Test stock movement for ' . $product->name,
            'user_id' => $inventoryUser->id,
            'status' => 'approved',
            'approved_by' => $inventoryUser->id,
            'approved_at' => $date,
            'movement_date' => $date,
        ]);

        $movement->created_at = $date;
        $movement->save();

        echo "  ✓ Created issued movement: {$movement->quantity} units on {$date->format('Y-m-d')}\n";
    }

    // Update catering stock to reflect some received items
    $totalIssued = 10 + 15 + 20; // 45
    $product->increment('catering_stock', $totalIssued);
    echo "  ✓ Updated catering_stock: +{$totalIssued} (now {$product->catering_stock})\n";

    echo "\n";
}

echo "=== Summary ===\n";
$totalMovements = StockMovement::count();
echo "Total stock movements in database: {$totalMovements}\n";

$last30Days = StockMovement::where('type', 'issued')
    ->where('created_at', '>=', now()->subDays(30))
    ->count();
echo "Issued movements in last 30 days: {$last30Days}\n";

echo "\n✓ Done! Products now have stock movements and will show issue counts.\n";
