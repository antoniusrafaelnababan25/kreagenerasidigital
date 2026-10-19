<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();

            // Data penyewa
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();
            $table->string('customer_id_number')->nullable();   // KTP/SIM
            $table->string('customer_id_photo')->nullable();

            // Periode sewa
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->default(0);

            // Pengambilan & pengembalian
            $table->string('pickup_method')->default('self_pickup'); // self_pickup, delivery
            $table->text('pickup_address')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('returned_at')->nullable();

            // Keuangan
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('deposit', 15, 2)->default(0);
            $table->decimal('late_fee', 15, 2)->default(0);
            $table->decimal('damage_fee', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('refund_amount', 15, 2)->default(0);

            // Status
            $table->string('status')->default('pending');
            // pending, confirmed, picked_up, returned, completed, cancelled
            $table->string('payment_status')->default('unpaid');
            // unpaid, dp, paid, refunded

            $table->text('notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('payment_status');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_bookings');
    }
};