<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\CateringStock;

echo "=== CATERING INCHARGE DASHBOARD DATA ===\n\n";

// Check Requests
echo "REQUESTS STATUS:\n";
echo "Total Requests: " . RequestModel::count() . "\n";
echo "Security Approved (ready for Catering Incharge): " . RequestModel::where('status', 'security_approved')->count() . "\n";
echo "Pending: " . RequestModel::where('status', 'pending')->count() . "\n";
echo "Approved: " . RequestModel::where('status', 'approved')->count() . "\n\n";

// All requests with their status
echo "ALL REQUESTS:\n";
$requests = RequestModel::with('flight')->get();
foreach ($requests as $req) {
    echo "Request #{$req->id}: {$req->status} - Flight: {$req->flight->flight_number}\n";
}

echo "\n";

// Check Catering Stock Receipts
echo "CATERING STOCK RECEIPTS:\n";
echo "Total Receipts: " . CateringStock::count() . "\n";
echo "Pending (need approval): " . CateringStock::where('status', 'pending')->count() . "\n";
echo "Approved: " . CateringStock::where('status', 'approved')->count() . "\n\n";

// Total available stock
$totalAvailable = CateringStock::where('status', 'approved')->sum('quantity_available');
echo "TOTAL AVAILABLE STOCK: {$totalAvailable} units\n";
