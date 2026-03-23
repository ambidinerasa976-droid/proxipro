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
        Schema::create('lost_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['lost', 'found'])->default('lost');
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->string('location');
            $table->date('date');
            $table->string('contact_phone')->nullable();
            $table->decimal('reward', 10, 2)->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['active', 'resolved', 'closed'])->default('active');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_items');
    }
};
