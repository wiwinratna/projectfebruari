<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accreditation_mappings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('job_category_id');
            $table->unsignedBigInteger('accreditation_id');

            $table->timestamps();

            $table->unique(['event_id', 'job_category_id'], 'uniq_event_jobcat');

            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('job_category_id')->references('id')->on('job_categories')->cascadeOnDelete();
            $table->foreign('accreditation_id')->references('id')->on('accreditations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditation_mappings');
    }
};