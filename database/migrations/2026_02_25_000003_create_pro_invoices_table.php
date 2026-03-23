<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pro_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('quote_id')->nullable()->constrained('pro_quotes')->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_address')->nullable();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->json('items'); // [{description, quantity, unit_price, total}]
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(20);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->date('due_date')->nullable();
            $table->date('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->text('payment_terms')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_invoices');
    }
};
