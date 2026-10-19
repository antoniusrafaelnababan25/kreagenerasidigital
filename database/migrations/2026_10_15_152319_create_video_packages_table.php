<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('category')->nullable();          // wedding, corporate, event, content
            $table->text('description')->nullable();
            $table->text('features')->nullable();            // JSON atau text
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('compare_price', 15, 2)->nullable();
            $table->string('duration')->nullable();          // 1 hari, 3 menit video
            $table->integer('crew_count')->default(1);
            $table->string('output_format')->nullable();     // MP4 4K, Full HD
            $table->integer('revision_count')->default(1);
            $table->integer('delivery_days')->default(7);
            $table->string('featured_image')->nullable();
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
        Schema::dropIfExists('video_packages');
    }
};