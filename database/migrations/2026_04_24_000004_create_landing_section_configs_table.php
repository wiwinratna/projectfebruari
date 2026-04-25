<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landing_section_configs', function (Blueprint $table) {
            $table->id();
            $table->string('section', 30)->unique();
            $table->string('badge_text')->nullable();
            $table->string('title_text')->nullable();
            $table->text('subtitle_text')->nullable();
            $table->text('extra_text')->nullable();
            $table->text('extra_text_2')->nullable();
            $table->text('extra_text_3')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_section_configs');
    }
};
