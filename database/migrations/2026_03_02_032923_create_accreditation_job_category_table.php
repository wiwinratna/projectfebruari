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
    Schema::create('accreditation_job_category', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('accreditation_id');
        $table->unsignedBigInteger('job_category_id');
        $table->timestamps();

        $table->unique(['accreditation_id', 'job_category_id'], 'ajc_acc_job_unique');

        $table->foreign('accreditation_id')->references('id')->on('accreditations')->onDelete('cascade');
        $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accreditation_job_category');
    }
};
