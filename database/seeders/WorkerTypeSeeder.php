<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workerTypes = [
            [
                'name' => 'VO',
                'description' => 'Volunteer Officer',
            ],
            [
                'name' => 'LO',
                'description' => 'Liaison Officer',
            ],
        ];

        foreach ($workerTypes as $workerType) {
            DB::table('worker_types')->insert($workerType);
        }
    }
}