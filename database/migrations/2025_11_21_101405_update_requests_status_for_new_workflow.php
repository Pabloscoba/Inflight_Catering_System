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
        // Update requests status enum to include new workflow stages:
        // pending_supervisor -> supervisor_approved -> sent_to_security -> security_approved -> catering_approved
        DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM(
            'pending',
            'pending_inventory',
            'pending_supervisor',
            'supervisor_approved',
            'sent_to_security',
            'security_approved',
            'catering_approved',
            'approved',
            'rejected',
            'received'
        ) NOT NULL DEFAULT 'pending_inventory'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM(
            'pending',
            'approved',
            'rejected',
            'received'
        ) NOT NULL DEFAULT 'pending'");
    }
};
