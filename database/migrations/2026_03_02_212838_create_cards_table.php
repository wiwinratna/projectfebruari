<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('application_id');

            // snapshot mapping yang dipakai saat draft dibuat
            $table->unsignedBigInteger('accreditation_mapping_id');

            // config default akses yang dipakai (dari access_card_configs)
            $table->unsignedBigInteger('access_card_config_id')->nullable();

            $table->enum('status', ['draft', 'issued', 'revoked'])->default('draft');

            // issuance fields
            $table->string('card_number', 50)->nullable();
            $table->char('qr_token', 64)->nullable();
            $table->text('qr_payload')->nullable();
            $table->char('signature', 64)->nullable();

            $table->timestamp('issued_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();

            // printing fields
            $table->timestamp('printed_at')->nullable();
            $table->unsignedBigInteger('printed_by')->nullable();

            // snapshot data untuk print agar stabil walau applicant berubah
            $table->json('snapshot')->nullable();

            $table->timestamps();

            // constraints
            $table->unique(['event_id', 'application_id'], 'cards_event_application_unique');

            $table->index(['event_id', 'status'], 'cards_event_status_idx');
            $table->index(['accreditation_mapping_id'], 'cards_mapping_idx');

            // FKs (sesuaikan nama pk jika beda)
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('application_id')->references('id')->on('applications')->cascadeOnDelete();
            $table->foreign('accreditation_mapping_id')->references('id')->on('accreditation_mappings')->restrictOnDelete();
            $table->foreign('access_card_config_id')->references('id')->on('access_card_configs')->nullOnDelete();

            $table->foreign('issued_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('printed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};