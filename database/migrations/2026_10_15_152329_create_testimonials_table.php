<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('position')->nullable();          // jabatan
            $table->string('company')->nullable();
            $table->string('avatar')->nullable();
            $table->text('content');
            $table->integer('rating')->default(5);           // 1-5

            $table->string('service_type')->nullable();      // website, video, printing, rental
            $table->string('project_reference')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('service_type');
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};