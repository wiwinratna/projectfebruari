<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_card_config_venues', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('access_card_config_id');
            $table->unsignedBigInteger('venue_access_id');
            $table->timestamps();

            $table->index('access_card_config_id', 'accfgv_cfg_idx');
            $table->index('venue_access_id', 'accfgv_ven_idx');

            $table->unique(['access_card_config_id', 'venue_access_id'], 'accfgv_uniq');

            $table->foreign('access_card_config_id', 'accfgv_cfg_fk')
                ->references('id')->on('access_card_configs')
                ->onDelete('cascade');

            $table->foreign('venue_access_id', 'accfgv_ven_fk')
                ->references('id')->on('venue_accesses')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_card_config_venues');
    }
};