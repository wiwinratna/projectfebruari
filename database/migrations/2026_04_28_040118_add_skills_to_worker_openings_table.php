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
        Schema::table('worker_openings', function (Blueprint $table) {
            $table->json('required_skills')->nullable()->after('requirements');
            $table->json('preferred_skills')->nullable()->after('required_skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worker_openings', function (Blueprint $table) {
            $table->dropColumn(['required_skills', 'preferred_skills']);
        });
    }
};
