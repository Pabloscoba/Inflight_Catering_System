<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Request #3 Current Status ===" . PHP_EOL . PHP_EOL;

$request = App\Models\Request::find(3);

if ($request) {
    echo "Request ID: " . $request->id . PHP_EOL;
    echo "Current Status: " . $request->status . PHP_EOL;
    echo "Flight: " . $request->flight->flight_number . PHP_EOL;
    echo "Last Updated: " . $request->updated_at . PHP_EOL;
    
    echo PHP_EOL . "Expected status for Ramp Dispatch: security_authenticated" . PHP_EOL;
    
    if ($request->status === 'security_authenticated') {
        echo "✓ Status is correct - ready for dispatch" . PHP_EOL;
    } else {
        echo "✗ Status mismatch - NOT ready for dispatch" . PHP_EOL;
        echo PHP_EOL . "Workflow position:" . PHP_EOL;
        
        $workflow = [
            'pending_catering_incharge' => '1. Waiting for Catering Incharge initial approval',
            'catering_approved' => '2. Approved by Catering Incharge, sent to Inventory Supervisor',
            'supervisor_approved' => '3. Approved by Inventory Supervisor, ready for item issuance',
            'items_issued' => '4. Items issued by Inventory Personnel',
            'pending_final_approval' => '5. Catering Staff received items, awaiting final approval',
            'catering_final_approved' => '6. Catering Incharge gave final approval, sent to Security',
            'security_authenticated' => '7. ← Security authenticated - READY FOR RAMP DISPATCH',
            'ramp_dispatched' => '8. Dispatched by Ramp to Flight Purser',
            'loaded' => '9. Loaded on aircraft by Flight Purser',
        ];
        
        if (isset($workflow[$request->status])) {
            echo "Current: " . $workflow[$request->status] . PHP_EOL;
        }
    }
} else {
    echo "Request #3 not found!" . PHP_EOL;
}
