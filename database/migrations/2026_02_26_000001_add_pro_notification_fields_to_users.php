<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Extended notification preferences
            $table->boolean('pro_notifications_email')->default(true)->after('pro_notifications_realtime');
            $table->boolean('pro_notifications_sms')->default(false)->after('pro_notifications_email');
            
            // Onboarding step tracking (0 = not started, 1-6 = current step, 7 = completed)
            $table->tinyInteger('pro_onboarding_step')->default(0)->after('pro_onboarding_completed');
            
            // Onboarding skipped flag (user closed without completing)
            $table->boolean('pro_onboarding_skipped')->default(false)->after('pro_onboarding_step');
            
            // Phone for SMS
            $table->string('pro_phone_sms')->nullable()->after('pro_notifications_sms');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pro_notifications_email',
                'pro_notifications_sms',
                'pro_onboarding_step',
                'pro_onboarding_skipped',
                'pro_phone_sms',
            ]);
        });
    }
};
