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
        // Flight Dispatch Records Table
        Schema::create('flight_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');
            $table->foreignId('dispatcher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('request_id')->nullable()->constrained()->onDelete('set null');
            
            // Confirmation Fields
            $table->enum('fuel_status', ['pending', 'confirmed', 'insufficient'])->default('pending');
            $table->timestamp('fuel_confirmed_at')->nullable();
            $table->text('fuel_notes')->nullable();
            
            $table->enum('crew_readiness', ['pending', 'confirmed', 'not_ready'])->default('pending');
            $table->timestamp('crew_confirmed_at')->nullable();
            $table->text('crew_notes')->nullable();
            
            $table->enum('catering_status', ['pending', 'confirmed', 'delayed'])->default('pending');
            $table->timestamp('catering_confirmed_at')->nullable();
            $table->text('catering_notes')->nullable();
            
            $table->enum('baggage_status', ['pending', 'confirmed', 'delayed'])->default('pending');
            $table->timestamp('baggage_confirmed_at')->nullable();
            $table->text('baggage_notes')->nullable();
            
            // Dispatch Details
            $table->text('operational_notes')->nullable();
            $table->text('delay_reason')->nullable();
            $table->enum('dispatch_recommendation', ['clear_to_dispatch', 'hold', 'delay'])->nullable();
            $table->timestamp('recommended_at')->nullable();
            
            $table->enum('overall_status', ['pending', 'in_progress', 'ready', 'dispatched', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // Flight Status Updates (for tracking ETD/ETA changes)
        Schema::create('flight_status_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->string('old_status');
            $table->string('new_status');
            $table->dateTime('old_departure_time')->nullable();
            $table->dateTime('new_departure_time')->nullable();
            $table->dateTime('old_arrival_time')->nullable();
            $table->dateTime('new_arrival_time')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        // Request Messages Table (for communication between roles)
        Schema::create('request_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->string('sender_role'); // Flight Dispatcher, Cabin Crew, Ramp Dispatcher, etc.
            $table->string('recipient_role'); // Who should see this message
            $table->text('message');
            $table->enum('message_type', ['general', 'urgent', 'confirmation', 'query'])->default('general');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_messages');
        Schema::dropIfExists('flight_status_updates');
        Schema::dropIfExists('flight_dispatches');
    }
};
