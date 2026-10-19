<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable');                     // order, invoice, rental_booking
            $table->string('payment_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('method')->nullable();          // transfer, cash, midtrans, xendit
            $table->string('status')->default('pending');  // pending, verified, failed, refunded
            $table->string('reference')->nullable();
            $table->string('proof_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};