<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration only adds the FK constraint.
     * The column already exists from a previous session.
     */
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            // Ensure application_id is nullable
            $table->unsignedBigInteger('application_id')->nullable()->change();
            
            // Add FK from card_recipient_id to card_recipients
            $table->foreign('card_recipient_id')->references('id')->on('card_recipients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign(['card_recipient_id']);
            $table->unsignedBigInteger('application_id')->nullable(false)->change();
        });
    }
};
