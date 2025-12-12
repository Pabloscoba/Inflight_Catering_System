<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Request Workflow ===\n\n";

$requests = App\Models\Request::with(['requester', 'flight', 'items.product'])->get();

if ($requests->isEmpty()) {
    echo "❌ No requests found in database\n";
    exit;
}

foreach ($requests as $req) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Request ID: #{$req->id}\n";
    echo "Flight: {$req->flight->flight_number}\n";
    echo "Requester: {$req->requester->name} ({$req->requester->roles->first()->name})\n";
    echo "Current Status: {$req->status}\n";
    echo "Created: {$req->created_at->format('Y-m-d H:i')}\n";
    
    echo "\n📋 Status History:\n";
    if ($req->forwarded_to_supervisor_at) {
        echo "  ✅ Forwarded to Supervisor: " . $req->forwarded_to_supervisor_at->format('Y-m-d H:i') . "\n";
    }
    if ($req->supervisor_approved_at) {
        echo "  ✅ Supervisor Approved: " . $req->supervisor_approved_at->format('Y-m-d H:i') . "\n";
        echo "      By: " . ($req->supervisorApprover->name ?? 'Unknown') . "\n";
    }
    if ($req->forwarded_to_security_at) {
        echo "  ✅ Forwarded to Security: " . $req->forwarded_to_security_at->format('Y-m-d H:i') . "\n";
    }
    if ($req->security_authenticated_at) {
        echo "  ✅ Security Authenticated: " . $req->security_authenticated_at->format('Y-m-d H:i') . "\n";
    }
    if ($req->catering_approved_at) {
        echo "  ✅ Catering Approved: " . $req->catering_approved_at->format('Y-m-d H:i') . "\n";
    }
    if ($req->sent_to_ramp_at) {
        echo "  ✅ Sent to Ramp: " . $req->sent_to_ramp_at->format('Y-m-d H:i') . "\n";
    }
    
    echo "\n🔍 Next Action:\n";
    switch ($req->status) {
        case 'pending_inventory':
            echo "  ⏳ Inventory Personnel needs to forward to Supervisor\n";
            break;
        case 'pending_supervisor':
            echo "  ⏳ Inventory Supervisor needs to approve\n";
            break;
        case 'supervisor_approved':
            echo "  ⏳ Inventory Personnel needs to forward to Security\n";
            break;
        case 'sent_to_security':
            echo "  ⏳ Security needs to authenticate\n";
            break;
        case 'security_approved':
            echo "  ⏳ Catering Incharge needs to approve\n";
            break;
        case 'catering_approved':
            echo "  ⏳ Catering Staff needs to send to Ramp\n";
            break;
        case 'sent_to_ramp':
            echo "  ⏳ Ramp Dispatcher needs to dispatch\n";
            break;
        default:
            echo "  Status: {$req->status}\n";
    }
    
    echo "\n📦 Items:\n";
    foreach ($req->items as $item) {
        echo "  - {$item->product->name}: {$item->quantity_requested} requested";
        if ($item->quantity_approved) {
            echo ", {$item->quantity_approved} approved";
        }
        echo "\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
