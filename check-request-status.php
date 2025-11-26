<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$request = App\Models\Request::find(7);

if ($request) {
    echo "Request #7 Details:\n";
    echo "==================\n";
    echo "Status: " . $request->status . "\n";
    echo "Security Approved At: " . ($request->security_approved_at ?? 'NULL') . "\n";
    echo "Security Approved By: " . ($request->security_approved_by ?? 'NULL') . "\n";
    echo "Updated At: " . $request->updated_at . "\n";
} else {
    echo "Request #7 not found\n";
}
