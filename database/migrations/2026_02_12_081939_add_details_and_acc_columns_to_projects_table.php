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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('contract_value', 15, 2)->nullable();
            $table->string('owner')->nullable();
            $table->string('consultant')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('acc_account_id')->nullable();
            $table->date('contract_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'contract_value',
                'owner',
                'consultant',
                'address',
                'description',
                'start_date',
                'end_date',
                'acc_account_id',
                'contract_date',
            ]);
        });
    }
};
