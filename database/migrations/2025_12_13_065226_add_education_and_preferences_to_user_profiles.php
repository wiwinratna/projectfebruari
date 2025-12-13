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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('last_education')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('university')->nullable();
            $table->year('graduation_year')->nullable();
            $table->text('skills')->nullable(); // stored as comma separated
            $table->text('languages')->nullable(); // stored as comma separated
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'last_education',
                'field_of_study',
                'university',
                'graduation_year',
                'skills',
                'languages'
            ]);
        });
    }
};
