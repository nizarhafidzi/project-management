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
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->decimal('progress_increment', 5, 2)->default(0);
            $table->boolean('is_backdate')->default(false);
            $table->string('approval_status')->default('approved'); // approved, pending, rejected
            $table->string('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'log_date']);
            $table->index('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};
