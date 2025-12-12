<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request as RequestModel;

echo "=== Checking Request #12 ===\n\n";

$request = RequestModel::with(['flight', 'requester', 'items.product'])
    ->find(12);

if (!$request) {
    echo "❌ Request #12 not found\n";
} else {
    echo "Request ID: {$request->id}\n";
    echo "Status: {$request->status}\n";
    echo "Flight: " . ($request->flight->flight_number ?? 'N/A') . "\n";
    echo "Route: " . ($request->flight->origin ?? 'N/A') . " → " . ($request->flight->destination ?? 'N/A') . "\n";
    echo "Requester: " . ($request->requester->name ?? 'N/A') . "\n";
    echo "Created at: {$request->created_at}\n";
    echo "\nItems:\n";
    foreach ($request->items as $item) {
        echo "  - {$item->product->name}: {$item->quantity_requested}\n";
    }
}

echo "\n\n=== All Pending Supervisor Requests ===\n\n";

$pendingRequests = RequestModel::with(['flight', 'requester', 'items.product'])
    ->where('status', 'pending_supervisor')
    ->latest()
    ->get();

echo "Total pending supervisor approval: " . $pendingRequests->count() . "\n\n";

foreach ($pendingRequests as $req) {
    echo "Request #{$req->id} | Flight: " . ($req->flight->flight_number ?? 'N/A') . " | Status: {$req->status} | Items: " . $req->items->count() . "\n";
    foreach ($req->items as $item) {
        echo "  - {$item->product->name}: {$item->quantity_requested}\n";
    }
    echo "\n";
}
