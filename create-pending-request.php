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

echo "Creating pending request for Inventory Personnel to review...\n\n";

// Get Catering Staff user
$staff = User::where('email', 'staff@example.test')->first();
if (!$staff) {
    echo "❌ Catering Staff user not found!\n";
    exit(1);
}

// Get available product
$product = Product::where('status', 'approved')->where('quantity_in_stock', '>', 20)->first();
if (!$product) {
    echo "❌ No products available!\n";
    exit(1);
}

// Get or create a future flight
$flight = Flight::where('departure_time', '>', now())->first();
if (!$flight) {
    $flight = Flight::create([
        'flight_number' => 'DF500',
        'airline' => 'FlySafe Airlines',
        'origin' => 'DAR',
        'destination' => 'NBO',
        'departure_time' => now()->addDays(3),
        'arrival_time' => now()->addDays(3)->addHours(2),
        'aircraft_type' => 'Boeing 737',
        'passenger_count' => 150,
        'status' => 'scheduled'
    ]);
    echo "✓ Created new flight: {$flight->flight_number}\n";
}

// Create request with pending_inventory status
$request = Request::create([
    'requester_id' => $staff->id,
    'flight_id' => $flight->id,
    'requested_date' => now(),
    'required_date' => $flight->departure_time->subHours(4),
    'status' => 'pending_inventory',  // First stage - waiting for Inventory Personnel
    'priority' => 'normal',
    'notes' => 'Urgent request for upcoming flight ' . $flight->flight_number
]);

// Add items to request
RequestItem::create([
    'request_id' => $request->id,
    'product_id' => $product->id,
    'quantity_requested' => 15
]);

echo "✓ Request #{$request->id} created (Status: pending_inventory)\n";
echo "  Flight: {$flight->flight_number} ({$flight->origin} → {$flight->destination})\n";
echo "  Product: {$product->name} (15 units)\n";
echo "  Requester: {$staff->name}\n\n";

echo "========================================\n";
echo "✅ PENDING REQUEST CREATED!\n";
echo "========================================\n\n";

echo "Now login as Inventory Personnel:\n";
echo "  Email:    inventory@example.test\n";
echo "  Password: password\n\n";

echo "Navigate to: Dashboard → Click 'Pending Requests' button\n";
echo "You should see Request #{$request->id} ready for review!\n\n";

echo "Workflow:\n";
echo "  1. Inventory Personnel reviews request\n";
echo "  2. Click 'Forward to Supervisor' button\n";
echo "  3. Status changes to 'pending_supervisor'\n";
echo "  4. Supervisor can then approve\n";
