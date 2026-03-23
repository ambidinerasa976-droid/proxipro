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
        Schema::table('ads', function (Blueprint $table) {
            // Champ urgent - uniquement pour les utilisateurs premium
            if (!Schema::hasColumn('ads', 'is_urgent')) {
                $table->boolean('is_urgent')->default(false)->after('boost_type');
            }
            if (!Schema::hasColumn('ads', 'urgent_until')) {
                $table->timestamp('urgent_until')->nullable()->after('is_urgent');
            }
            // Priorité d'affichage pour le sidebar gauche (1=urgent premium, 2=boosted, 3=urgent standard)
            if (!Schema::hasColumn('ads', 'sidebar_priority')) {
                $table->tinyInteger('sidebar_priority')->default(0)->after('urgent_until');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['is_urgent', 'urgent_until', 'sidebar_priority']);
        });
    }
};
