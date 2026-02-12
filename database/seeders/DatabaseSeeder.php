<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            WorkerTypeSeeder::class,
            JobCategorySeeder::class,
            SportSeeder::class,
            UserSeeder::class,
            EventSeeder::class,
            EventMasterDataSeeder::class,
            WorkerOpeningSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}
