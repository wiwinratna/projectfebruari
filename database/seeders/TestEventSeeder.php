<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\EventStatusService;

class TestEventSeeder extends Seeder
{
    /**
     * Run the database seeds to create test events with different statuses.
     * 
     * Status Logic:
     * - 'planning': Event internal, tidak muncul di customer side
     * - 'upcoming': Event yang akan datang, muncul di customer side tapi belum active
     * - 'active': Event sedang berlangsung, muncul di customer side
     * - 'completed': Event sudah selesai, tidak muncul lagi di customer side
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Calculate relative dates
        $yesterday = $now->copy()->subDay();
        $today = $now->copy();
        $tomorrow = $now->copy()->addDay();
        $nextWeek = $now->copy()->addWeek();
        $twoWeeksAhead = $now->copy()->addWeeks(2);
        $nextMonth = $now->copy()->addMonth();
        $twoMonthsAgo = $now->copy()->subMonths(2);
        $lastWeek = $now->copy()->subWeek();

        $testEvents = [
            // COMPLETED EVENTS (tidak muncul di customer side)
            [
                'title' => 'Completed Event - Past Championship',
                'description' => 'Event yang sudah selesai bulan lalu - tidak muncul di customer side',
                'start_at' => $lastWeek->copy()->subDays(3)->setHour(9),
                'end_at' => $lastWeek->copy()->subDays(1)->setHour(18),
                'venue' => 'Jakarta Sports Complex',
                'city_id' => 32, // Jakarta Pusat
                'status' => 'completed',
                'stage' => 'national',
                'penyelenggara' => 'KONI Pusat',
                'instagram' => '@completed_event',
                'email' => 'completed@example.com',
            ],
            [
                'title' => 'Completed Event - Last Month Tournament',
                'description' => 'Event yang sudah selesai dua bulan lalu',
                'start_at' => $twoMonthsAgo->copy()->setHour(8),
                'end_at' => $twoMonthsAgo->copy()->addDays(2)->setHour(20),
                'venue' => 'National Stadium',
                'city_id' => 4, // Medan
                'status' => 'completed',
                'stage' => 'province',
                'penyelenggara' => 'KONI Daerah',
                'instagram' => '@past_tournament',
                'email' => 'past@example.com',
            ],

            // ACTIVE EVENTS (sedang berlangsung - muncul di customer side)
            [
                'title' => 'Active Event - Currently Running Championship',
                'description' => 'Event yang sedang berlangsung sekarang - muncul di customer side',
                'start_at' => $yesterday->copy()->setHour(9),
                'end_at' => $tomorrow->copy()->setHour(18),
                'venue' => 'Gelora Bung Karno',
                'city_id' => 32, // Jakarta Pusat
                'status' => 'active',
                'stage' => 'national',
                'penyelenggara' => 'PB PASI',
                'instagram' => '@active_event',
                'email' => 'active@example.com',
            ],
            [
                'title' => 'Active Event - Multi-day Festival',
                'description' => 'Event multi-hari yang sedang berlangsung',
                'start_at' => $today->copy()->subHours(2), // Started 2 hours ago
                'end_at' => $nextWeek->copy()->addDays(1)->setHour(20), // Ends next week
                'venue' => 'Jakarta Convention Center',
                'city_id' => 32, // Jakarta Pusat
                'status' => 'active',
                'stage' => 'asia',
                'penyelenggara' => 'KONI Internasional',
                'instagram' => '@festival_active',
                'email' => 'festival@example.com',
            ],

            // UPCOMING EVENTS (akan datang - muncul di customer side)
            [
                'title' => 'Upcoming Event - Next Week Championship',
                'description' => 'Event yang akan datang minggu depan - muncul di customer side tapi belum active',
                'start_at' => $nextWeek->copy()->addDays(2)->setHour(9),
                'end_at' => $nextWeek->copy()->addDays(4)->setHour(18),
                'venue' => 'Surabaya Sports Arena',
                'city_id' => 68, // Surabaya (Jawa Timur)
                'status' => 'upcoming',
                'stage' => 'province',
                'penyelenggara' => 'KONI Jawa Timur',
                'instagram' => '@upcoming_champ',
                'email' => 'upcoming@example.com',
            ],
            [
                'title' => 'Upcoming Event - Next Month Tournament',
                'description' => 'Event yang akan datang bulan depan',
                'start_at' => $nextMonth->copy()->setHour(10),
                'end_at' => $nextMonth->copy()->addDays(3)->setHour(17),
                'venue' => 'Bandung Sports Center',
                'city_id' => 58, // Bandung (Jawa Barat)
                'status' => 'upcoming',
                'stage' => 'national',
                'penyelenggara' => 'KONI Daerah Jawa Barat',
                'instagram' => '@upcoming_tournament',
                'email' => 'upcoming_tour@example.com',
            ],

            // PLANNING EVENTS (tidak muncul di customer side)
            [
                'title' => 'Planning Event - Future International',
                'description' => 'Event masih dalam tahap planning - TIDAK muncul di customer side',
                'start_at' => $twoWeeksAhead->copy()->addDays(1)->setHour(8),
                'end_at' => $twoWeeksAhead->copy()->addDays(5)->setHour(20),
                'venue' => 'Bali International Convention Center',
                'city_id' => 73, // Denpasar (Bali)
                'status' => 'planning',
                'stage' => 'asia',
                'penyelenggara' => 'KONI Internasional',
                'instagram' => '@planning_intl',
                'email' => 'planning_intl@example.com',
            ],
            [
                'title' => 'Planning Event - Future Regional',
                'description' => 'Event dalam tahap planning untuk level regional',
                'start_at' => $nextMonth->copy()->addDays(10)->setHour(9),
                'end_at' => $nextMonth->copy()->addDays(12)->setHour(18),
                'venue' => 'Medan Sports Complex',
                'city_id' => 4, // Medan
                'status' => 'planning',
                'stage' => 'asean/sea',
                'penyelenggara' => 'KONI Sumatera Utara',
                'instagram' => '@planning_regional',
                'email' => 'planning_regional@example.com',
            ],
        ];

        $insertedEvents = [];
        foreach ($testEvents as $event) {
            $insertedId = DB::table('events')->insertGetId(array_merge($event, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
            $insertedEvents[] = DB::table('events')->find($insertedId);
        }

        // Calculate and display status statistics
        $statusCounts = [];
        foreach ($insertedEvents as $eventData) {
            // Calculate status based on dates manually for seeder
            $now = Carbon::now();
            $startAt = Carbon::parse($eventData->start_at);
            $endAt = $eventData->end_at ? Carbon::parse($eventData->end_at) : $startAt->copy()->addDay();
            
            if ($now->greaterThan($endAt)) {
                $calculatedStatus = 'completed';
            } elseif ($now->greaterThanOrEqualTo($startAt) && $now->lessThanOrEqualTo($endAt)) {
                $calculatedStatus = 'active';
            } else {
                // Use the original status for future events
                $calculatedStatus = $eventData->status;
            }
            
            $statusCounts[$calculatedStatus] = ($statusCounts[$calculatedStatus] ?? 0) + 1;
        }

        // Add some event-sport relationships for testing
        $eventSports = [
            1 => [  // Completed Event - Past Championship
                'ATH' => ['quota' => 20],
                'SWI' => ['quota' => 15],
            ],
            3 => [  // Active Event - Currently Running Championship
                'BDM' => ['quota' => 32],
                'GYM' => ['quota' => 18],
            ],
            5 => [  // Upcoming Event - Next Week Championship
                'BSK' => ['quota' => 24],
                'VOL' => ['quota' => 16],
            ],
        ];

        foreach ($eventSports as $eventIndex => $sports) {
            if (!isset($insertedEvents[$eventIndex - 1])) {
                continue;
            }
            
            $eventId = $insertedEvents[$eventIndex - 1]->id;

            foreach ($sports as $sportCode => $meta) {
                $sportId = DB::table('sports')->where('code', $sportCode)->value('id');

                if (!$sportId) {
                    continue;
                }

                DB::table('event_sport')->insert([
                    'event_id' => $eventId,
                    'sport_id' => $sportId,
                    'quota' => $meta['quota'] ?? null,
                    'notes' => $meta['notes'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Display summary
        echo "Created " . count($testEvents) . " test events with comprehensive status coverage:\n";
        foreach ($statusCounts as $status => $count) {
            $visibility = in_array($status, ['active', 'upcoming']) ? '(visible to customers)' : '(admin only)';
            echo "- $status: $count events $visibility\n";
        }
        
        // Use EventStatusService after seeding is complete
        $customerVisibleCount = \App\Models\Event::whereIn('status', ['active', 'upcoming'])->count();
        $adminTotalCount = \App\Models\Event::count();
        
        echo "\nCustomer Visible Events: $customerVisibleCount\n";
        echo "Admin Total Events: $adminTotalCount\n";
    }
}