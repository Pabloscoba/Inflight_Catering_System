<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add flight dispatcher clearance statuses
        DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM(
            'pending_catering_incharge',
            'catering_approved',
            'supervisor_approved',
            'items_issued',
            'catering_staff_received',
            'pending_final_approval',
            'catering_final_approved',
            'security_authenticated',
            'awaiting_flight_dispatcher',
            'flight_dispatcher_assessed',
            'flight_cleared_for_departure',
            'ramp_dispatched',
            'loaded',
            'delivered',
            'served',
            'rejected',
            'cancelled',
            'pending',
            'pending_inventory',
            'pending_supervisor',
            'sent_to_security',
            'security_approved',
            'sent_to_ramp',
            'ready_for_dispatch',
            'dispatched',
            'flight_received',
            'approved',
            'received'
        ) NOT NULL DEFAULT 'pending_catering_incharge'");

        // Add flight dispatcher clearance tracking fields
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignId('flight_dispatcher_assessed_by')->nullable()->constrained('users')->after('dispatched_at');
            $table->timestamp('flight_dispatcher_assessed_at')->nullable()->after('flight_dispatcher_assessed_by');
            $table->timestamp('flight_cleared_for_departure_at')->nullable()->after('flight_dispatcher_assessed_at');
            $table->text('flight_clearance_notes')->nullable()->after('flight_cleared_for_departure_at');
            $table->boolean('flight_cleared')->default(false)->after('flight_clearance_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['flight_dispatcher_assessed_by']);
            $table->dropColumn([
                'flight_dispatcher_assessed_by',
                'flight_dispatcher_assessed_at',
                'flight_cleared_for_departure_at',
                'flight_clearance_notes',
                'flight_cleared'
            ]);
        });

        DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM(
            'pending_catering_incharge',
            'catering_approved',
            'supervisor_approved',
            'items_issued',
            'catering_staff_received',
            'pending_final_approval',
            'catering_final_approved',
            'security_authenticated',
            'ramp_dispatched',
            'loaded',
            'delivered',
            'served',
            'rejected',
            'cancelled',
            'pending',
            'pending_inventory',
            'pending_supervisor',
            'sent_to_security',
            'security_approved',
            'sent_to_ramp',
            'ready_for_dispatch',
            'dispatched',
            'flight_received',
            'approved',
            'received'
        ) NOT NULL DEFAULT 'pending_catering_incharge'");
    }
};
