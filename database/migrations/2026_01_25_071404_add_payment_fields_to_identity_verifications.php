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
            $table->enum('type', ['profile_verification', 'service_provider'])->default('profile_verification')->after('user_id');
            $table->decimal('payment_amount', 10, 2)->default(10.00)->after('selfie');
            $table->string('payment_id')->nullable()->after('payment_amount');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->after('payment_id');
            $table->datetime('paid_at')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->dropColumn(['type', 'payment_amount', 'payment_id', 'payment_status', 'paid_at']);
        });
    }
};
