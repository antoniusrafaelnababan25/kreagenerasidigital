<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('compare_price', 15, 2)->nullable();
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('unit')->default('pcs');
            $table->string('status')->default('draft');    // draft, published, archived
            $table->boolean('is_featured')->default(false);
            $table->boolean('track_stock')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug']);
            $table->index('status');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};