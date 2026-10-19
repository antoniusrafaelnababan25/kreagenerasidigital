<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();        // company, website, rental, video, printing
            $table->string('name');                 // KREA Company Profile
            $table->string('path')->unique();       // /, layanan/website, dst
            $table->string('domain')->nullable();   // untuk production nanti
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};