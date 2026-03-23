<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add per-document status columns to identity_verifications
        Schema::table('identity_verifications', function (Blueprint $table) {
            // Per-document statuses: pending, approved, rejected
            if (!Schema::hasColumn('identity_verifications', 'document_front_status')) {
                $table->string('document_front_status', 20)->default('pending')->after('document_front');
            }
            if (!Schema::hasColumn('identity_verifications', 'document_front_rejection_reason')) {
                $table->text('document_front_rejection_reason')->nullable()->after('document_front_status');
            }
            if (!Schema::hasColumn('identity_verifications', 'document_back_status')) {
                $table->string('document_back_status', 20)->default('pending')->after('document_back');
            }
            if (!Schema::hasColumn('identity_verifications', 'document_back_rejection_reason')) {
                $table->text('document_back_rejection_reason')->nullable()->after('document_back_status');
            }
            if (!Schema::hasColumn('identity_verifications', 'selfie_status')) {
                $table->string('selfie_status', 20)->default('pending')->after('selfie');
            }
            if (!Schema::hasColumn('identity_verifications', 'selfie_rejection_reason')) {
                $table->text('selfie_rejection_reason')->nullable()->after('selfie_status');
            }
            // Professional documents (Kbis, SIRENE)
            if (!Schema::hasColumn('identity_verifications', 'professional_document')) {
                $table->string('professional_document')->nullable()->after('selfie_rejection_reason');
            }
            if (!Schema::hasColumn('identity_verifications', 'professional_document_type')) {
                $table->string('professional_document_type', 30)->nullable()->after('professional_document');
            }
            if (!Schema::hasColumn('identity_verifications', 'professional_document_status')) {
                $table->string('professional_document_status', 20)->default('pending')->after('professional_document_type');
            }
            if (!Schema::hasColumn('identity_verifications', 'professional_document_rejection_reason')) {
                $table->text('professional_document_rejection_reason')->nullable()->after('professional_document_status');
            }
            // Admin message to user
            if (!Schema::hasColumn('identity_verifications', 'admin_message')) {
                $table->text('admin_message')->nullable()->after('rejection_reason');
            }
            // Status: add 'returned' for sent back to user
            // We'll handle this in the model - the column already exists as enum, we need to alter it
        });

        // Create notifications table (Laravel standard)
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // The status column is already VARCHAR in SQLite, so 'returned' value is natively supported
        // No need to alter it for SQLite. For MySQL, the varchar type also supports it.
    }

    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $columns = [
                'document_front_status', 'document_front_rejection_reason',
                'document_back_status', 'document_back_rejection_reason',
                'selfie_status', 'selfie_rejection_reason',
                'professional_document', 'professional_document_type',
                'professional_document_status', 'professional_document_rejection_reason',
                'admin_message',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('identity_verifications', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
