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
        Schema::create('access_card_access_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_card_id')->constrained('access_cards')->cascadeOnDelete();
            $table->foreignId('event_access_code_id')->constrained('event_access_codes')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['access_card_id', 'event_access_code_id'], 'ac_eac_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_card_access_codes');
    }
};
