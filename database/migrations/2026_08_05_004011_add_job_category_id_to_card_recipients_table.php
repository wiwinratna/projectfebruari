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
        Schema::table('card_recipients', function (Blueprint $table) {
            $table->unsignedBigInteger('job_category_id')->nullable()->after('event_id');
            $table->unsignedBigInteger('accreditation_mapping_id')->nullable()->after('job_category_id');

            $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('set null');
            $table->foreign('accreditation_mapping_id')->references('id')->on('accreditation_mappings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_recipients', function (Blueprint $table) {
            $table->dropForeign(['job_category_id']);
            $table->dropForeign(['accreditation_mapping_id']);
            $table->dropColumn(['job_category_id', 'accreditation_mapping_id']);
        });
    }
};
