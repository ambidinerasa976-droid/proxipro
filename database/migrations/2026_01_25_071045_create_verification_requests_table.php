<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['profile_verification', 'service_provider'])->default('profile_verification');
            $table->string('document_type')->nullable(); // CNI, Passport, etc.
            $table->string('document_front')->nullable(); // Chemin vers le recto
            $table->string('document_back')->nullable(); // Chemin vers le verso
            $table->string('selfie')->nullable(); // Photo selfie avec document
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->string('payment_id')->nullable(); // ID Stripe
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->datetime('paid_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_requests');
    }
};
