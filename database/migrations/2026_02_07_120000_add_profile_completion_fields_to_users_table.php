<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ajout des champs pour compléter le profil des utilisateurs OAuth
     * et afficher le métier/profession sur le profil
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Métier/Profession affiché sur le profil
            $table->string('profession')->nullable()->after('bio');
            
            // Localisation
            $table->string('country')->nullable()->after('profession');
            $table->string('city')->nullable()->after('country');
            
            // Indicateur si le profil OAuth a été complété
            $table->boolean('profile_completed')->default(false)->after('city');
            $table->timestamp('profile_completed_at')->nullable()->after('profile_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profession',
                'country',
                'city',
                'profile_completed',
                'profile_completed_at',
            ]);
        });
    }
};
