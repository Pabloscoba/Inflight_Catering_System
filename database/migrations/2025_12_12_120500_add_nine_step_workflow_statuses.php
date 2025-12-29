<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add all 9-step workflow statuses to requests table
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM(
            'pending',
            'pending_inventory',
            'pending_supervisor',
            'supervisor_approved',
            'sent_to_security',
            'security_approved',
            'catering_approved',
            'sent_to_ramp',
            'ready_for_dispatch',
            'dispatched',
            'loaded',
            'flight_received',
            'delivered',
            'served',
            'approved',
            'rejected',
            'received'
        ) NOT NULL DEFAULT 'pending_inventory'");
    }
};
