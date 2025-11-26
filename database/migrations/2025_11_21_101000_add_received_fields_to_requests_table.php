<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add received fields and extend status enum to include 'received'
        Schema::table('requests', function (Blueprint $table) {
            // Add nullable received_by and received_date
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_date');
            $table->timestamp('received_date')->nullable()->after('received_by');
        });

        // Modify the enum: in MySQL we need to perform raw ALTER
        // We'll change column to a VARCHAR and then back to ENUM to include 'received'
        // For portability, run raw SQL to alter enum (works for MySQL).
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM('pending','approved','rejected','received') NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `requests` MODIFY COLUMN `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn(['received_by', 'received_date']);
        });
    }
};
