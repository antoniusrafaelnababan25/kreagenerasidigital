<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_order_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('print_order_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable();         // design, mockup, reference, final
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();        // dalam bytes
            $table->integer('version')->default(1);
            $table->boolean('is_approved')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_order_files');
    }
};