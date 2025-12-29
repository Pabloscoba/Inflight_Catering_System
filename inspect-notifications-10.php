<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

$fd = App\Models\User::role('Flight Dispatcher')->first();
if (!$fd) {
    echo "No Flight Dispatcher user found\n";
    exit(1);
}

echo "Flight Dispatcher: " . $fd->name . " (" . $fd->email . ")\n";
$notes = Illuminate\Support\Facades\DB::table('notifications')->where('notifiable_id', $fd->id)->orderBy('created_at', 'desc')->take(10)->get();
if ($notes->isEmpty()) {
    echo "No notifications found for Flight Dispatcher.\n";
    exit(0);
}

foreach ($notes as $n) {
    echo "---\n";
    echo "ID: " . $n->id . "\n";
    echo "Type: " . $n->type . "\n";
    echo "Data: " . json_encode($n->data) . "\n";
    echo "Created: " . $n->created_at . "\n";
}
