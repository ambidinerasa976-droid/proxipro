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
        Schema::create('social_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('platform', 20); // facebook, twitter, linkedin, whatsapp
            $table->integer('points_earned');
            $table->string('ip_address', 50)->nullable();
            $table->timestamps();
            
            // Un utilisateur ne peut réclamer qu'une fois par plateforme
            $table->unique(['user_id', 'platform']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_shares');
    }
};
