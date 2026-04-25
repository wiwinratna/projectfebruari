<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landing_section_items', function (Blueprint $table) {
            $table->id();
            $table->string('section', 30);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('emoji', 20)->nullable();
            $table->string('highlight', 100)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['section', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_section_items');
    }
};
