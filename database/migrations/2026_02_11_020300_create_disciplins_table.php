<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('sport_id')->constrained('sports')->cascadeOnDelete();
            $table->foreignId('venue_location_id')->constrained('venue_locations')->cascadeOnDelete();
            $table->string('nama_disiplin');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'sport_id']);
            $table->index(['event_id', 'venue_location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplins');
    }
};