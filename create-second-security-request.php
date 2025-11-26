<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create Request #8 at sent_to_security status for Security Staff to authenticate

// Find or create products
$product1 = App\Models\Product::where('name', 'Test Beef Meal')->first();
if (!$product1) {
    $category = App\Models\Category::firstOrCreate(['name' => 'Meals']);
    $product1 = App\Models\Product::create([
        'name' => 'Test Beef Meal',
        'sku' => 'MEAL-BEEF-002',
        'category_id' => $category->id,
        'quantity_in_stock' => 200,
        'min_stock_level' => 15,
        'is_active' => true,
    ]);
}

$product2 = App\Models\Product::where('name', 'Orange Juice')->first();
if (!$product2) {
    $category = App\Models\Category::firstOrCreate(['name' => 'Beverages']);
    $product2 = App\Models\Product::create([
        'name' => 'Orange Juice',
        'sku' => 'BEV-OJ-001',
        'category_id' => $category->id,
        'quantity_in_stock' => 150,
        'min_stock_level' => 20,
        'is_active' => true,
    ]);
}

// Find users
$cateringStaff = App\Models\User::role('Catering Staff')->first();
$inventoryPersonnel = App\Models\User::role('Inventory Personnel')->first();
$supervisor = App\Models\User::role('Inventory Supervisor')->first();

// Find or create test flight
$flight = App\Models\Flight::where('flight_number', 'AF505')->first();
if (!$flight) {
    $flight = App\Models\Flight::create([
        'flight_number' => 'AF505',
        'airline' => 'Air France',
        'origin' => 'DAR',
        'destination' => 'NBO',
        'departure_time' => now()->addDays(2)->setTime(14, 30),
        'arrival_time' => now()->addDays(2)->setTime(16, 15),
        'passenger_count' => 180,
        'status' => 'scheduled',
    ]);
}

// Create Request #8
$request = App\Models\Request::create([
    'flight_id' => $flight->id,
    'user_id' => $cateringStaff->id,
    'requested_date' => now()->subHours(3),
    'status' => 'sent_to_security',
]);

// Add request items
App\Models\RequestItem::create([
    'request_id' => $request->id,
    'product_id' => $product1->id,
    'quantity_requested' => 30,
    'quantity_approved' => 30,
]);

App\Models\RequestItem::create([
    'request_id' => $request->id,
    'product_id' => $product2->id,
    'quantity_requested' => 45,
    'quantity_approved' => 45,
]);

echo "✅ Request #{$request->id} created successfully!\n\n";
echo "Details:\n";
echo "--------\n";
echo "Flight: {$flight->flight_number} ({$flight->origin} → {$flight->destination})\n";
echo "Departure: " . $flight->departure_time->format('M d, Y H:i A') . "\n";
echo "Status: {$request->status}\n";
echo "Items:\n";
echo "  - {$product1->name}: 30 units\n";
echo "  - {$product2->name}: 45 units\n";
echo "\nReady for Security Staff authentication!\n";
