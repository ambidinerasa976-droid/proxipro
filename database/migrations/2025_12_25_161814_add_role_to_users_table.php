<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin', 'moderator'])->default('user');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'bio', 'is_verified', 'is_active']);
        });
    }
};
