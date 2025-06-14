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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('booking_number')->unique();
            
            // Booking details
            $table->date('date');
            $table->time('time');
            $table->enum('location_type', ['studio', 'outdoor', 'client_home', 'venue']);
            $table->text('location');
            $table->text('notes')->nullable();
            
            // Client information
            $table->string('client_name', 100);
            $table->string('client_phone', 20);
            $table->string('client_email', 100)->nullable();
            
            // Pricing
            $table->decimal('total_price', 12, 2);
            $table->decimal('deposit_amount', 12, 2)->nullable();
            
            // Status
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Additional fields
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['date', 'time']);
            $table->index(['status', 'date']);
            $table->index('booking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
