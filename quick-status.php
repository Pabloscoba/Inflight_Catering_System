<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$r = App\Models\Request::find(3);
echo 'Request #3 Status: ' . $r->status . PHP_EOL;
