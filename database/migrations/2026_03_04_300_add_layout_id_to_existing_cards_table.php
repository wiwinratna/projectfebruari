<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Check if column exists to avoid duplicate column error
        if (!Schema::hasColumn('cards', 'layout_id')) {
            Schema::table('cards', function (Blueprint $table) {
                $table->unsignedBigInteger('layout_id')->nullable()->after('access_card_config_id');
                
                // Add foreign key after column exists
                $table->foreign('layout_id')
                    ->references('id')
                    ->on('card_layouts')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            if (Schema::hasColumn('cards', 'layout_id')) {
                $table->dropForeign(['layout_id']);
                $table->dropColumn('layout_id');
            }
        });
    }
};
