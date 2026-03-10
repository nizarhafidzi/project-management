<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workforce_monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->integer('total_working_days')->default(0);
            $table->integer('attended_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->decimal('total_utilization', 5, 2)->default(0);
            $table->json('project_details')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'month', 'year'], 'workforce_summary_unique');
            $table->index(['month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workforce_monthly_summaries');
    }
};
