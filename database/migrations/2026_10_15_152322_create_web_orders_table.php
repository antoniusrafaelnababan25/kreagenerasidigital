<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('web_package_id')->nullable()->constrained()->nullOnDelete();

            // Data pelanggan
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->string('company_name')->nullable();
            $table->string('business_type')->nullable();

            // Brief
            $table->string('project_title');
            $table->text('description')->nullable();
            $table->text('reference_websites')->nullable();
            $table->json('requested_features')->nullable();
            $table->string('domain_preference')->nullable();
            $table->boolean('needs_hosting')->default(false);
            $table->boolean('needs_content')->default(false);   // apakah perlu copywriting

            // Keuangan
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);

            // Status
            $table->string('status')->default('pending');
            // pending, brief_confirmed, design, development, review, revision, completed, cancelled
            $table->string('payment_status')->default('unpaid');

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('launched_at')->nullable();
            $table->string('live_url')->nullable();
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
        Schema::dropIfExists('web_orders');
    }
};