<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventStatusService;
use App\Models\Event;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:event-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all event statuses based on current date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Updating event statuses based on current date...');
        
        $beforeCount = Event::count();
        $updated = EventStatusService::updateAllStatuses();
        
        $this->info("✅ Updated {$updated} out of {$beforeCount} events");
        
        // Show updated events
        $events = Event::orderBy('start_at')->get();
        foreach ($events as $event) {
            $this->line("📅 {$event->title} - Status: {$event->status}");
        }
        
        return Command::SUCCESS;
    }
}
