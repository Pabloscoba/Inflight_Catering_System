<?php
// Run this from project root: php create-single-flow-test.php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Flight;
use App\Models\Request as RequestModel;
use App\Models\RequestItem;
use App\Models\StockMovement;
use App\Models\CateringStock;
use Illuminate\Support\Str;

echo "Starting single flow test...\n";

// Clean previous test data
RequestModel::where('notes', 'Test flow request')->delete();
CateringStock::where('notes', 'like', '%test%')->delete();
StockMovement::where('notes', 'like', '%test%')->delete();
Flight::whereIn('flight_number', ['DF100', 'DF201', 'DF302'])->delete();

// Helper to get or create user with role
function getOrCreateUser($email, $name, $role)
{
    $user = User::where('email', $email)->first();
    if (!$user) {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
        ]);
        echo "Created user $email\n";
    }
    if (!$user->hasRole($role)) {
        $user->assignRole($role);
        echo "Assigned role $role to $email\n";
    }
    return $user;
}

$staff = getOrCreateUser('staff@example.test', 'Catering Staff', 'Catering Staff');
$supervisor = getOrCreateUser('supervisor@example.test', 'Inventory Supervisor', 'Inventory Supervisor');
$inventory = getOrCreateUser('inventory@example.test', 'Inventory Personnel', 'Inventory Personnel');
$catering = getOrCreateUser('incharge@example.test', 'Catering Incharge', 'Catering Incharge');
$security = getOrCreateUser('security@example.test', 'Security Staff', 'Security Staff');

// Create category & product
$category = Category::first() ?? Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
$product = Product::where('sku', 'TEST-001')->first();
if (!$product) {
    $product = Product::create([
        'name' => 'Test Chicken Meal',
        'sku' => 'TEST-001',
        'category_id' => $category->id,
        'quantity_in_stock' => 500,
        'unit_price' => 5000,
        'min_stock_level' => 20,
        'is_active' => true,
        'status' => 'approved',
    ]);
    echo "Created product {$product->name}\n";
}

// Create sample flights
$flights = [
    [
        'flight_number' => 'DF100',
        'airline' => 'DemoAir',
        'origin' => 'JRO',
        'destination' => 'DAR',
        'departure_time' => now()->addDays(1)->setTime(8, 0),
        'arrival_time' => now()->addDays(1)->setTime(9, 30),
        'status' => 'scheduled',
    ],
    [
        'flight_number' => 'DF201',
        'airline' => 'DemoAir',
        'origin' => 'DAR',
        'destination' => 'ZNZ',
        'departure_time' => now()->addDays(2)->setTime(14, 0),
        'arrival_time' => now()->addDays(2)->setTime(15, 0),
        'status' => 'scheduled',
    ],
    [
        'flight_number' => 'DF302',
        'airline' => 'DemoAir',
        'origin' => 'ZNZ',
        'destination' => 'JRO',
        'departure_time' => now()->addDays(3)->setTime(10, 30),
        'arrival_time' => now()->addDays(3)->setTime(11, 30),
        'status' => 'scheduled',
    ],
];

foreach ($flights as $flightData) {
    Flight::create($flightData);
}
echo "Created " . count($flights) . " sample flights\n";

$flight = Flight::where('flight_number', 'DF100')->first();

echo "Created product {$product->name} with stock {$product->quantity_in_stock}\n";

echo "Creating request as Catering Staff...\n";
$request = RequestModel::create([
    'flight_id' => $flight->id,
    'requester_id' => $staff->id,
    'requested_date' => now()->toDateString(),
    'notes' => 'Test flow request',
    'status' => 'pending_inventory',
]);

RequestItem::create([
    'request_id' => $request->id,
    'product_id' => $product->id,
    'quantity_requested' => 10,
]);

echo "Request #{$request->id} created with 10 x {$product->name}\n";

// Inventory Personnel forwards to Supervisor
echo "Inventory Personnel forwarding to Supervisor...\n";
$request->update(['status' => 'pending_supervisor']);
echo "Status: pending_supervisor\n";

// Supervisor approves (sets quantity_approved)
echo "Supervisor approving request...\n";
$request->update(['status' => 'supervisor_approved','approved_by'=>$supervisor->id,'approved_date'=>now()]);
$ri = $request->items()->first();
$ri->update(['quantity_approved' => 10]);

echo "Supervisor approved request.\n";

// Inventory Personnel forwards to Security
echo "Inventory Personnel forwarding to Security...\n";
$request->update(['status' => 'sent_to_security']);
echo "Status: sent_to_security\n";

// Security authenticates and issues stock
echo "Security authenticating and issuing stock...\n";
foreach ($request->items as $item) {
    $qty = $item->quantity_approved ?? $item->quantity;
    StockMovement::create([
        'type' => 'issued',
        'product_id' => $item->product_id,
        'quantity' => $qty,
        'reference_number' => 'REQ-'.$request->id,
        'notes' => 'Test: Issued after security authentication',
        'user_id' => $security->id,
        'movement_date' => now(),
    ]);
    $prod = Product::find($item->product_id);
    if ($prod) {
        $prod->decrement('quantity_in_stock', $qty);
    }
}
$request->update(['status' => 'security_approved']);
echo "✓ Security approved. Main inventory now: " . Product::find($product->id)->quantity_in_stock . "\n";

// Catering Incharge approves and creates CateringStock entries
echo "Catering Incharge approving...\n";
foreach ($request->items as $item) {
    $qty = $item->quantity_approved ?? $item->quantity;
    CateringStock::create([
        'product_id' => $item->product_id,
        'quantity_received' => $qty,
        'quantity_available' => $qty,
        'reference_number' => 'REQ-'.$request->id,
        'notes' => 'Test: Approved by Catering Incharge',
        'received_by' => null,
        'catering_incharge_id' => $catering->id,
        'status' => 'approved',
        'received_date' => now(),
        'approved_date' => now(),
    ]);
}
$request->update(['status'=>'catering_approved','approved_by'=>$catering->id,'approved_date'=>now()]);
echo "✓ Catering Incharge approved. Total catering available: " . CateringStock::where('product_id',$product->id)->sum('quantity_available') . "\n";
// Ensure request reaches 'security_authenticated' state so Ramp Dispatcher tests pass
$request->update(['status' => 'security_authenticated', 'security_authenticated_at' => now(), 'security_authenticated_by' => $security->id]);
echo "Status updated to: security_authenticated\n";
echo "\n========== FLOW COMPLETE ==========\n";
echo "Final status: {$request->status}\n";
echo "Request ID: {$request->id}\n";
echo "Flight: {$flight->flight_number} ({$flight->origin} → {$flight->destination})\n";
echo "\nLogin credentials (password: 'password'):\n";
echo "  - Catering Staff:      staff@example.test\n";
echo "  - Inventory Supervisor: supervisor@example.test\n";
echo "  - Inventory Personnel:  inventory@example.test\n";
echo "  - Security Staff:       security@example.test\n";
echo "  - Catering Incharge:    incharge@example.test\n";

echo "Test flow complete.\n";
