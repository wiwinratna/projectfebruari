<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('accommodation_codes', function (Blueprint $table) {
            $table->string('icon_key')->nullable()->after('kode');
            $table->boolean('show_icon')->default(true)->after('icon_key');
            $table->boolean('show_code')->default(true)->after('show_icon');
        });
    }

    public function down(): void
    {
        Schema::table('accommodation_codes', function (Blueprint $table) {
            $table->dropColumn(['icon_key', 'show_icon', 'show_code']);
        });
    }
};