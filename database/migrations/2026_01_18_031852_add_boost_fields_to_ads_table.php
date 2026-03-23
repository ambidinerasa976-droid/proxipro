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
            $table->boolean('is_boosted')->default(false)->after('is_pinned');
            $table->timestamp('boost_end')->nullable()->after('is_boosted');
            $table->string('boost_type')->nullable()->after('boost_end'); // 'standard', 'premium', 'vip'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['is_boosted', 'boost_end', 'boost_type']);
        });
    }
};
