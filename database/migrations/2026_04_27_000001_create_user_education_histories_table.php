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
        Schema::create('user_education_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('education_level'); // SMA, SMK, D3, S1, S2, S3, Other
            $table->string('institution_name'); // Nama sekolah / universitas
            $table->string('field_of_study')->nullable(); // Jurusan / program studi
            $table->year('graduation_year')->nullable();
            $table->boolean('is_still_studying')->default(false); // Masih kuliah/sekolah
            $table->string('proof_document')->nullable(); // Path to uploaded document
            $table->string('proof_document_original_name')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_education_histories');
    }
};
