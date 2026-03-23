<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pro_documents', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
            // Change status to string to support pending/approved/rejected
            $table->string('status_new')->default('pending')->after('status');
        });

        // Copy old status values and convert
        DB::table('pro_documents')->update(['status_new' => DB::raw("CASE 
            WHEN status = 'valid' THEN 'approved' 
            WHEN status = 'expired' THEN 'rejected' 
            WHEN status = 'pending_review' THEN 'pending' 
            ELSE 'pending' END")]);

        Schema::table('pro_documents', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('pro_documents', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    public function down(): void
    {
        Schema::table('pro_documents', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
