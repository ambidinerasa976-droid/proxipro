<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // id_card, passport, driver_license
            $table->string('document_front')->nullable();
            $table->string('document_back')->nullable();
            $table->string('selfie')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Add verification status to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'identity_verified')) {
                $table->boolean('identity_verified')->default(false)->after('is_verified');
            }
            if (!Schema::hasColumn('users', 'identity_verified_at')) {
                $table->timestamp('identity_verified_at')->nullable()->after('identity_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['identity_verified', 'identity_verified_at']);
        });
    }
};
