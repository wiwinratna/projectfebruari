<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\JobCategory;

class WorkerOpeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marathon = Event::where('title', 'Jakarta International Marathon 2025')->first();
        $esports = Event::where('title', 'Esports National Championship')->first();
        
        // Match names from JobCategorySeeder
        $medical = JobCategory::where('name', 'Medis')->first();
        $media = JobCategory::where('name', 'Media & Komunikasi')->first();
        $consumption = JobCategory::where('name', 'Konsumsi')->first(); // Using as general volunteer/support
        $security = JobCategory::where('name', 'Keamanan')->first();

        // Fallback if null (though Seeder order should prevent this)
        $medId = $medical ? $medical->id : 1;
        $mediaId = $media ? $media->id : 1;
        $consId = $consumption ? $consumption->id : 1;
        $secId = $security ? $security->id : 1;

        $openings = [];

        if ($marathon) {
            $openings[] = [
                'event_id' => $marathon->id,
                'job_category_id' => $medId,
                'title' => 'Medical Team Director',
                'description' => 'Lead the medical response team for the marathon. Coordinate ambulances, first aid stations, and hospital liaisons.',
                'requirements' => json_encode(['Medical License', '5+ Years Emergency Experience', 'Leadership Skills']),
                'slots_total' => 1,
                'slots_filled' => 0, // Will be updated by ApplicationSeeder
                'application_deadline' => Carbon::now()->addWeeks(4),
                'benefits' => 'Competitive Salary, Insurance, Certificate',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $openings[] = [
                'event_id' => $marathon->id,
                'job_category_id' => $consId,
                'title' => 'Water Station Volunteer',
                'description' => 'Manage water stations along the marathon route. Ensure runners stay hydrated.',
                'requirements' => json_encode(['Enthusiastic', 'Physically Fit', 'Team Player']),
                'slots_total' => 50,
                'slots_filled' => 0, // Will be updated by ApplicationSeeder
                'application_deadline' => Carbon::now()->addWeeks(2),
                'benefits' => 'T-Shirt, Meals, Certificate',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($esports) {
            $openings[] = [
                'event_id' => $esports->id,
                'job_category_id' => $mediaId,
                'title' => 'Social Media Specialist',
                'description' => 'Manage live social media updates during the tournament. Engage with the online community.',
                'requirements' => json_encode(['Social Media Savvy', 'Copywriting Skills', 'Esports Knowledge']),
                'slots_total' => 2,
                'slots_filled' => 0, // Will be updated by ApplicationSeeder
                'application_deadline' => Carbon::now()->addDays(5),
                'benefits' => 'Access to VIP Area, Allowance',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ];

             $openings[] = [
                'event_id' => $esports->id,
                'job_category_id' => $secId,
                'title' => 'Venue Security',
                'description' => 'Ensure safety of players and equipment at the venue.',
                'requirements' => json_encode(['Security License', 'Physical Fitness']),
                'slots_total' => 10,
                'slots_filled' => 0, // Will be updated by ApplicationSeeder
                'application_deadline' => Carbon::now()->addDays(3),
                'benefits' => 'Daily Allowance, Meals',
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($openings as $job) {
            DB::table('worker_openings')->insert($job);
        }
    }
}
