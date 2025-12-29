<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\RequestItem;
use App\Models\Flight;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

echo "Creating test requests for Security Staff verification...\n\n";

// Get a catering staff user
$cateringStaff = User::whereHas('roles', fn($q) => $q->where('name', 'Catering Staff'))->first();
if (!$cateringStaff) {
    echo "Error: No Catering Staff user found\n";
    exit(1);
}

// Create 3 test flights with different departure times
$flights = [
    [
        'flight_number' => 'SEC-' . rand(100, 999),
        'aircraft_type' => 'Boeing 737',
        'departure_time' => now()->addHours(4), // HIGH RISK - departing in 4 hours
        'items_count' => 25, // HIGH RISK - many items
        'total_quantity' => 120, // HIGH RISK - high quantity
    ],
    [
        'flight_number' => 'SEC-' . rand(100, 999),
        'aircraft_type' => 'Airbus A320',
        'departure_time' => now()->addHours(8), // MEDIUM RISK
        'items_count' => 15, // normal
        'total_quantity' => 80, // normal
    ],
    [
        'flight_number' => 'SEC-' . rand(100, 999),
        'aircraft_type' => 'Boeing 787',
        'departure_time' => now()->addHours(24), // LOW RISK - departure far away
        'items_count' => 5, // few items
        'total_quantity' => 30, // low quantity
    ],
];

$products = Product::take(30)->get();
if ($products->count() == 0) {
    echo "Error: No products found\n";
    exit(1);
}

foreach ($flights as $index => $flightData) {
    // Create flight
    $flight = Flight::create([
        'flight_number' => $flightData['flight_number'],
        'airline' => 'Tanzania Airlines',
        'aircraft_type' => $flightData['aircraft_type'],
        'departure_time' => $flightData['departure_time'],
        'arrival_time' => $flightData['departure_time']->copy()->addHours(3),
        'origin' => 'DAR',
        'destination' => 'JRO',
        'route' => 'DAR-JRO-DAR',
        'status' => 'scheduled',
    ]);

    // Create request at catering_final_approved status
    $request = RequestModel::create([
        'requester_id' => $cateringStaff->id,
        'flight_id' => $flight->id,
        'status' => 'catering_final_approved',
        'priority' => $index == 0 ? 'high' : 'normal',
        'requested_date' => now()->subHours(2),
        'notes' => 'Test request for security verification - ' . ($index + 1),
        'created_at' => now()->subHours(2),
        'updated_at' => now()->subMinutes(30),
    ]);

    // Add items
    $itemCount = $flightData['items_count'];
    $avgQuantityPerItem = (int)($flightData['total_quantity'] / $itemCount);
    
    for ($i = 0; $i < $itemCount; $i++) {
        $product = $products->random();
        $qty = $avgQuantityPerItem + rand(-5, 5);
        RequestItem::create([
            'request_id' => $request->id,
            'product_id' => $product->id,
            'quantity' => $qty,
            'quantity_requested' => $qty,
            'quantity_approved' => $qty,
            'unit' => 'pieces',
        ]);
    }

    $riskLevel = $index == 0 ? 'HIGH' : ($index == 1 ? 'MEDIUM' : 'LOW');
    echo "✓ Created Request #{$request->id} for Flight {$flightData['flight_number']}\n";
    echo "  Status: catering_final_approved\n";
    echo "  Departure: {$flightData['departure_time']->format('Y-m-d H:i')}\n";
    echo "  Items: {$itemCount} | Total Qty: ~{$flightData['total_quantity']}\n";
    echo "  Expected Risk Level: {$riskLevel}\n\n";
}

echo "\n✅ Created 3 test requests with different risk levels!\n";
echo "Now refresh the Security Staff dashboard to see:\n";
echo "  • Risk-coded request cards (HIGH/MEDIUM/LOW)\n";
echo "  • Verification summary with risk breakdown\n";
echo "  • Assessment checklist guide\n";
echo "  • Urgent flights indicator\n";
