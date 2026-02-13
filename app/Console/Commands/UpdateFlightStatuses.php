<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Flight;
use Carbon\Carbon;

class UpdateFlightStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flights:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update flight statuses based on departure times and clean up old flights';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting flight status updates...');

        // 1. Auto-update scheduled flights that have passed departure time to "departed"
        $scheduledPastFlights = Flight::where('status', 'scheduled')
            ->where('departure_time', '<', now())
            ->get();

        $departedCount = 0;
        foreach ($scheduledPastFlights as $flight) {
            $flight->update(['status' => 'departed']);
            $departedCount++;
            $this->line("✓ Flight {$flight->flight_number} marked as departed");
        }

        // 2. Auto-update departed flights that have passed arrival time to "arrived"
        $departedPastArrival = Flight::where('status', 'departed')
            ->whereNotNull('arrival_time')
            ->where('arrival_time', '<', now())
            ->get();

        $arrivedCount = 0;
        foreach ($departedPastArrival as $flight) {
            $flight->update(['status' => 'arrived']);
            $arrivedCount++;
            $this->line("✓ Flight {$flight->flight_number} marked as arrived");
        }

        // 3. Optional: Soft delete or archive very old flights (30+ days old)
        $oldFlights = Flight::where('departure_time', '<', now()->subDays(30))
            ->whereIn('status', ['departed', 'arrived'])
            ->get();

        $archivedCount = 0;
        foreach ($oldFlights as $flight) {
            // You can either soft delete or update status to 'archived' or 'completed'
            // For now, we'll just mark them as 'completed' for record keeping
            if (!in_array($flight->status, ['completed'])) {
                $flight->update(['status' => 'completed']);
                $archivedCount++;
                $this->line("✓ Flight {$flight->flight_number} marked as completed (archived)");
            }
        }

        // Summary
        $this->newLine();
        $this->info("✅ Flight status update completed!");
        $this->table(
            ['Action', 'Count'],
            [
                ['Scheduled → Departed', $departedCount],
                ['Departed → Arrived', $arrivedCount],
                ['Archived (Completed)', $archivedCount],
            ]
        );

        return Command::SUCCESS;
    }
}
