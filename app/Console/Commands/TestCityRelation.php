<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\City;

class TestCityRelation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:city';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test city relationships in events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing city relationships...');
        
        $events = Event::with('city')->get();
        
        foreach ($events as $event) {
            $this->line("📅 Event: {$event->title}");
            $this->line("   City ID: {$event->city_id}");
            $this->line("   City Name: " . ($event->city ? $event->city->name : 'NOT FOUND'));
            $this->line("   Province: " . ($event->city ? $event->city->province : 'NOT FOUND'));
            $this->line("---");
        }
        
        $this->info('✅ Test completed!');
        
        return Command::SUCCESS;
    }
}
