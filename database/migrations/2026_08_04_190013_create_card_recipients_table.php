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
        Schema::create('card_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('event_id')->nullable();
            
            $table->string('name');
            $table->string('population')->nullable();
            $table->string('category')->nullable();
            
            $table->string('venue_access')->nullable();
            $table->string('zone_access')->nullable();
            $table->string('seating_access')->nullable();
            $table->string('transport')->nullable();
            
            $table->string('identity_number')->nullable();
            $table->string('photo_path')->nullable();
            
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_recipients');
    }
};
