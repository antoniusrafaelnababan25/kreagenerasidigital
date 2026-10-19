<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->decimal('price_per_day', 15, 2)->default(0);
            $table->decimal('price_per_week', 15, 2)->nullable();
            $table->decimal('deposit', 15, 2)->default(0);
            $table->integer('stock_total')->default(0);
            $table->integer('stock_available')->default(0);
            $table->integer('stock_maintenance')->default(0);
            $table->string('condition')->default('good');   // good, fair, damaged
            $table->string('status')->default('available'); // available, rented, maintenance, retired
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('weight', 10, 2)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};