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
            if (!Schema::hasColumn('users', 'plan')) {
                $table->string('plan', 20)->default('FREE')->after('points');
            }
            if (!Schema::hasColumn('users', 'subscription_end')) {
                $table->timestamp('subscription_end')->nullable()->after('plan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'subscription_end']);
        });
    }
};
