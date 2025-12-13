<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WorkerOpening;
use App\Models\Application;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'customer')->get();
        $jobs = WorkerOpening::all();
        $admin = User::where('role', 'admin')->first();

        if ($users->isEmpty() || $jobs->isEmpty()) {
            return;
        }

        // Helper to get CV path (just cycling through placeholders)
        $getCv = function($index) {
            $cvs = ['cvs/budi_cv.pdf', 'cvs/siti_cv.pdf', 'cvs/reza_cv.pdf'];
            return $cvs[$index % count($cvs)];
        };

        foreach ($users as $index => $user) {
            // Each user applies to 1-3 random jobs
            $randomJobs = $jobs->random(rand(1, 3));

            foreach ($randomJobs as $job) {
                // Determine status randomly
                $rand = rand(1, 10);
                if ($rand <= 2) { 
                    $status = 'rejected';
                    $notes = 'Not enough experience.';
                } elseif ($rand <= 6) {
                    $status = 'pending';
                    $notes = null;
                } else {
                    $status = 'approved'; // 40% chance of approval to fill slots
                    $notes = 'Great candidate!';
                }

                // If approved/rejected, it must be reviewed
                $reviewedBy = ($status !== 'pending' && $admin) ? $admin->id : null;
                $reviewedAt = ($status !== 'pending') ? now() : null;

                Application::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'worker_opening_id' => $job->id,
                    ],
                    [
                        'motivation' => 'I am very interested in this position because I have relevant skills and passion for the event.',
                        'experience' => '3 years of experience in similar roles.',
                        'cv_path' => $getCv($index),
                        'status' => $status,
                        'reviewed_by' => $reviewedBy,
                        'review_notes' => $notes,
                        'reviewed_at' => $reviewedAt,
                    ]
                );
            }
        }

        // Recalculate slots_filled for all openings
        foreach ($jobs as $job) {
            $approvedCount = Application::where('worker_opening_id', $job->id)
                                      ->where('status', 'approved')
                                      ->count();
            
            $job->update(['slots_filled' => $approvedCount]);
        }
    }
}
