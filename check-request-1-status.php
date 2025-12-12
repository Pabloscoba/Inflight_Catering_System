<?php
// Quick check of Request #1 status for served form testing

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$request = App\Models\Request::with(['flight', 'items.product'])->find(1);

if (!$request) {
    echo "❌ Request #1 not found!\n";
    exit;
}

echo "=== Request #1 Status ===\n";
echo "ID: {$request->id}\n";
echo "Status: {$request->status}\n";
echo "Flight: {$request->flight->flight_number} ({$request->flight->departure_city} → {$request->flight->arrival_city})\n";
echo "Departure: {$request->flight->departure_time}\n";
echo "Capacity: {$request->flight->passenger_capacity} passengers\n";
echo "\n=== Request Items ===\n";
foreach ($request->items as $item) {
    echo "- {$item->product->name}: {$item->quantity_approved} approved\n";
}

echo "\n=== Can Access Served Form? ===\n";
$validStatuses = ['loaded', 'flight_received', 'delivered'];
if (in_array($request->status, $validStatuses)) {
    echo "✅ YES - Status '{$request->status}' is valid for served form\n";
    echo "Access at: http://127.0.0.1:8000/cabin-crew/requests/{$request->id}/served-form\n";
} else {
    echo "❌ NO - Current status '{$request->status}' not ready. Need: " . implode(', ', $validStatuses) . "\n";
}
