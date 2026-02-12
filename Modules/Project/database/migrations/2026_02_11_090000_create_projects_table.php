<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_type');
            $table->string('contract_number')->unique();
            $table->string('project_code')->unique();
            $table->string('name');
            $table->string('technical_service');
            $table->string('sector');
            $table->string('acc_project_id')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
