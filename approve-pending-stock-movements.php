<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Updating Pending Stock Movements to Approved ===\n\n";

$pendingMovements = \App\Models\StockMovement::where('status', 'pending')
    ->whereIn('type', ['issued'])
    ->get();

if ($pendingMovements->isEmpty()) {
    echo "✅ No pending stock movements to update!\n";
    exit;
}

echo "Found {$pendingMovements->count()} pending issued stock movements to approve:\n\n";

foreach ($pendingMovements as $movement) {
    echo "📦 Stock Movement ID: {$movement->id}\n";
    echo "   Reference: {$movement->reference_number}\n";
    echo "   Product: " . ($movement->product->name ?? 'N/A') . "\n";
    echo "   Type: {$movement->type}\n";
    echo "   Quantity: {$movement->quantity}\n";
    echo "   Current Status: {$movement->status}\n";
    
    // Get the user who created this movement (usually Security Staff)
    $approver = $movement->user;
    
    $movement->update([
        'status' => 'approved',
        'approved_by' => $approver->id ?? 1, // Use creator or Admin
        'approved_at' => $movement->created_at, // Approve with same timestamp as creation
    ]);
    
    echo "   ✅ Updated to: approved\n";
    echo "   Approved By: " . ($approver->name ?? 'Admin') . "\n";
    echo "   ---\n";
}

echo "\n✅ All pending issued stock movements have been approved!\n";
