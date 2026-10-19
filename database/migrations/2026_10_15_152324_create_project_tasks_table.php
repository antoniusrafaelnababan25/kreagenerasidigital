<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('project_tasks')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('todo');
            // todo, in_progress, review, done, cancelled
            $table->string('priority')->default('medium');
            $table->integer('order')->default(0);
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};