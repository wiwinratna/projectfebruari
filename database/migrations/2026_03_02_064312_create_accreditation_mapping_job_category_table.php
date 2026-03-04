<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accreditation_mapping_job_category', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('accreditation_mapping_id');
            $table->unsignedBigInteger('job_category_id');

            $table->timestamps();

            // index pendek
            $table->index('event_id', 'amjc_event_idx');
            $table->index('accreditation_mapping_id', 'amjc_map_idx');
            $table->index('job_category_id', 'amjc_job_idx');

            // RULE utama: dalam 1 event, job_category cuma boleh muncul sekali
            $table->unique(['event_id', 'job_category_id'], 'amjc_ev_job_uniq');

            // optional: mencegah duplikat yang sama persis
            $table->unique(['accreditation_mapping_id', 'job_category_id'], 'amjc_map_job_uniq');

            // FK pendek
            $table->foreign('event_id', 'amjc_event_fk')
                ->references('id')->on('events')
                ->onDelete('cascade');

            $table->foreign('accreditation_mapping_id', 'amjc_map_fk')
                ->references('id')->on('accreditation_mappings')
                ->onDelete('cascade');

            $table->foreign('job_category_id', 'amjc_job_fk')
                ->references('id')->on('job_categories')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditation_mapping_job_category');
    }
};