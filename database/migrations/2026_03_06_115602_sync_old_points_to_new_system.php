<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sync old 'points' column to new available_points/total_points columns.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'points')) {
            // Sync: if old 'points' > available_points, add the difference
            \DB::statement("
                UPDATE users 
                SET available_points = available_points + GREATEST(COALESCE(points, 0) - available_points, 0),
                    total_points = total_points + GREATEST(COALESCE(points, 0) - total_points, 0)
                WHERE COALESCE(points, 0) > available_points
            ");
            
            // Reset the old column to match new system
            \DB::statement("UPDATE users SET points = available_points WHERE points IS NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed - data sync only
    }
};
