<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('print_product_id')->nullable()->constrained()->nullOnDelete();

            // Data pelanggan
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();

            // Detail produksi
            $table->string('size')->nullable();              // S, M, L, XL
            $table->string('color')->nullable();
            $table->string('material')->nullable();
            $table->string('print_technique')->nullable();
            $table->integer('quantity')->default(1);

            // Harga
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);

            // Status produksi
            $table->string('status')->default('pending');
            // pending, approved, in_production, ready, shipped, completed, cancelled
            $table->string('payment_status')->default('unpaid');
            // unpaid, dp, paid

            // Pengiriman
            $table->string('shipping_method')->default('pickup');  // pickup, delivery
            $table->string('tracking_number')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Brief tambahan
            $table->text('notes')->nullable();
            $table->text('design_notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_orders');
    }
};