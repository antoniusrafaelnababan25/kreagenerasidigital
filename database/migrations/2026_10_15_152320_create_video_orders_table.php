<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('video_package_id')->nullable()->constrained()->nullOnDelete();

            // Data pelanggan
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();

            // Brief
            $table->string('project_title');
            $table->string('video_type')->nullable();         // wedding, company_profile, event, sosmed
            $table->text('concept')->nullable();
            $table->text('reference')->nullable();
            $table->string('location')->nullable();
            $table->date('shooting_date')->nullable();
            $table->time('shooting_time')->nullable();
            $table->integer('duration_minutes')->nullable();

            // Keuangan
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);

            // Status produksi
            $table->string('status')->default('pending');
            // pending, confirmed, pre_production, shooting, editing, review, revision, completed, cancelled
            $table->string('payment_status')->default('unpaid');

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_orders');
    }
};