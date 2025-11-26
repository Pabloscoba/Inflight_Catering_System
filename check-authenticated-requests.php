<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$requests = App\Models\Request::where('status', 'security_approved')->get();

echo "Requests with status 'security_approved':\n";
echo "=========================================\n";
echo "Total: " . $requests->count() . "\n\n";

foreach ($requests as $request) {
    echo "Request #" . $request->id . "\n";
    echo "  Status: " . $request->status . "\n";
    echo "  Flight: " . $request->flight->flight_number . "\n";
    echo "  Security Approved At: " . ($request->security_approved_at ?? 'NULL') . "\n";
    echo "  Security Approved By: " . ($request->security_approved_by ?? 'NULL') . "\n";
    echo "  Updated At: " . $request->updated_at . "\n\n";
}
