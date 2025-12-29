<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::where('email','flight.dispatcher@inflightcatering.com')->first();
if (!$u) {
    echo "User not found\n";
    exit(1);
}

echo "User: " . $u->email . "\n";
echo "Roles: " . implode(', ', $u->getRoleNames()->toArray()) . "\n";
