<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Change Request #7 back to catering_approved
$r7 = App\Models\Request::find(7);
if ($r7) {
    $r7->status = 'catering_approved';
    $r7->sent_to_ramp_at = null;
    $r7->save();
    echo "Request #7 updated to catering_approved\n";
} else {
    echo "Request #7 not found\n";
}

// Verify
$r7 = App\Models\Request::find(7);
echo "Request #7 current status: " . $r7->status . "\n";
