<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('accreditation_mappings', function (Blueprint $table) {
            // ✅ nama_akreditasi unik per event
            $table->unique(['event_id', 'nama_akreditasi'], 'am_ev_name_uniq');
        });
    }

    public function down(): void
    {
        Schema::table('accreditation_mappings', function (Blueprint $table) {
            $table->dropUnique('am_ev_name_uniq');
        });
    }
};