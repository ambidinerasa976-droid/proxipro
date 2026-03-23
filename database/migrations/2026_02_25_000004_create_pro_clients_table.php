<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pro_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('company')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['prospect', 'active', 'completed', 'archived'])->default('prospect');
            $table->string('source')->nullable(); // message, quote, manual
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->integer('total_projects')->default(0);
            $table->timestamp('last_interaction_at')->nullable();
            $table->timestamps();

            $table->index(['provider_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_clients');
    }
};
