<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('worker_opening_access_codes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('worker_opening_id')->constrained('worker_openings')->cascadeOnDelete();
      $table->foreignId('event_access_code_id')->constrained('event_access_codes')->cascadeOnDelete();
      $table->timestamps();

    $table->unique(['worker_opening_id', 'event_access_code_id'], 'wo_acc_unique');

    });
  }

  public function down(): void
  {
    Schema::dropIfExists('worker_opening_access_codes');
  }
};
