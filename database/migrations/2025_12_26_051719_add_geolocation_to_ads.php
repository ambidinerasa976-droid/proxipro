<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Coordonnées géographiques
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            
            // Adresse complète pour le géocodage
            $table->string('address')->nullable()->after('longitude');
            $table->string('postal_code', 20)->nullable()->after('address');
            $table->string('country', 100)->default('France')->after('postal_code');
            
            // Rayon d'intervention
            $table->integer('radius_km')->default(10)->after('country');
            
            // Index pour les recherches géospatiales
            $table->index(['latitude', 'longitude']);
            $table->index('category');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropIndex(['category']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'latitude', 'longitude', 'address', 
                'postal_code', 'country', 'radius_km'
            ]);
        });
    }
};
