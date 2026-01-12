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
        Schema::table('users', function (Blueprint $table) {
            // Add nullable google_id and make password nullable
            $table->string('google_id')->nullable()->unique()->after('email');

            // Make password column nullable - requires doctrine/dbal
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop google_id column and revert password to not nullable
            $table->dropColumn('google_id');

            $table->string('password')->nullable(false)->change();
        });
    }
};
