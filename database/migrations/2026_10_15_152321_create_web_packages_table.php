<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('category')->nullable();          // landing, company, ecommerce, custom
            $table->text('description')->nullable();
            $table->json('features')->nullable();             // list fitur
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('compare_price', 15, 2)->nullable();
            $table->integer('pages_count')->default(1);
            $table->integer('revision_count')->default(1);
            $table->integer('delivery_days')->default(14);
            $table->boolean('include_domain')->default(false);
            $table->boolean('include_hosting')->default(false);
            $table->boolean('include_maintenance')->default(false);
            $table->integer('maintenance_months')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_packages');
    }
};