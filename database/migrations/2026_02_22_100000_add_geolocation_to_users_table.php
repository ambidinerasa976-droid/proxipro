<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute les champs de géolocalisation aux utilisateurs
     * pour le système de détection automatique de position
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('city');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('detected_city')->nullable()->after('longitude');
            $table->string('detected_country')->nullable()->after('detected_city');
            $table->integer('geo_radius')->default(50)->after('detected_country'); // km
            $table->string('geo_source')->nullable()->after('geo_radius'); // ip, profile, browser
            $table->timestamp('geo_detected_at')->nullable()->after('geo_source');
            
            // Index pour les recherches géographiques
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropColumn([
                'latitude', 'longitude', 'detected_city', 
                'detected_country', 'geo_radius', 'geo_source', 'geo_detected_at'
            ]);
        });
    }
};
