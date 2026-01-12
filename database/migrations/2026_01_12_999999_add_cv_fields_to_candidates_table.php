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
        Schema::table('candidates', function (Blueprint $table) {
            // Profil Pribadi
            $table->string('birth_place')->nullable()->after('name');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('occupation')->nullable()->comment('Pekerjaan saat ini')->after('birth_date');
            $table->text('address')->nullable()->after('occupation');

            // CV & Rekam Jejak (LongText untuk support Markdown/HTML)
            $table->longText('education_history')->nullable()->after('address');
            $table->longText('organization_experience')->nullable()->after('education_history');

            // Update vision and mission to LongText for richer content
            $table->longText('vision')->nullable()->change();
            $table->longText('mission')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'birth_place',
                'birth_date',
                'occupation',
                'address',
                'education_history',
                'organization_experience',
            ]);
        });
    }
};
