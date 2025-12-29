<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$r = App\Models\Request::find(10);
if (! $r) {
    echo "Request #10 not found\n";
    exit(1);
}

echo "Request #10\n";
echo " status: " . $r->status . "\n";
echo " dispatcher_comments: " . ($r->dispatcher_comments ?? 'NULL') . "\n";
echo " dispatcher_recommended: " . ($r->dispatcher_recommended ? '1' : '0') . "\n";
echo " dispatcher_recommended_by: " . ($r->dispatcher_recommended_by ?? 'NULL') . "\n";
echo " dispatcher_recommended_at: " . ($r->dispatcher_recommended_at ? $r->dispatcher_recommended_at : 'NULL') . "\n";

$u = App\Models\User::where('email', 'flight.dispatcher@inflightcatering.com')->first();
if ($u) {
    echo "\nFlight Dispatcher user:\n";
    echo " email: " . $u->email . "\n";
    echo " roles: " . implode(', ', $u->getRoleNames()->toArray()) . "\n";
}
