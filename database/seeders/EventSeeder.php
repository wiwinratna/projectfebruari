<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Sport;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure cities exist first (handled by CitySeeder usually, but we fetch here)
        $jakarta = DB::table('cities')->where('name', 'Jakarta Selatan')->first();
        
        // Fallback if specific cities not found
        $jakartaId = $jakarta ? $jakarta->id : 1;

        $events = [
            [
                'title' => 'Jakarta International Marathon 2025',
                'description' => 'A world-class marathon event inviting runners from across the globe to compete in the heart of Jakarta.',
                'start_at' => Carbon::now()->addMonths(2)->setTime(5, 0),
                'end_at' => Carbon::now()->addMonths(2)->setTime(14, 0),
                'venue' => 'Monas & Gelora Bung Karno',
                'city_id' => $jakartaId,
                'status' => 'upcoming',
                'stage' => 'international',
                'penyelenggara' => 'PASI (Persatuan Atletik Seluruh Indonesia)',
                'instagram' => '@jakartamarathon',
                'email' => 'info@jakartamarathon.com',
                'sports' => ['ATH'], // Associate Athletics
            ],
            [
                'title' => 'Asian Youth Games Prep',
                'description' => 'Preparation and selection event for the upcoming Asian Youth Games.',
                'start_at' => Carbon::now()->addWeeks(3)->setTime(9, 0),
                'end_at' => Carbon::now()->addWeeks(4)->setTime(17, 0),
                'venue' => 'Jakarta International Velodrome',
                'city_id' => $jakartaId,
                'status' => 'active',
                'stage' => 'national',
                'penyelenggara' => 'NOC Indonesia',
                'instagram' => '@noc.indonesia',
                'email' => 'youthgames@noc.id',
                'sports' => ['CYC', 'BDM', 'SWM'], // Associate Cycling, Badminton, Swimming
            ],
            [
                'title' => 'Esports National Championship',
                'description' => 'The biggest national esports tournament featuring Mobile Legends, PUBG, and Valorant.',
                'start_at' => Carbon::now()->subDays(5)->setTime(10, 0),
                'end_at' => Carbon::now()->addDays(2)->setTime(22, 0),
                'venue' => 'Ice BSD',
                'city_id' => $jakartaId, 
                'status' => 'active',
                'stage' => 'national',
                'penyelenggara' => 'PB ESI',
                'instagram' => '@pbesi_official',
                'email' => 'championship@pbesi.org',
                'sports' => ['ESP'], // Associate Esports
            ],
        ];

        foreach ($events as $data) {
            $sportsCodes = $data['sports'] ?? [];
            unset($data['sports']); // Remove from data array to prevent column error

            // Use updateOrCreate to prevent duplicates
            $event = Event::updateOrCreate(
                ['title' => $data['title']],
                $data
            );

            // Attach Sports
            if (!empty($sportsCodes)) {
                $sportIds = Sport::whereIn('code', $sportsCodes)->pluck('id');
                // Sync with pivot data if needed, but for now just plain sync
                $event->sports()->sync($sportIds);
            }
        }
    }
}
