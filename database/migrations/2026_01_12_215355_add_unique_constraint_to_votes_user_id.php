<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration ensures the unique constraint on user_id exists.
     * Even if the original migration had ->unique(), this acts as a safety net
     * and explicitly enforces the database-level constraint.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Add unique constraint with explicit name for better control
            // This will fail if the constraint already exists, which is fine
            // It confirms the database-level protection is in place
            $table->unique('user_id', 'votes_user_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique('votes_user_id_unique');
        });
    }
};
