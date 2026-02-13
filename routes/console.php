<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic flight status updates
Schedule::command('flights:update-statuses')
    ->hourly() // Run every hour
    ->withoutOverlapping()
    ->runInBackground();
