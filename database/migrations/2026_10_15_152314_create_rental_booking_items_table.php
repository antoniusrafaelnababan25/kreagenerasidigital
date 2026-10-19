<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rental_item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price_per_day', 15, 2);
            $table->integer('days')->default(1);
            $table->decimal('subtotal', 15, 2);
            $table->string('condition_out')->nullable();    // kondisi saat keluar
            $table->string('condition_in')->nullable();     // kondisi saat kembali
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_booking_items');
    }
};