<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Change Request #6 back to catering_approved
$r6 = App\Models\Request::find(6);
if ($r6) {
    $r6->status = 'catering_approved';
    $r6->sent_to_ramp_at = null;
    $r6->save();
    echo "Request #6 updated to catering_approved\n";
}

echo "\nApproved requests for Catering Staff (requester_id=5):\n";
$approved = App\Models\Request::where('requester_id', 5)
    ->where('status', 'catering_approved')
    ->get(['id', 'status']);
foreach ($approved as $req) {
    echo "  Request #{$req->id}: {$req->status}\n";
}
