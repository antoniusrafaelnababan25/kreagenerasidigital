<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('slug');
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('reading_time_minutes')->nullable();
            $table->integer('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->json('schema_json')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->boolean('is_indexable')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug']);
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};