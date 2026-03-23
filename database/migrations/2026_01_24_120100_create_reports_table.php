<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('ad_id');
            $table->string('reason');
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
