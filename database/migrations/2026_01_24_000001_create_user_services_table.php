<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table pour stocker les compétences/services des prestataires particuliers
     * Permet aux utilisateurs "particulier" de devenir "particulier_prestataire"
     * et d'apparaître dans les recherches de professionnels
     */
    public function up(): void
    {
        // Table principale pour les services/compétences des prestataires
        Schema::create('user_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('main_category', 100); // Catégorie principale (ex: "Bricolage & Travaux")
            $table->string('subcategory', 100);   // Sous-catégorie (ex: "Plomberie")
            $table->integer('experience_years')->default(0); // Années d'expérience
            $table->text('description')->nullable(); // Description de l'expertise
            $table->boolean('is_verified')->default(false); // Vérifié par admin
            $table->boolean('is_active')->default(true); // Service actif
            $table->timestamps();
            
            // Index pour les recherches
            $table->index(['main_category', 'subcategory']);
            $table->index('is_active');
            
            // Un utilisateur ne peut pas avoir la même sous-catégorie deux fois
            $table->unique(['user_id', 'subcategory']);
        });

        // Ajouter le champ is_service_provider à la table users si pas déjà existant
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_service_provider')) {
                $table->boolean('is_service_provider')->default(false)->after('user_type');
            }
            if (!Schema::hasColumn('users', 'service_provider_since')) {
                $table->timestamp('service_provider_since')->nullable()->after('is_service_provider');
            }
            if (!Schema::hasColumn('users', 'service_provider_verified')) {
                $table->boolean('service_provider_verified')->default(false)->after('service_provider_since');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_services');
        
        Schema::table('users', function (Blueprint $table) {
            $columns = ['is_service_provider', 'service_provider_since', 'service_provider_verified'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
