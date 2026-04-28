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
        Schema::table('events', function (Blueprint $table) {
            $table->string('province_code')->nullable()->after('venue');
            $table->string('province_name')->nullable()->after('province_code');
            $table->string('city_code')->nullable()->after('province_name');
            $table->string('city_name')->nullable()->after('city_code');
            $table->decimal('latitude', 10, 8)->nullable()->after('city_name');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'province_code',
                'province_name',
                'city_code',
                'city_name',
                'latitude',
                'longitude'
            ]);
        });
    }
};
