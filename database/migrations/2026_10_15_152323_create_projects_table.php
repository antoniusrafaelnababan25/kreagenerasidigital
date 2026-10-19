<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('service_type')->nullable();      // website, video, rental, printing
            $table->string('status')->default('pending');
            // pending, in_progress, review, completed, on_hold, cancelled
            $table->string('priority')->default('medium');   // low, medium, high, urgent

            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Keuangan
            $table->decimal('budget', 15, 2)->default(0);
            $table->decimal('actual_cost', 15, 2)->default(0);
            $table->decimal('billed_amount', 15, 2)->default(0);

            $table->integer('progress')->default(0);         // 0-100%
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('team_members')->nullable();         // array user_id
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('service_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};