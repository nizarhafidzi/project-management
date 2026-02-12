<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->date('period_date');
            $table->decimal('planned_progress', 8, 2)->default(0);
            $table->decimal('actual_progress', 8, 2)->nullable();
            $table->timestamps();

            $table->index(['project_id', 'period_date']);
            $table->unique(['project_id', 'period_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_plans');
    }
};
