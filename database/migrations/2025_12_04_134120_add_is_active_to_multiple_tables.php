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
        // Add is_active to categories table (products already has it)
        if (!Schema::hasColumn('categories', 'is_active')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('description');
            });
        }

        // Add is_active to flights table
        if (!Schema::hasColumn('flights', 'is_active')) {
            Schema::table('flights', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('status');
            });
        }

        // Add is_active to users table
        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('email_verified_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categories', 'is_active')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasColumn('flights', 'is_active')) {
            Schema::table('flights', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
