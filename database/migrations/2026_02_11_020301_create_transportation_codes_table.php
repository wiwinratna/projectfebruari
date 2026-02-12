<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transportation_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('kode');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('event_id');
            $table->unique(['event_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transportation_codes');
    }
};