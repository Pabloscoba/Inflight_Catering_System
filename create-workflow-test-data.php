<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\CateringStock;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "📦 Creating Complete Workflow Test Data...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get users
$inventoryPersonnel = User::whereHas('roles', function($q) {
    $q->where('name', 'Inventory Personnel');
})->first();

$inventorySupervisor = User::whereHas('roles', function($q) {
    $q->where('name', 'Inventory Supervisor');
})->first();

$cateringIncharge = User::whereHas('roles', function($q) {
    $q->where('name', 'Catering Incharge');
})->first();

if (!$inventoryPersonnel || !$inventorySupervisor || !$cateringIncharge) {
    echo "❌ Required users not found!\n";
    exit(1);
}

$category = Category::first();
if (!$category) {
    echo "❌ No category found! Run: php artisan db:seed --class=CategorySeeder\n";
    exit(1);
}

// ============================================
// STEP 1: Inventory Personnel creates products (PENDING)
// ============================================
echo "STEP 1: Inventory Personnel Creates Products\n";
echo "─────────────────────────────────────────────\n";

$productsData = [
    [
        'name' => 'Chicken Meal',
        'sku' => 'MEAL-001',
        'description' => 'Grilled chicken with vegetables',
        'unit_price' => 12000,
        'quantity_in_stock' => 0, // Will be updated when supervisor approves incoming stock
        'reorder_level' => 50,
    ],
    [
        'name' => 'Vegetarian Pasta',
        'sku' => 'MEAL-002',
        'description' => 'Pasta with mixed vegetables',
        'unit_price' => 10000,
        'quantity_in_stock' => 0,
        'reorder_level' => 40,
    ],
    [
        'name' => 'Orange Juice 500ml',
        'sku' => 'BEV-001',
        'description' => 'Fresh orange juice',
        'unit_price' => 3500,
        'quantity_in_stock' => 0,
        'reorder_level' => 100,
    ],
];

$createdProducts = [];
foreach ($productsData as $data) {
    $product = Product::create([
        'name' => $data['name'],
        'sku' => $data['sku'],
        'category_id' => $category->id,
        'description' => $data['description'],
        'currency' => 'TZS',
        'unit_price' => $data['unit_price'],
        'quantity_in_stock' => $data['quantity_in_stock'],
        'reorder_level' => $data['reorder_level'],
        'unit_of_measure' => 'units',
        'is_active' => true,
        'status' => 'pending', // Awaiting supervisor approval
    ]);
    $createdProducts[] = $product;
    echo "  ✓ Created: {$product->name} (SKU: {$product->sku}) - Status: PENDING\n";
}

echo "\n";

// ============================================
// STEP 2: Inventory Supervisor APPROVES Products
// ============================================
echo "STEP 2: Inventory Supervisor Approves Products\n";
echo "────────────────────────────────────────────────\n";

foreach ($createdProducts as $product) {
    $product->update([
        'status' => 'approved',
        'approved_by' => $inventorySupervisor->id,
        'approved_at' => now(),
    ]);
    echo "  ✓ Approved: {$product->name} by Supervisor\n";
}

echo "\n";

// ============================================
// STEP 3: Inventory Personnel records INCOMING stock (PENDING)
// ============================================
echo "STEP 3: Inventory Personnel Records Incoming Stock\n";
echo "─────────────────────────────────────────────────────\n";

$incomingStockData = [
    ['product' => $createdProducts[0], 'quantity' => 100, 'ref' => 'PO-2025-001'],
    ['product' => $createdProducts[1], 'quantity' => 80, 'ref' => 'PO-2025-002'],
    ['product' => $createdProducts[2], 'quantity' => 200, 'ref' => 'PO-2025-003'],
];

$stockMovements = [];
foreach ($incomingStockData as $data) {
    $movement = StockMovement::create([
        'type' => 'incoming',
        'product_id' => $data['product']->id,
        'quantity' => $data['quantity'],
        'reference_number' => $data['ref'],
        'notes' => 'New stock from supplier',
        'user_id' => $inventoryPersonnel->id,
        'movement_date' => now(),
        'status' => 'pending', // Awaiting supervisor approval
    ]);
    $stockMovements[] = $movement;
    echo "  ✓ Recorded: {$data['quantity']} units of {$data['product']->name} - Status: PENDING\n";
}

