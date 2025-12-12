<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add meal request specific statuses and tracking fields
     */
    public function up(): void
    {
        // Update status enum to include meal request flow
        DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM(
            'pending',
            'pending_inventory',
            'pending_supervisor',
            'supervisor_approved',
            'sent_to_security',
            'security_approved',
            'catering_approved',
            'security_dispatched',
            'handed_to_flight',
            'flight_received',
            'in_service',
            'served',
            'approved',
            'rejected',
            'received',
            'loaded',
            'delivered',
            'dispatched'
        ) NOT NULL DEFAULT 'pending'");

        // Add tracking fields for meal request workflow
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedBigInteger('catering_approved_by')->nullable()->after('approved_by');
            $table->timestamp('catering_approved_at')->nullable()->after('approved_date');
            
            $table->unsignedBigInteger('security_dispatched_by')->nullable()->after('catering_approved_at');
            $table->timestamp('security_dispatched_at')->nullable()->after('security_dispatched_by');
            
            $table->unsignedBigInteger('handed_to_flight_by')->nullable()->after('security_dispatched_at');
            $table->timestamp('handed_to_flight_at')->nullable()->after('handed_to_flight_by');
            
            $table->unsignedBigInteger('flight_received_by')->nullable()->after('handed_to_flight_at');
            $table->timestamp('flight_received_at')->nullable()->after('flight_received_by');
            
            $table->unsignedBigInteger('served_by')->nullable()->after('flight_received_at');
            $table->timestamp('served_at')->nullable()->after('served_by');
            
            $table->enum('request_type', ['meal', 'product', 'mixed'])->default('product')->after('status');
            
            // Foreign keys
            $table->foreign('catering_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('security_dispatched_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('handed_to_flight_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('flight_received_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('served_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['catering_approved_by']);
            $table->dropForeign(['security_dispatched_by']);
            $table->dropForeign(['handed_to_flight_by']);
            $table->dropForeign(['flight_received_by']);
            $table->dropForeign(['served_by']);
            
            $table->dropColumn([
                'catering_approved_by',
                'catering_approved_at',
                'security_dispatched_by',
                'security_dispatched_at',
                'handed_to_flight_by',
                'handed_to_flight_at',
                'flight_received_by',
                'flight_received_at',
                'served_by',
                'served_at',
                'request_type'
            ]);
        });

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
};
