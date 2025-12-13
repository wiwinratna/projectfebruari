<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $sports = [
            ['code' => 'SOC', 'name' => 'Soccer / Football'],
            ['code' => 'BKB', 'name' => 'Basketball'],
            ['code' => 'BDM', 'name' => 'Badminton'],
            ['code' => 'ATH', 'name' => 'Athletics'],
            ['code' => 'ESP', 'name' => 'Esports'],
            ['code' => 'SWM', 'name' => 'Swimming'],
            ['code' => 'WLF', 'name' => 'Weightlifting'],
            ['code' => 'VOLL', 'name' => 'Volleyball'],
            ['code' => 'CYC', 'name' => 'Cycling'],
            ['code' => 'ARC', 'name' => 'Archery'],
        ];

        foreach ($sports as $sport) {
            DB::table('sports')->updateOrInsert(
                ['code' => $sport['code']],
                array_merge($sport, [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
