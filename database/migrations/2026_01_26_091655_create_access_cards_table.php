<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('access_cards', function (Blueprint $table) {
      $table->id();

      $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
      $table->foreignId('worker_opening_id')->constrained('worker_openings')->cascadeOnDelete();

      $table->string('registration_code', 40)->unique();
      $table->timestamp('issued_at')->nullable();

      $table->timestamps();

      $table->unique('application_id'); // 1 lamaran = 1 kartu
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('access_cards');
  }
};
