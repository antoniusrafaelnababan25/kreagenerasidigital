<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('fixed');      // fixed, percentage
            $table->decimal('value', 15, 2);
            $table->decimal('min_purchase', 15, 2)->nullable();
            $table->decimal('max_discount', 15, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('usage_limit_per_user')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};