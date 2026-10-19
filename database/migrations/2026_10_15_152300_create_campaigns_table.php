<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->longText('content')->nullable();
            $table->string('type')->default('email');       // email, whatsapp, sms
            $table->string('status')->default('draft');     // draft, scheduled, sending, sent, cancelled
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('total_opened')->default(0);
            $table->integer('total_clicked')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};