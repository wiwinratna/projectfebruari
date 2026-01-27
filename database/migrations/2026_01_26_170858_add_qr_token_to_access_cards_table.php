<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('access_cards', function (Blueprint $table) {
            $table->string('qr_token', 64)
                  ->nullable()
                  ->unique()
                  ->after('registration_code');
        });
    }

    public function down(): void
    {
        Schema::table('access_cards', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn('qr_token');
        });
    }
};
