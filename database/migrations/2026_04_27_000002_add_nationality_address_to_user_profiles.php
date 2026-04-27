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
        Schema::table('user_profiles', function (Blueprint $table) {
            // Nationality type: 'wni' | 'wna'
            $table->string('nationality_type')->nullable()->after('address');

            // WNI - Indonesian structured address
            $table->string('province')->nullable()->after('nationality_type');
            $table->string('city_regency')->nullable()->after('province');
            $table->string('district')->nullable()->after('city_regency');     // Kecamatan
            $table->string('village')->nullable()->after('district');          // Kelurahan/Desa
            $table->string('postal_code')->nullable()->after('village');
            $table->string('rt')->nullable()->after('postal_code');
            $table->string('rw')->nullable()->after('rt');

            // WNA - International address
            $table->string('country')->nullable()->after('rw');
            $table->string('state_region')->nullable()->after('country');

            // professional_headline (may already exist, guard with check)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nationality_type',
                'province',
                'city_regency',
                'district',
                'village',
                'postal_code',
                'rt',
                'rw',
                'country',
                'state_region',
            ]);
        });
    }
};
