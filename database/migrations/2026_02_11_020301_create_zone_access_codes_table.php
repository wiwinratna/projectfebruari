<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zone_access_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('kode_zona');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('event_id');
            $table->unique(['event_id', 'kode_zona']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zone_access_codes');
    }
};
