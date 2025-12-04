<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->string('venue')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('status')->default('draft');
            $table->string('stage')->default('province'); // province, national, asean/sea, asia, world
            $table->string('penyelenggara')->nullable();
            $table->string('instagram')->nullable();
            $table->string('email')->nullable();

            $table->timestamps();

            $table->index(['status', 'start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};