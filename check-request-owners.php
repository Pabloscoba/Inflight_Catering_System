<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$r6 = App\Models\Request::find(6);
$r7 = App\Models\Request::find(7);

echo "Request #6:\n";
echo "  Requester ID: {$r6->requester_id}\n";
echo "  Requester: {$r6->requester->name}\n";
echo "  Status: {$r6->status}\n\n";

echo "Request #7:\n";
echo "  Requester ID: {$r7->requester_id}\n";
echo "  Requester: {$r7->requester->name}\n";
echo "  Status: {$r7->status}\n\n";

echo "Catering Staff User ID should be: 5\n";
