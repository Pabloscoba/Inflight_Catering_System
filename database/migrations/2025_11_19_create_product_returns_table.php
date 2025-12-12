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
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity_returned');
            $table->string('condition')->default('good'); // good, damaged, expired
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            
            // Workflow tracking
            $table->enum('status', [
                'pending_ramp',      // Cabin Crew created, waiting for Ramp
                'received_by_ramp',  // Ramp received, forwarding to Security
                'pending_security',  // Waiting for Security authentication
                'authenticated',     // Security verified, stock adjusted
                'rejected'           // Return rejected
            ])->default('pending_ramp');
            
            // User tracking
            $table->foreignId('returned_by')->constrained('users'); // Cabin Crew
            $table->foreignId('received_by')->nullable()->constrained('users'); // Ramp Dispatcher
            $table->foreignId('verified_by')->nullable()->constrained('users'); // Security Staff
            
            // Timestamps for each stage
            $table->timestamp('returned_at');
            $table->timestamp('received_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('returned_by');
            $table->index(['status', 'returned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};
