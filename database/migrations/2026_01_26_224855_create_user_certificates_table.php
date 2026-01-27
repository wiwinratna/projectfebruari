<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('file_path');        // lokasi file
            $table->string('original_name');    // nama asli file

            $table->string('title', 150);       // nama lomba / sertifikat
            $table->date('event_date');         // tanggal sertifikat
            $table->enum('stage', [
                'province',
                'national',
                'asean_sea',
                'asia',
                'world'
            ]);

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('user_certificates');
    }
};
