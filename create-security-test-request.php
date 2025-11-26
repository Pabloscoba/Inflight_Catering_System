<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Flight;
use App\Models\Request;
use App\Models\RequestItem;

echo "Creating test request for Security Staff authentication...\n\n";

// Get users for each role
$cateringStaff = User::where('email', 'staff@example.test')->first();
$inventoryPersonnel = User::where('email', 'inventory@example.test')->first();
$supervisor = User::where('email', 'supervisor@example.test')->first();

if (!$cateringStaff || !$inventoryPersonnel || !$supervisor) {
    echo "❌ Required users not found!\n";
    exit(1);
}

// Get product
$product = Product::where('status', 'approved')->where('quantity_in_stock', '>', 20)->first();
if (!$product) {
    echo "❌ No products available!\n";
    exit(1);
}

// Get or create a future flight
$flight = Flight::where('departure_time', '>', now())->first();
if (!$flight) {
    $flight = Flight::create([
        'flight_number' => 'DF700',
        'airline' => 'Tanzania Airlines',
        'origin' => 'DAR',
        'destination' => 'KGL',
        'departure_time' => now()->addDays(2),
        'arrival_time' => now()->addDays(2)->addHours(3),
        'aircraft_type' => 'Airbus A320',
        'passenger_count' => 180,
        'status' => 'scheduled'
    ]);
    echo "✓ Created new flight: {$flight->flight_number}\n";
} else {
    echo "✓ Using existing flight: {$flight->flight_number}\n";
}

echo "\n========== CREATING REQUEST ==========\n";

// 1. Catering Staff creates request
$request = Request::create([
    'requester_id' => $cateringStaff->id,
    'flight_id' => $flight->id,
    'requested_date' => now(),
    'required_date' => $flight->departure_time->subHours(4),
    'status' => 'pending_inventory',
    'priority' => 'high',
    'notes' => 'Urgent catering request for international flight'
]);

RequestItem::create([
    'request_id' => $request->id,
    'product_id' => $product->id,
    'quantity_requested' => 25
]);

echo "1. ✓ Catering Staff created Request #{$request->id}\n";
echo "   Product: {$product->name} (25 units)\n";
echo "   Status: pending_inventory\n\n";

// 2. Inventory Personnel forwards to Supervisor
$request->update(['status' => 'pending_supervisor']);
echo "2. ✓ Inventory Personnel forwarded to Supervisor\n";
echo "   Status: pending_supervisor\n\n";

// 3. Supervisor approves
$request->update([
    'status' => 'supervisor_approved',
    'approved_by' => $supervisor->id,
    'approved_date' => now()
]);
echo "3. ✓ Supervisor approved request\n";
echo "   Status: supervisor_approved\n\n";

// 4. Inventory Personnel forwards to Security
$request->update(['status' => 'sent_to_security']);
echo "4. ✓ Inventory Personnel forwarded to Security Staff\n";
echo "   Status: sent_to_security\n\n";

echo "========================================\n";
echo "✅ REQUEST READY FOR SECURITY CHECK!\n";
echo "========================================\n\n";

echo "Request Details:\n";
echo "  Request ID:  #" . $request->id . "\n";
echo "  Flight:      {$flight->flight_number} ({$flight->origin} → {$flight->destination})\n";
echo "  Departure:   " . $flight->departure_time->format('M d, Y H:i') . "\n";
echo "  Product:     {$product->name} (25 units)\n";
echo "  Current Status: sent_to_security\n\n";

echo "Now login as Security Staff:\n";
echo "  Email:    security@inflightcatering.com\n";
echo "  Password: Security@123\n\n";

echo "Navigate to: Dashboard → 'Orders Pending Security Check' section\n";
echo "You should see Request #{$request->id} ready for authentication!\n\n";

echo "Next Steps:\n";
echo "  1. Click 'Awaiting Authentication' button\n";
echo "  2. Click 'Verify' on Request #{$request->id}\n";
echo "  3. Authenticate and issue stock\n";
echo "  4. Request moves to Catering Incharge for final approval\n";
