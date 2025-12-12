<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Recent Stock Movements Status Check ===\n\n";

$recentStock = \App\Models\StockMovement::with(['product', 'user', 'approvedBy'])
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();

if ($recentStock->isEmpty()) {
    echo "❌ No stock movements found!\n";
    exit;
}

echo "Found {$recentStock->count()} recent stock movements:\n\n";

foreach ($recentStock as $stock) {
    echo "📦 ID: {$stock->id}\n";
    echo "   Reference: {$stock->reference_number}\n";
    echo "   Product: " . ($stock->product->name ?? 'N/A') . "\n";
    echo "   Type: {$stock->type}\n";
    echo "   Quantity: {$stock->quantity}\n";
    echo "   Status: {$stock->status}\n";
    echo "   Requested By: " . ($stock->user->name ?? 'System') . "\n";
    echo "   Approved By: " . ($stock->approvedBy->name ?? 'Not approved yet') . "\n";
    echo "   Created: {$stock->created_at->format('Y-m-d H:i:s')}\n";
    echo "   ---\n";
}

echo "\n=== Status Distribution ===\n";
$statusCounts = \App\Models\StockMovement::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->get();

foreach ($statusCounts as $stat) {
    echo "{$stat->status}: {$stat->count}\n";
}
