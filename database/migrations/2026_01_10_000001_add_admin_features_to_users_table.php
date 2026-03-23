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
        Schema::table('users', function (Blueprint $table) {
            // Colonne pour le type d'utilisateur (particulier, professionnel, entreprise)
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->string('user_type', 30)->default('particulier')->after('role');
            }
            
            // Colonne pour les privilèges admin (JSON)
            if (!Schema::hasColumn('users', 'admin_privileges')) {
                $table->json('admin_privileges')->nullable()->after('role');
            }
            
            // Colonne soft delete si elle n'existe pas
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'admin_privileges']);
            $table->dropSoftDeletes();
        });
    }
};
