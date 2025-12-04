<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class DebugCityRelation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:city';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug city relationships in events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Debugging city relationships...');
        
        // Load events like the controller does
        $events = Event::with([
                'sports',
                'city',
                'workerOpenings' => function ($query) {
                    $query->select('id', 'event_id', 'status', 'slots_total', 'slots_filled');
                },
            ])
            ->withCount([
                'workerOpenings',
                'applications',
            ])
            ->withSum('workerOpenings as slots_total_sum', 'slots_total')
            ->orderBy('start_at')
            ->get();
        
        foreach ($events as $event) {
            $this->line("📅 Event: {$event->title}");
            $this->line("   city_id: {$event->city_id}");
            $this->line("   city relationship loaded: " . ($event->relationLoaded('city') ? 'YES' : 'NO'));
            $this->line("   city object: " . ($event->city ? 'EXISTS' : 'NULL'));
            if ($event->city) {
                $this->line("   city->name: " . ($event->city->name ?? 'NO NAME'));
                $this->line("   city->province: " . ($event->city->province ?? 'NO PROVINCE'));
            }
            $this->line("   city_name attribute: " . ($event->city_name ?? 'NO ATTRIBUTE'));
            $this->line("---");
        }
        
        $this->info('✅ Debug completed!');
        
        return Command::SUCCESS;
    }
}
