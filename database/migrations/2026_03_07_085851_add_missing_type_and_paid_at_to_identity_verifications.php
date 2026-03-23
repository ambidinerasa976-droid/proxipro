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
        Schema::table('identity_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('identity_verifications', 'type')) {
                $table->string('type', 30)->default('profile_verification')->after('user_id');
            }
            if (!Schema::hasColumn('identity_verifications', 'paid_at')) {
                $table->datetime('paid_at')->nullable()->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('identity_verifications', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('identity_verifications', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }
};
