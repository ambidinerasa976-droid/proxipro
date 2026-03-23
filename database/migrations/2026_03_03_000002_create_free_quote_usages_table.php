<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_quote_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45);
            $table->string('fingerprint', 64)->nullable();
            $table->string('document_type', 10); // quote or invoice
            $table->timestamps();

            $table->index('ip_address');
            $table->index('fingerprint');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_quote_usages');
    }
};
