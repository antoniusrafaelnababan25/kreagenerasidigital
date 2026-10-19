<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('returned_at');
            $table->integer('days_late')->default(0);
            $table->decimal('late_fee', 15, 2)->default(0);
            $table->decimal('damage_fee', 15, 2)->default(0);
            $table->decimal('deposit_returned', 15, 2)->default(0);
            $table->text('condition_notes')->nullable();
            $table->json('item_conditions')->nullable();     // detail kondisi per item
            $table->string('status')->default('completed');  // completed, partial, damaged
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_returns');
    }
};