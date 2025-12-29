<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\StockMovement;

echo "=== Checking Stock Movements for Drinks ===\n\n";

// Check Coca Cola
$cocaCola = Product::where('sku', 'C001')->first();
if ($cocaCola) {
    echo "Product: {$cocaCola->name} (SKU: {$cocaCola->sku}, ID: {$cocaCola->id})\n";
    
    $allMovements = StockMovement::where('product_id', $cocaCola->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    echo "Total movements: " . $allMovements->count() . "\n";
    
    if ($allMovements->count() > 0) {
        echo "Recent movements:\n";
        foreach ($allMovements->take(5) as $movement) {
            echo "  - {$movement->type} | Qty: {$movement->quantity} | Date: {$movement->created_at->format('Y-m-d H:i')} | Age: {$movement->created_at->diffForHumans()}\n";
        }
    }
    
    $issued30Days = StockMovement::where('product_id', $cocaCola->id)
        ->where('type', 'issued')
        ->where('created_at', '>=', now()->subDays(30))
        ->count();
    
    echo "Issued movements in last 30 days: {$issued30Days}\n";
    echo "\n";
}

// Check Maji
echo "---\n\n";
$maji = Product::where('sku', 't-67')->first();
if ($maji) {
    echo "Product: {$maji->name} (SKU: {$maji->sku}, ID: {$maji->id})\n";
    
    $allMovements = StockMovement::where('product_id', $maji->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    echo "Total movements: " . $allMovements->count() . "\n";
    
    if ($allMovements->count() > 0) {
        echo "Recent movements:\n";
        foreach ($allMovements->take(5) as $movement) {
            echo "  - {$movement->type} | Qty: {$movement->quantity} | Date: {$movement->created_at->format('Y-m-d H:i')} | Age: {$movement->created_at->diffForHumans()}\n";
        }
    }
    
    $issued30Days = StockMovement::where('product_id', $maji->id)
        ->where('type', 'issued')
        ->where('created_at', '>=', now()->subDays(30))
        ->count();
    
    echo "Issued movements in last 30 days: {$issued30Days}\n";
}

echo "\n=== All Stock Movements Summary ===\n";
$totalMovements = StockMovement::count();
echo "Total movements in database: {$totalMovements}\n";

$issued = StockMovement::where('type', 'issued')->count();
$incoming = StockMovement::where('type', 'incoming')->count();
$returned = StockMovement::where('type', 'returned')->count();

echo "Breakdown:\n";
echo "  - Issued: {$issued}\n";
echo "  - Incoming: {$incoming}\n";
echo "  - Returned: {$returned}\n";
