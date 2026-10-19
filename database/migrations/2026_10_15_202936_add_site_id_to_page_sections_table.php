<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->after('page_id')->constrained()->cascadeOnDelete();
        });

        // Isi site_id dari relasi page
        DB::statement('
            UPDATE page_sections
            SET site_id = (
                SELECT site_id FROM pages WHERE pages.id = page_sections.page_id
            )
        ');
    }

    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};