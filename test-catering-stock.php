<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

echo "=== Catering Mini Stock System Test ===\n\n";

// 1. Show sample product stock levels
echo "1. Sample Product Stock Status:\n";
echo str_repeat("-", 60) . "\n";

$products = Product::select('id', 'name', 'sku', 'quantity_in_stock', 'catering_stock', 'catering_reorder_level')
    ->take(5)
    ->get();

foreach ($products as $product) {
    echo "Product: {$product->name} (SKU: {$product->sku})\n";
    echo "  Main Inventory: {$product->quantity_in_stock}\n";
    echo "  Catering Stock: {$product->catering_stock}\n";
    echo "  Catering Reorder Level: {$product->catering_reorder_level}\n";
    
    if ($product->isCateringStockOut()) {
        echo "  ⚠️  STATUS: CATERING STOCK OUT\n";
    } elseif ($product->isCateringStockLow()) {
        echo "  ⚠️  STATUS: CATERING STOCK LOW\n";
    } else {
        echo "  ✓ STATUS: OK\n";
    }
    echo "\n";
}

// 2. Show pending transfers to catering
echo "\n2. Pending Transfers to Catering:\n";
echo str_repeat("-", 60) . "\n";

$pendingTransfers = StockMovement::where('type', 'transfer_to_catering')
    ->where('status', 'pending')
    ->with(['product', 'user'])
    ->get();

if ($pendingTransfers->isEmpty()) {
    echo "No pending transfers.\n";
} else {
    foreach ($pendingTransfers as $transfer) {
        echo "Reference: {$transfer->reference_number}\n";
        echo "Product: {$transfer->product->name}\n";
        echo "Quantity: {$transfer->quantity}\n";
        echo "Requested by: {$transfer->user->name}\n";
        echo "Date: {$transfer->movement_date->format('d M Y')}\n";
        echo "Status: {$transfer->status}\n";
        echo "Notes: {$transfer->notes}\n";
        echo "\n";
    }
}

// 3. Show approved transfers to catering
echo "\n3. Approved Transfers to Catering:\n";
echo str_repeat("-", 60) . "\n";

$approvedTransfers = StockMovement::where('type', 'transfer_to_catering')
    ->where('status', 'approved')
    ->with(['product', 'user'])
    ->latest()
    ->take(5)
    ->get();

if ($approvedTransfers->isEmpty()) {
    echo "No approved transfers yet.\n";
} else {
    foreach ($approvedTransfers as $transfer) {
        echo "Reference: {$transfer->reference_number}\n";
        echo "Product: {$transfer->product->name}\n";
        echo "Quantity Transferred: {$transfer->quantity}\n";
        echo "Requested by: {$transfer->user->name}\n";
        echo "Approved: {$transfer->approved_at->format('d M Y H:i')}\n";
        echo "\n";
    }
}

// 4. Products available for Catering Staff (catering_stock > 0)
echo "\n4. Products Available for Catering Staff Requests:\n";
echo str_repeat("-", 60) . "\n";

$cateringProducts = Product::where('catering_stock', '>', 0)
    ->select('name', 'sku', 'catering_stock')
    ->get();

if ($cateringProducts->isEmpty()) {
    echo "No products available in catering mini stock.\n";
    echo "Inventory Personnel needs to transfer stock from main inventory.\n";
} else {
    echo "Total products in catering stock: " . $cateringProducts->count() . "\n\n";
    foreach ($cateringProducts as $product) {
        echo "{$product->name} (SKU: {$product->sku})\n";
        echo "  Available: {$product->catering_stock}\n";
        echo "\n";
    }
}

// 5. Transfer Statistics
echo "\n5. Transfer Statistics:\n";
echo str_repeat("-", 60) . "\n";

$totalTransfers = StockMovement::where('type', 'transfer_to_catering')->count();
$pendingCount = StockMovement::where('type', 'transfer_to_catering')->where('status', 'pending')->count();
$approvedCount = StockMovement::where('type', 'transfer_to_catering')->where('status', 'approved')->count();
$rejectedCount = StockMovement::where('type', 'transfer_to_catering')->where('status', 'rejected')->count();

echo "Total Transfer Requests: {$totalTransfers}\n";
echo "Pending Approval: {$pendingCount}\n";
echo "Approved: {$approvedCount}\n";
echo "Rejected: {$rejectedCount}\n";

// 6. Stock Distribution Summary
echo "\n6. Stock Distribution Summary:\n";
echo str_repeat("-", 60) . "\n";

$totalMainStock = Product::sum('quantity_in_stock');
$totalCateringStock = Product::sum('catering_stock');
$productsInCatering = Product::where('catering_stock', '>', 0)->count();
$totalProducts = Product::count();

echo "Total Products: {$totalProducts}\n";
echo "Products in Catering Stock: {$productsInCatering}\n";
echo "Total Main Inventory: {$totalMainStock} units\n";
echo "Total Catering Mini Stock: {$totalCateringStock} units\n";
echo "Distribution Ratio: " . ($totalMainStock + $totalCateringStock > 0 ? 
    round(($totalCateringStock / ($totalMainStock + $totalCateringStock)) * 100, 1) : 0) . "% in catering\n";

echo "\n=== Test Complete ===\n";
