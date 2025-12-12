<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\StockMovement;

echo "=== Checking Beef Salad Stock Movements ===\n\n";

$movements = StockMovement::with(['product', 'user'])
    ->whereHas('product', function($q) {
        $q->where('name', 'LIKE', '%Beef Salad%');
    })
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get();

if ($movements->isEmpty()) {
    echo "❌ No stock movements found for Beef Salad\n";
} else {
    foreach ($movements as $stock) {
        echo "Movement ID: {$stock->id}\n";
        echo "Product: {$stock->product->name}\n";
        echo "Status: {$stock->status}\n";
        echo "Type: {$stock->type}\n";
        echo "Quantity: {$stock->quantity}\n";
        echo "Notes: {$stock->notes}\n";
        echo "Created by: " . ($stock->user->name ?? 'N/A') . "\n";
        echo "Created at: {$stock->created_at}\n";
        echo "---\n\n";
    }
}

// Check pending stock movements
echo "\n=== All Pending Stock Movements ===\n\n";
$pending = StockMovement::with(['product', 'user'])
    ->where('status', 'pending')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Total pending: " . $pending->count() . "\n\n";

foreach ($pending as $stock) {
    echo "ID: {$stock->id} | Product: {$stock->product->name} | Type: {$stock->type} | Qty: {$stock->quantity} | Notes: {$stock->notes}\n";
}