echo "\n";

// ============================================
// STEP 4: Inventory Supervisor APPROVES Stock Movements
// ============================================
echo "STEP 4: Inventory Supervisor Approves Stock Movements\n";
echo "────────────────────────────────────────────────────────\n";

foreach ($stockMovements as $movement) {
    DB::transaction(function () use ($movement, $inventorySupervisor) {
        // Approve the movement
        $movement->update([
            'status' => 'approved',
            'approved_by' => $inventorySupervisor->id,
            'approved_at' => now(),
        ]);
        
        // Update product stock
        $product = $movement->product;
        $product->increment('quantity_in_stock', $movement->quantity);
        
        echo "  ✓ Approved: {$movement->quantity} units of {$product->name}\n";
        echo "    → Stock Updated: {$product->name} now has {$product->quantity_in_stock} units\n";
    });
}

echo "\n";

// ============================================
// STEP 5: Inventory Personnel TRANSFERS to Catering (creates catering_stock PENDING)
// ============================================
echo "STEP 5: Inventory Personnel Transfers to Catering Incharge\n";
echo "────────────────────────────────────────────────────────────\n";

$transfersData = [
    ['product' => $createdProducts[0], 'quantity' => 60, 'ref' => 'TRF-CAT-001'],
    ['product' => $createdProducts[1], 'quantity' => 50, 'ref' => 'TRF-CAT-002'],
    ['product' => $createdProducts[2], 'quantity' => 150, 'ref' => 'TRF-CAT-003'],
];

$cateringStockRecords = [];
foreach ($transfersData as $data) {
    $cateringStock = CateringStock::create([
        'product_id' => $data['product']->id,
        'quantity_received' => $data['quantity'],
        'quantity_available' => 0, // Will be set when Catering Incharge approves
        'reference_number' => $data['ref'],
        'notes' => 'Transfer from main inventory to catering',
        'received_by' => $inventoryPersonnel->id,
        'received_date' => now(),
        'status' => 'pending', // Awaiting Catering Incharge approval
    ]);
    $cateringStockRecords[] = $cateringStock;
    echo "  ✓ Transferred: {$data['quantity']} units of {$data['product']->name} - Status: PENDING\n";
    echo "    → Awaiting Catering Incharge approval\n";
}

echo "\n";

// ============================================
// STEP 6: Catering Incharge APPROVES Receipts
// ============================================
echo "STEP 6: Catering Incharge Approves Product Receipts\n";
echo "──────────────────────────────────────────────────────\n";

foreach ($cateringStockRecords as $stock) {
    $stock->update([
        'status' => 'approved',
        'catering_incharge_id' => $cateringIncharge->id,
        'approved_date' => now(),
        'quantity_available' => $stock->quantity_received, // Now available to Catering Staff
    ]);
    echo "  ✓ Approved: {$stock->quantity_received} units of {$stock->product->name}\n";
    echo "    → Now AVAILABLE to Catering Staff\n";
}

echo "\n";

// ============================================
// CREATE SOME PENDING ITEMS (for dynamic display)
// ============================================
echo "STEP 7: Creating Additional PENDING Items for Dynamic Display\n";
echo "───────────────────────────────────────────────────────────────\n";

// Create 2 more pending products
$pendingProducts = [
    [
        'name' => 'Beef Steak',
        'sku' => 'MEAL-003',
        'description' => 'Grilled beef steak with sauce',
        'unit_price' => 18000,
    ],
    [
        'name' => 'Fish Fillet',
        'sku' => 'MEAL-004',
        'description' => 'Grilled fish fillet',
        'unit_price' => 15000,
    ],
];

foreach ($pendingProducts as $data) {
    $product = Product::create([
        'name' => $data['name'],
        'sku' => $data['sku'],
        'category_id' => $category->id,
        'description' => $data['description'],
        'currency' => 'TZS',
        'unit_price' => $data['unit_price'],
        'quantity_in_stock' => 0,
        'reorder_level' => 30,
        'unit_of_measure' => 'units',
        'is_active' => true,
        'status' => 'pending',
    ]);
    echo "  ✓ Created PENDING product: {$product->name}\n";
}

