<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('primary_color')->default('#3B82F6');
            $table->string('secondary_color')->default('#8B5CF6');
            $table->string('accent_color')->default('#F59E0B');
            $table->string('background_color')->default('#FFFFFF');
            $table->string('text_color')->default('#111827');
            $table->string('font_family')->default('Inter');
            $table->string('heading_font')->default('Inter');
            $table->string('hero_gradient')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};