<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Services\EventStatusService;

class TestEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:event-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test event status validation system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Event Status Validation System...');
        
        // Get all events
        $events = Event::all();
        
        foreach ($events as $event) {
            $this->line("📅 Event: {$event->title}");
            $this->line("   Start Date: " . $event->start_at->format('Y-m-d H:i'));
            $this->line("   End Date: " . ($event->end_at ? $event->end_at->format('Y-m-d H:i') : 'Not set'));
            $this->line("   Current Status: {$event->status}");
            
            // Calculate what the status should be
            $calculatedStatus = EventStatusService::calculateStatus($event);
            $this->line("   Calculated Status: {$calculatedStatus}");
            
            // Check if status matches
            if ($event->status === $calculatedStatus) {
                $this->line("   ✅ Status is correct");
            } else {
                $this->line("   ⚠️  Status mismatch - will be updated");
            }
            
            // Check customer visibility
            $visibleToCustomers = EventStatusService::isVisibleToCustomers($event);
            $this->line("   Visible to Customers: " . ($visibleToCustomers ? 'YES' : 'NO'));
            
            $this->line("---");
        }
        
        $this->info('✅ Test completed!');
        
        return Command::SUCCESS;
    }
}
