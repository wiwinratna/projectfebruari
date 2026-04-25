<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landing_footer_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->default('default');
            $table->text('brand_description')->nullable();
            $table->string('quick_links_title')->nullable();
            $table->string('resource_links_title')->nullable();
            $table->string('connect_title')->nullable();
            $table->json('quick_links')->nullable();
            $table->json('resource_links')->nullable();
            $table->json('legal_links')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('address_text')->nullable();
            $table->string('address_url')->nullable();
            $table->string('phone_text')->nullable();
            $table->string('phone_url')->nullable();
            $table->string('email_text')->nullable();
            $table->string('email_url')->nullable();
            $table->text('copyright_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_footer_configs');
    }
};
