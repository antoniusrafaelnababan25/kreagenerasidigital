<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_task_id')->nullable()->constrained()->nullOnDelete();

            $table->date('date');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->decimal('hours', 8, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_billable')->default(true);
            $table->decimal('hourly_rate', 15, 2)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('date');
            $table->index(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};