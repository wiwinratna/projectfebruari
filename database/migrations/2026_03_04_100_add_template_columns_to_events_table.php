<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Logo path untuk event
            $table->string('logo_path')->nullable()->after('stage');
            
            // Card template background image path
            $table->string('card_template_path')->nullable()->after('logo_path');
            
            // Timestamp for last template upload
            $table->timestamp('card_template_updated_at')->nullable()->after('card_template_path');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'card_template_path', 'card_template_updated_at']);
        });
    }
};
