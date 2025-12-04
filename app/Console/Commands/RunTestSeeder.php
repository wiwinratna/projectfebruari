<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\TestEventSeeder;
use App\Services\EventStatusService;

class RunTestSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run-seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run test event seeder and update all event statuses automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Test Event Seeder...');
        
        // Run the seeder
        $seeder = new TestEventSeeder();
        $seeder->run();
        
        $this->info('');
        $this->info('🔄 Updating all event statuses automatically...');
        
        // Update all event statuses
        $updatedCount = EventStatusService::updateAllStatuses();
        
        $this->info("✅ Updated $updatedCount event statuses automatically");
        
        // Show current status summary
        $this->info('');
        $this->info('📊 Current Event Status Summary:');
        
        $events = \App\Models\Event::all();
        $statusCounts = $events->groupBy('status')->map->count();
        
        foreach ($statusCounts as $status => $count) {
            $visibility = in_array($status, ['active', 'upcoming']) ? '👥 (Customer Visible)' : '🔒 (Admin Only)';
            $this->line("   $status: $count events $visibility");
        }
        
        $customerVisible = EventStatusService::getCustomerVisibleEvents()->count();
        $adminTotal = EventStatusService::getAdminEvents()->count();
        
        $this->info('');
        $this->info("👥 Events visible to customers: $customerVisible");
        $this->info("🔒 Total events for admin: $adminTotal");
        
        $this->info('');
        $this->info('✅ Test seeder completed successfully!');
        
        return Command::SUCCESS;
    }
}