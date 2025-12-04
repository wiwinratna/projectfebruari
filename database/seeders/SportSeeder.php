<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $sports = [
            ['code' => 'ATH', 'name' => 'Athletics'],
            ['code' => 'SWI', 'name' => 'Swimming'],
            ['code' => 'BDM', 'name' => 'Badminton'],
            ['code' => 'GYM', 'name' => 'Gymnastics'],
            ['code' => 'BSK', 'name' => 'Basketball'],
            ['code' => 'VOL', 'name' => 'Volleyball'],
        ];

        foreach ($sports as $sport) {
            DB::table('sports')->insert(array_merge($sport, [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
