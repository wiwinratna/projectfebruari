<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('landing_section_configs', function (Blueprint $table) {
            $table->string('chip_text_1')->nullable()->after('extra_text_3');
            $table->string('chip_text_2')->nullable()->after('chip_text_1');
            $table->string('chip_text_3')->nullable()->after('chip_text_2');
            $table->string('cta_text')->nullable()->after('chip_text_3');
            $table->string('mission_title')->nullable()->after('cta_text');
            $table->string('vision_title')->nullable()->after('mission_title');
        });
    }

    public function down(): void
    {
        Schema::table('landing_section_configs', function (Blueprint $table) {
            $table->dropColumn([
                'chip_text_1',
                'chip_text_2',
                'chip_text_3',
                'cta_text',
                'mission_title',
                'vision_title',
            ]);
        });
    }
};
