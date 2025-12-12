<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;

// Get Inventory Personnel user
$inventoryPersonnel = User::whereHas('roles', function($q) {
    $q->where('name', 'Inventory Personnel');
})->first();

if (!$inventoryPersonnel) {
    echo "❌ No Inventory Personnel user found!\n";
    exit;
}

// Get available products
$products = Product::all();

if ($products->count() < 1) {
    echo "❌ No products found in database!\n";
    exit;
}

echo "Found {$products->count()} products. Creating test stock movements...\n\n";

// Use first product for multiple movements
$product1 = $products->first();
$product2 = $products->count() > 1 ? $products[1] : $products->first();

// Create Incoming movement
$incoming = StockMovement::create([
    'product_id' => $product1->id,
    'type' => 'incoming',
    'quantity' => 100,
    'movement_date' => now(),
    'reference_number' => 'PO-2024-001',
    'notes' => 'New stock delivery from supplier',
    'user_id' => $inventoryPersonnel->id,
    'status' => 'pending'
]);
echo "✓ Created Incoming movement: {$product1->name} (+100 units)\n";

// Create Transfer movement
$transfer = StockMovement::create([
    'product_id' => $product2->id,
    'type' => 'transfer_to_catering',
    'quantity' => 50,
    'movement_date' => now(),
    'reference_number' => 'TRF-2024-001',
    'notes' => 'Transfer to catering department for flight preparation',
    'user_id' => $inventoryPersonnel->id,
    'status' => 'pending'
]);
echo "✓ Created Transfer movement: {$product2->name} (50 units to catering)\n";

// Create Issued movement
$issued = StockMovement::create([
    'product_id' => $product1->id,
    'type' => 'issued',
    'quantity' => 30,
    'movement_date' => now(),
    'reference_number' => 'ISS-2024-001',
    'notes' => 'Issued for maintenance use',
    'user_id' => $inventoryPersonnel->id,
    'status' => 'pending'
]);
echo "✓ Created Issued movement: {$product1->name} (-30 units)\n";

// Create Returns movement
$returned = StockMovement::create([
    'product_id' => $product2->id,
    'type' => 'returned',
    'quantity' => 20,
    'movement_date' => now(),
    'reference_number' => 'RET-2024-001',
    'notes' => 'Unused items returned from catering',
    'user_id' => $inventoryPersonnel->id,
    'status' => 'pending'
]);
echo "✓ Created Returns movement: {$product2->name} (+20 units)\n";

// Create more movements for better visualization
$incoming2 = StockMovement::create([
    'product_id' => $product1->id,
    'type' => 'incoming',
    'quantity' => 75,
    'movement_date' => now(),
    'reference_number' => 'PO-2024-002',
    'notes' => 'Second delivery batch',
    'user_id' => $inventoryPersonnel->id,
    'status' => 'pending'
]);
echo "✓ Created Incoming movement: {$product1->name} (+75 units)\n";

$transfer2 = StockMovement::create([
    'product_id' => $product2->id,
    'type' => 'transfer_to_catering',
    'quantity' => 40,
    'movement_date' => now(),
    'reference_number' => 'TRF-2024-002',
    'notes' => 'Transfer for upcoming flights',
    'user_id' => $inventoryPersonnel->id,
    'status' => 'pending'
]);
echo "✓ Created Transfer movement: {$product2->name} (40 units to catering)\n";

echo "\n✅ Done! Created 6 pending stock movements.\n";
echo "Now login as Inventory Supervisor and visit: /inventory-supervisor/approvals/movements\n";
