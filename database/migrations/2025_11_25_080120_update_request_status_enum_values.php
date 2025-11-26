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
        DB::statement("ALTER TABLE `requests` MODIFY `status` ENUM('pending', 'approved', 'rejected', 'pending_inventory', 'pending_supervisor', 'supervisor_approved', 'sent_to_security', 'security_approved', 'catering_approved', 'ready_for_dispatch', 'dispatched', 'loaded', 'delivered') DEFAULT 'pending_inventory'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `requests` MODIFY `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
