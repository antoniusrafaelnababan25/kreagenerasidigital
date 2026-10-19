<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();

            // Referensi ke order/booking
            $table->nullableMorphs('invoicable');            // order, rental_booking, print_order, dll

            $table->string('title');
            $table->text('description')->nullable();
            $table->date('issue_date');
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();

            // Keuangan
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining', 15, 2)->default(0);

            // Status
            $table->string('status')->default('draft');
            // draft, sent, viewed, partial, paid, overdue, cancelled
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('issue_date');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};