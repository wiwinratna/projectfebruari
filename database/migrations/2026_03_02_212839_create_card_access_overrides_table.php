<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('card_access_overrides', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('card_id');

            $table->enum('type', ['venue', 'zone', 'transportation', 'accommodation']);
            $table->unsignedBigInteger('ref_id'); // id dari master data terkait
            $table->enum('action', ['add', 'remove'])->default('add');

            // biar riwayat jelas: ini dari default config atau custom admin
            $table->enum('source', ['default', 'custom'])->default('custom');

            $table->unsignedBigInteger('changed_by')->nullable(); // admin user id (optional)
            $table->timestamps();

            // Anti-double: tidak boleh ada record sama persis
            $table->unique(['card_id', 'type', 'ref_id', 'action'], 'card_overrides_unique');

            $table->index(['card_id', 'type'], 'card_overrides_card_type_idx');

            $table->foreign('card_id')->references('id')->on('cards')->cascadeOnDelete();
            $table->foreign('changed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_access_overrides');
    }
};