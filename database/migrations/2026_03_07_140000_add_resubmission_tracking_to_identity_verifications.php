<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('identity_verifications', 'resubmitted_at')) {
                $table->timestamp('resubmitted_at')->nullable()->after('submitted_at');
            }
            if (!Schema::hasColumn('identity_verifications', 'resubmission_count')) {
                $table->unsignedInteger('resubmission_count')->default(0)->after('resubmitted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->dropColumn(['resubmitted_at', 'resubmission_count']);
        });
    }
};
