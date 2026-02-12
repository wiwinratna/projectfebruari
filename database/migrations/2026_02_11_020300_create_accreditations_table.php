<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accreditations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('jabatan_id')->constrained('jabatan')->cascadeOnDelete();
            $table->string('nama_akreditasi');
            $table->string('warna')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'jabatan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditations');
    }
};
