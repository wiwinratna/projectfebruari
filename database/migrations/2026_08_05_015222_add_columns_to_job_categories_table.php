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
        Schema::table('job_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('id');
            $table->unsignedBigInteger('jabatan_id')->nullable()->after('event_id');
            $table->string('fa_name')->nullable()->after('jabatan_id');
            
            // Note: If you want to add foreign key constraints:
            // $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            // $table->foreign('jabatan_id')->references('id')->on('jabatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropColumn(['event_id', 'jabatan_id', 'fa_name']);
        });
    }
};
