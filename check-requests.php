<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$r6 = App\Models\Request::find(6);
$r7 = App\Models\Request::find(7);

echo "Request #6:\n";
if ($r6) {
    echo "  Status: " . $r6->status . "\n";
    echo "  Requester ID: " . $r6->requester_id . "\n";
} else {
    echo "  Not found\n";
}

echo "\nRequest #7:\n";
if ($r7) {
    echo "  Status: " . $r7->status . "\n";
    echo "  Requester ID: " . $r7->requester_id . "\n";
} else {
    echo "  Not found\n";
}

echo "\nAll requests for requester_id=5:\n";
$allRequests = App\Models\Request::where('requester_id', 5)->get(['id', 'status']);
foreach ($allRequests as $req) {
    echo "  Request #{$req->id}: Status={$req->status}\n";
}

echo "\nAll catering_approved requests:\n";
$approved = App\Models\Request::where('status', 'catering_approved')->get(['id', 'status', 'requester_id']);
foreach ($approved as $req) {
    echo "  Request #{$req->id}: Requester={$req->requester_id}\n";
}
