<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pro_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('plan', ['monthly', 'annual'])->default('monthly');
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_payment_intent')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->boolean('notifications_enabled')->default(true);
            $table->boolean('realtime_notifications')->default(true);
            $table->json('selected_categories')->nullable();
            $table->integer('intervention_radius')->default(30); // km
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_subscriptions');
    }
};
