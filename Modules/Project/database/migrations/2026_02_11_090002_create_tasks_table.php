<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->cascadeOnDelete();
            $table->string('wbs_code');
            $table->string('name');
            $table->string('acc_file_name')->nullable();
            $table->decimal('weight', 8, 2)->default(0);
            $table->decimal('coefficient', 8, 4)->default(1.0000);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_progress', 8, 2)->default(0.00);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['project_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
