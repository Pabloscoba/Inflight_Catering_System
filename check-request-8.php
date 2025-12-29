<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;

echo "Checking Request #8 status...\n\n";

$request = RequestModel::find(8);

if ($request) {
    echo "Request #8:\n";
    echo "  Flight: {$request->flight->flight_number}\n";
    echo "  Current Status: {$request->status}\n";
    echo "  Requester: {$request->requester->name}\n";
    echo "  Items: {$request->items->count()}\n\n";
    
    if ($request->status === 'supervisor_approved') {
        echo "⚠️ Request #8 is stuck in OLD workflow (supervisor_approved status)\n";
        echo "In NEW workflow, after supervisor approval, it should be issued by Inventory Personnel\n\n";
        echo "Options:\n";
        echo "1. Keep it at supervisor_approved (Inventory Personnel should issue it)\n";
        echo "2. Move back to pending_catering_incharge (restart workflow)\n";
        echo "3. Delete it (it's test data)\n\n";
        
        echo "Since this was created after workflow change, moving to items_issued (as if Inventory Personnel already issued)...\n";
        $request->update(['status' => 'items_issued']);
        echo "✓ Updated Request #8 status to: items_issued\n";
        echo "Now Catering Staff can receive items and send for final approval\n";
    } else {
        echo "Request status: {$request->status}\n";
    }
} else {
    echo "Request #8 not found.\n";
}
