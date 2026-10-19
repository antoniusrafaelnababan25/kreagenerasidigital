<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('service_type')->nullable();    // website, rental, video, printing
            $table->text('message')->nullable();
            $table->string('source')->nullable();          // website, whatsapp, instagram, referral
            $table->string('status')->default('new');      // new, contacted, qualified, proposal, won, lost
            $table->string('priority')->default('medium');  // low, medium, high
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('service_type');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};