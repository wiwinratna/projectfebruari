<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_access_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('code');                 // contoh: VIP, GATE-A, ROOM-1
            $table->string('label');                // arti: VIP Area, Gate A, Ruang 1
            $table->string('color_hex', 20)->default('#3B82F6'); // warna
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_access_codes');
    }
};