// Create 2 pending stock movements for already approved products
$pendingMovement1 = StockMovement::create([
    'type' => 'incoming',
    'product_id' => $createdProducts[0]->id,
    'quantity' => 50,
    'reference_number' => 'PO-2025-004',
    'notes' => 'Additional stock delivery',
    'user_id' => $inventoryPersonnel->id,
    'movement_date' => now(),
    'status' => 'pending',
]);
echo "  ✓ Created PENDING incoming: 50 units of {$createdProducts[0]->name}\n";

$pendingMovement2 = StockMovement::create([
    'type' => 'issued',
    'product_id' => $createdProducts[1]->id,
    'quantity' => 10,
    'reference_number' => 'ISS-2025-001',
    'notes' => 'Issued for testing',
    'user_id' => $inventoryPersonnel->id,
    'movement_date' => now(),
    'status' => 'pending',
]);
echo "  ✓ Created PENDING issue: 10 units of {$createdProducts[1]->name}\n";

// Create 2 pending catering receipts
$pendingReceipt1 = CateringStock::create([
    'product_id' => $createdProducts[0]->id,
    'quantity_received' => 30,
    'quantity_available' => 0,
    'reference_number' => 'TRF-CAT-004',
    'notes' => 'Additional catering stock',
    'received_by' => $inventoryPersonnel->id,
    'received_date' => now(),
    'status' => 'pending',
]);
echo "  ✓ Created PENDING catering receipt: 30 units of {$createdProducts[0]->name}\n";

echo "\n";

// ============================================
// SUMMARY
// ============================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Complete Workflow Test Data Created!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "📊 SUMMARY:\n";
echo "─────────────────────────────────────────\n";

// Inventory Supervisor Dashboard
$supervisorPendingProducts = Product::where('status', 'pending')->count();
$supervisorPendingMovements = StockMovement::where('status', 'pending')->count();
echo "🔵 INVENTORY SUPERVISOR:\n";
echo "   • Pending Products: {$supervisorPendingProducts}\n";
echo "   • Pending Stock Movements: {$supervisorPendingMovements}\n";
echo "   • Approved Products: " . Product::where('status', 'approved')->count() . "\n";
echo "   • Approved Movements: " . StockMovement::where('status', 'approved')->count() . "\n\n";

// Catering Incharge Dashboard
$inchargePendingReceipts = CateringStock::where('status', 'pending')->count();
$inchargeApprovedReceipts = CateringStock::where('status', 'approved')->count();
$totalCateringStock = CateringStock::where('status', 'approved')->sum('quantity_available');
echo "🟢 CATERING INCHARGE:\n";
echo "   • Pending Product Receipts: {$inchargePendingReceipts}\n";
echo "   • Approved Receipts: {$inchargeApprovedReceipts}\n";
echo "   • Total Available Catering Stock: {$totalCateringStock} units\n\n";

// Main Inventory
echo "📦 MAIN INVENTORY:\n";
$approvedProducts = Product::where('status', 'approved')->get();
foreach ($approvedProducts as $product) {
    echo "   • {$product->name}: {$product->quantity_in_stock} units\n";
}

echo "\n";
echo "📦 CATERING STOCK (Available to Staff):\n";
$cateringStocks = CateringStock::where('status', 'approved')->with('product')->get();
foreach ($cateringStocks as $stock) {
    echo "   • {$stock->product->name}: {$stock->quantity_available} units\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🎯 FLOW DEMONSTRATION:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "1️⃣  Inventory Personnel creates products → PENDING\n";
echo "2️⃣  Inventory Supervisor approves → APPROVED\n";
echo "3️⃣  Inventory Personnel records incoming stock → PENDING\n";
echo "4️⃣  Inventory Supervisor approves → Stock UPDATED in main inventory\n";
echo "5️⃣  Inventory Personnel transfers to Catering → PENDING receipt\n";
echo "6️⃣  Catering Incharge approves receipt → Available to Catering Staff\n";
echo "7️⃣  Catering Staff can now request from available catering stock\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "✨ All dashboards will now show DYNAMIC data!\n";
echo "🔗 Test the flow by logging in as each role:\n";
echo "   → supervisor@inflightcatering.com (Inventory Supervisor)\n";
echo "   → catering@inflightcatering.com (Catering Incharge)\n";
echo "   → inventory@inflightcatering.com (Inventory Personnel)\n";
