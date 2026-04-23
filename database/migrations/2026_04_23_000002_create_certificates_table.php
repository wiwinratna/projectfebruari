<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('application_id');

            // Layout used — snapshot at publish time so layout changes don't affect issued certs
            $table->unsignedBigInteger('layout_id')->nullable();

            // Full layout JSON at publish time (immutable — never reads the live layout row again)
            $table->json('layout_snapshot')->nullable();

            // Certificate status
            $table->enum('status', ['draft', 'published', 'revoked'])->default('draft');

            // Internal unique code (NOT shown on certificate face)
            $table->string('cert_code', 80)->nullable()->unique();

            // QR / verification
            $table->char('qr_token', 64)->nullable()->unique();
            $table->text('verify_url')->nullable();

            // HMAC signature for verify_url integrity
            $table->char('signature', 64)->nullable();

            // Resolved data frozen at publish time — used for rendering
            // Fields: volunteer_name, role_label, event_title, event_start_at,
            //         event_end_at, issue_date, event_logo_path, org_logo_path, qr_url
            $table->json('payload')->nullable();

            // Snapshot of raw application/user data at publish time
            $table->json('snapshot')->nullable();

            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamp('issued_at')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // One certificate per application per event
            $table->unique(['event_id', 'application_id'], 'certificates_event_application_unique');

            // Indexes
            $table->index(['event_id', 'status'], 'certificates_event_status_idx');
            $table->index('qr_token', 'certificates_qr_token_idx');

            // Foreign keys
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('application_id')->references('id')->on('applications')->cascadeOnDelete();
            $table->foreign('layout_id')->references('id')->on('certificate_layouts')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
