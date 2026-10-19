<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->string('label');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('target_blank')->default(false);
            $table->timestamps();

            $table->index(['site_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};