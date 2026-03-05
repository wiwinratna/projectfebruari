<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('card_layouts', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('event_id');
            $table->string('name')->default('Default Layout');
            $table->boolean('is_active')->default(false);
            $table->integer('version')->default(1);
            
            // Layout JSON structure:
            // {
            //   "schemaVersion": 1,
            //   "contentArea": { "xMm": 10, "yMm": 10, "wMm": 130, "hMm": 190 },
            //   "elements": [
            //     {
            //       "id": "photo",
            //       "type": "photo",
            //       "visible": true,
            //       "rect": { "xMm": 20, "yMm": 20, "wMm": 30, "hMm": 40 },
            //       "style": { ... }
            //     },
            //     ...
            //   ]
            // }
            $table->json('layout_json');
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
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
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_layouts');
    }
};
