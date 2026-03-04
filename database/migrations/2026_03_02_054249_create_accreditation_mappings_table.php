<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accreditation_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('nama_akreditasi', 50);   // contoh: VIP, A, B, C, D
            $table->string('warna', 20)->nullable(); // contoh: #3B82F6
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->index(['event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditation_mappings');
    }
};