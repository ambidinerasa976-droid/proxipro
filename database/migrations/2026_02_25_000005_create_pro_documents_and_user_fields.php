<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pro_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // insurance, kbis, certificate, diploma, other
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size')->default(0);
            $table->string('mime_type')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['valid', 'expired', 'pending_review'])->default('pending_review');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
        });

        // Add pro-specific fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('pro_onboarding_completed')->default(false)->after('profile_completed');
            $table->timestamp('pro_onboarding_completed_at')->nullable()->after('pro_onboarding_completed');
            $table->string('pro_subscription_plan')->nullable()->after('pro_onboarding_completed_at');
            $table->boolean('pro_notifications_realtime')->default(true)->after('pro_subscription_plan');
            $table->json('pro_service_categories')->nullable()->after('pro_notifications_realtime');
            $table->integer('pro_intervention_radius')->default(30)->after('pro_service_categories');
            $table->string('pro_status')->nullable()->after('pro_intervention_radius'); // particulier, auto_entrepreneur, entreprise
            $table->string('website_url')->nullable()->after('bio');
            $table->json('social_links')->nullable()->after('website_url');
            $table->string('insurance_number')->nullable()->after('siret');
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('insurance_number');
            $table->text('specialties')->nullable()->after('hourly_rate');
            $table->integer('years_experience')->nullable()->after('specialties');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_documents');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pro_onboarding_completed', 'pro_onboarding_completed_at',
                'pro_subscription_plan', 'pro_notifications_realtime',
                'pro_service_categories', 'pro_intervention_radius',
                'pro_status', 'website_url', 'social_links',
                'insurance_number', 'hourly_rate', 'specialties', 'years_experience'
            ]);
        });
    }
};
