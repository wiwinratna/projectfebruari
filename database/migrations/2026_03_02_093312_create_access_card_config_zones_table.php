<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_card_config_zones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('access_card_config_id');
            $table->unsignedBigInteger('zone_access_code_id');
            $table->timestamps();

            $table->index('access_card_config_id', 'accfgz_cfg_idx');
            $table->index('zone_access_code_id', 'accfgz_zone_idx');

            $table->unique(['access_card_config_id', 'zone_access_code_id'], 'accfgz_uniq');

            $table->foreign('access_card_config_id', 'accfgz_cfg_fk')
                ->references('id')->on('access_card_configs')
                ->onDelete('cascade');

            $table->foreign('zone_access_code_id', 'accfgz_zone_fk')
                ->references('id')->on('zone_access_codes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_card_config_zones');
    }
};