<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'aps_access_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('aps_access_token')->nullable();
                $table->text('aps_refresh_token')->nullable();
                $table->timestamp('aps_token_expires_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['aps_access_token', 'aps_refresh_token', 'aps_token_expires_at']);
        });
    }
};
