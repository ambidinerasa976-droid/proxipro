<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter des colonnes aux utilisateurs
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0);
            $table->integer('available_points')->default(0);
            $table->integer('level')->default(1);
            $table->integer('daily_points')->default(0);
            $table->date('last_daily_reset')->nullable();
        });

        // Créer la table des transactions de points
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('type'); // share, daily, purchase, subscription, etc.
            $table->string('description');
            $table->string('source')->nullable(); // facebook, twitter, etc.
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Créer la table des badges
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('icon'); // fas fa-star, etc.
            $table->string('color'); // primary, success, etc.
            $table->integer('points_required')->default(0);
            $table->integer('level_required')->default(0);
            $table->timestamps();
        });

        // Table de liaison utilisateurs-badges
        Schema::create('badge_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_points', 'available_points', 'level', 'daily_points', 'last_daily_reset']);
        });
        
        Schema::dropIfExists('badge_user');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('point_transactions');
    }
};
