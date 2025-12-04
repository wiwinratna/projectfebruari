<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds to reset all data and start ID from 1.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Define the order of tables to truncate (respecting foreign key dependencies)
        $orderedTables = [
            'applications',           // References users, worker_openings
            'event_sport',           // References events, sports
            'worker_openings',       // References users, events, job_categories
            'events',                // References users, cities
            'job_categories',        // References worker_types
            'sports',                // No dependencies
            'worker_types',          // No dependencies
            'cities',                // No dependencies
            'users',                 // No dependencies (but referenced by others)
        ];
        
        // Truncate each table in the correct order
        foreach ($orderedTables as $table) {
            try {
                DB::table($table)->truncate();
                echo "✅ Truncated table: $table\n";
            } catch (\Exception $e) {
                echo "⚠️  Could not truncate table $table: " . $e->getMessage() . "\n";
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "🎉 Database reset completed! All IDs will start from 1.\n";
    }

}