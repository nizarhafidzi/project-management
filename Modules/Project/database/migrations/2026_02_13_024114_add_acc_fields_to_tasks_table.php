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
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('acc_file_urn')->nullable()->after('acc_file_name');
            $table->integer('acc_file_version')->nullable()->after('acc_file_urn');
            $table->integer('acc_latest_version')->nullable()->after('acc_file_version');
            $table->timestamp('acc_last_synced_at')->nullable()->after('acc_latest_version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'acc_file_urn',
                'acc_file_version',
                'acc_latest_version',
                'acc_last_synced_at',
            ]);
        });
    }
};
