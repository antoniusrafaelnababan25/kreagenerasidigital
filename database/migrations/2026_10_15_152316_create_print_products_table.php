<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('apparel');      // apparel, merchandise, packaging, large_format
            $table->string('material')->nullable();          // cotton_combed, polyester, dll
            $table->string('print_technique')->nullable();   // dtf, sablon, sublimasi, uv
            $table->decimal('base_price', 15, 2)->default(0);
            $table->decimal('min_order_qty', 10, 2)->default(1);
            $table->integer('production_days')->default(3);
            $table->string('featured_image')->nullable();
            $table->json('available_sizes')->nullable();     // ["S", "M", "L", "XL"]
            $table->json('available_colors')->nullable();    // ["Putih", "Hitam", "Navy"]
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_products');
    }
};