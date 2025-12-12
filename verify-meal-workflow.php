<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "MEAL WORKFLOW TEST\n";
echo str_repeat("=", 80) . "\n\n";

// Get the test meal
$meal = \App\Models\Product::where('meal_type', '!=', null)->first();

if ($meal) {
    echo "Test Meal: {$meal->name} (ID: {$meal->id})\n";
    echo "SKU: {$meal->sku}\n";
    echo "Meal Type: {$meal->meal_type}\n";
    echo "Status: {$meal->status}\n";
    echo str_repeat("-", 80) . "\n\n";
    
    echo "WORKFLOW TRACKING:\n";
    echo "1. Created: " . ($meal->created_at ? $meal->created_at->format('M d, Y H:i') : 'N/A') . "\n";
    
    if ($meal->approved_by) {
        $approver = \App\Models\User::find($meal->approved_by);
        echo "2. Approved by: {$approver->name} at " . $meal->approved_at->format('M d, Y H:i') . "\n";
    } else {
        echo "2. Waiting for Catering Incharge approval...\n";
    }
    
    if ($meal->authenticated_by) {
        $authenticator = \App\Models\User::find($meal->authenticated_by);
        echo "3. Authenticated by: {$authenticator->name} at " . $meal->authenticated_at->format('M d, Y H:i') . "\n";
    } else {
        echo "3. Waiting for Security Staff authentication...\n";
    }
    
    if ($meal->dispatched_by) {
        $dispatcher = \App\Models\User::find($meal->dispatched_by);
        echo "4. Dispatched by: {$dispatcher->name} at " . $meal->dispatched_at->format('M d, Y H:i') . "\n";
    } else {
        echo "4. Waiting for Ramp Dispatcher dispatch...\n";
    }
    
    if ($meal->delivered_by) {
        $deliverer = \App\Models\User::find($meal->delivered_by);
        echo "5. Received by: {$deliverer->name} at " . $meal->delivered_at->format('M d, Y H:i') . "\n";
    } else {
        echo "5. Waiting for Flight Purser/Cabin Crew receipt...\n";
    }
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "WORKFLOW STATUS: " . strtoupper($meal->status) . "\n";
    echo str_repeat("=", 80) . "\n\n";
    
    // Count meals at each stage
    echo "MEAL WORKFLOW STATISTICS:\n";
    echo "Pending (awaiting Catering Incharge): " . \App\Models\Product::whereNotNull('meal_type')->where('status', 'pending')->count() . "\n";
    echo "Approved (awaiting Security): " . \App\Models\Product::whereNotNull('meal_type')->where('status', 'approved')->count() . "\n";
    echo "Authenticated (awaiting Ramp Dispatcher): " . \App\Models\Product::whereNotNull('meal_type')->where('status', 'authenticated')->count() . "\n";
    echo "Dispatched (awaiting Flight Operations): " . \App\Models\Product::whereNotNull('meal_type')->where('status', 'dispatched')->count() . "\n";
    echo "Received (completed): " . \App\Models\Product::whereNotNull('meal_type')->where('status', 'received')->count() . "\n";
    
} else {
    echo "No meals found. Please create a meal first using:\n";
    echo "Route: catering-staff/meals/create\n";
}
