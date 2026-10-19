<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();

            $table->string('question');
            $table->text('answer');
            $table->string('category')->nullable();          // general, website, rental, video, printing
            $table->string('service_type')->nullable();

            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->integer('view_count')->default(0);

            // SEO untuk FAQ Schema
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('service_type');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};