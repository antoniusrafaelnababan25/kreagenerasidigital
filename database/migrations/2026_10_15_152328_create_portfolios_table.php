<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('slug');
            $table->string('title');
            $table->string('client_name')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Media
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();             // array gambar
            $table->string('video_url')->nullable();
            $table->string('live_url')->nullable();

            // Kategori layanan
            $table->string('service_type')->nullable();      // website, video, printing, rental
            $table->string('project_year')->nullable();
            $table->json('tags')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Display
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug']);
            $table->index('service_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};