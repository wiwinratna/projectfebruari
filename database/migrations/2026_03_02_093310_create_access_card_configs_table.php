<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_card_configs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('accreditation_mapping_id');

            $table->unsignedBigInteger('transportation_code_id')->nullable();
            $table->unsignedBigInteger('accommodation_code_id')->nullable();

            $table->string('keterangan', 1000)->nullable();
            $table->timestamps();

            // indexes (short names)
            $table->index('event_id', 'accfg_ev_idx');
            $table->index('accreditation_mapping_id', 'accfg_map_idx');
            $table->index('transportation_code_id', 'accfg_tr_idx');
            $table->index('accommodation_code_id', 'accfg_acm_idx');

            // unique: 1 mapping = 1 config per event
            $table->unique(['event_id', 'accreditation_mapping_id'], 'accfg_ev_map_uniq');

            // FKs (short names)
            $table->foreign('event_id', 'accfg_ev_fk')
                ->references('id')->on('events')
                ->onDelete('cascade');

            $table->foreign('accreditation_mapping_id', 'accfg_map_fk')
                ->references('id')->on('accreditation_mappings')
                ->onDelete('cascade');

            $table->foreign('transportation_code_id', 'accfg_tr_fk')
                ->references('id')->on('transportation_codes')
                ->nullOnDelete();

            $table->foreign('accommodation_code_id', 'accfg_acm_fk')
                ->references('id')->on('accommodation_codes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_card_configs');
    }
};