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
            // Qui peut répondre : everyone (tous), pro_only (pro uniquement), verified_only (vérifiés uniquement)
            $table->string('reply_restriction', 20)->default('everyone')->after('shares_count');
            // Visibilité : public (page publique), pro_targeted (envoyé aux pros par catégorie)
            $table->string('visibility', 20)->default('public')->after('reply_restriction');
            // Catégories ciblées (pour visibility = pro_targeted)
            $table->json('target_categories')->nullable()->after('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['reply_restriction', 'visibility', 'target_categories']);
        });
    }
};
