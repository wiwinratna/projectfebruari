<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database by truncating all tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting database reset...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // List of tables to truncate in order (respecting foreign key dependencies)
        $tables = [
            'applications',
            'event_sport', 
            'worker_openings',
            'events',
            'job_categories',
            'sports',
            'worker_types',
            'cities',
            'users',
        ];
        
        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
                $this->info("Truncated table: $table");
            } catch (\Exception $e) {
                $this->error("Failed to truncate table $table: " . $e->getMessage());
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->info('🎉 Database reset completed! All IDs will start from 1.');
        
        return Command::SUCCESS;
    }
}
