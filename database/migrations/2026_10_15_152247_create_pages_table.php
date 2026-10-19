<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->unique(['site_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};