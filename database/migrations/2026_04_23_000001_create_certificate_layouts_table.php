<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificate_layouts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id');
            $table->string('name')->default('Default Layout');

            // draft = can be edited | published = locked (immutable)
            $table->enum('status', ['draft', 'published'])->default('draft');

            // Only one layout can be the "active" one used for generation
            $table->boolean('is_active')->default(false);
            $table->integer('version')->default(1);

            // Layout JSON — same schema as card_layouts but for certificate elements
            // Elements: volunteer_name, volunteer_role, event_name, event_period,
            //           issue_date, event_logo, org_logo, qr_code
            // Canvas: 297mm × 210mm (A4 Landscape)
            $table->json('layout_json');

            // Asset paths stored in the layout for rendering
            $table->string('background_path')->nullable();
            $table->string('event_logo_path')->nullable();
            $table->string('org_logo_path')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // If this layout was duplicated from another
            $table->unsignedBigInteger('duplicated_from')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->cascadeOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Indexes
            $table->index(['event_id', 'is_active']);
            $table->index(['event_id', 'status']);
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_layouts');
    }
};
