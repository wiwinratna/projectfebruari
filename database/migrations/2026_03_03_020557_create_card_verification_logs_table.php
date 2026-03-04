<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('card_verification_logs', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('card_id');
      $table->string('qr_token', 128);
      $table->string('visitor_name', 120);
      $table->string('phone', 50)->nullable();
      $table->text('note')->nullable();

      // opsional tapi kepake banget buat audit
      $table->string('ip_address', 64)->nullable();
      $table->string('user_agent', 255)->nullable();

      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable();

      $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
      $table->index(['qr_token']);
      $table->index(['card_id', 'created_at']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('card_verification_logs');
  }
};