<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ensure progress-related columns use decimal(5,2) to preserve fractional values.
 *
 * This migration explicitly sets the column type to decimal(5,2) for:
 * - tasks.total_progress
 * - daily_logs.progress_increment
 *
 * Even if they already are decimal, this serves as a safety-net migration
 * to guarantee the schema matches the application's expectations.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('total_progress', 5, 2)->default(0)->change();
        });

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->decimal('progress_increment', 5, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        // Revert to the original wider precision (no-op if already correct)
        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('total_progress', 8, 2)->default(0)->change();
        });

        Schema::table('daily_logs', function (Blueprint $table) {
            $table->decimal('progress_increment', 5, 2)->default(0)->change();
        });
    }
};
