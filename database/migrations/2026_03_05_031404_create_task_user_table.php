<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the pivot table
        Schema::create('task_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['task_id', 'user_id']);
        });

        // 2. Transfer existing data from tasks.user_id → task_user
        DB::statement('INSERT INTO task_user (task_id, user_id, created_at, updated_at) SELECT id, user_id, NOW(), NOW() FROM tasks WHERE user_id IS NOT NULL');

        // 3. Drop the old user_id column from tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add user_id column to tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // Transfer data back from pivot to tasks.user_id (take first assigned user)
        DB::statement('UPDATE tasks SET user_id = (SELECT user_id FROM task_user WHERE task_user.task_id = tasks.id LIMIT 1)');

        // Drop pivot table
        Schema::dropIfExists('task_user');
    }
};
