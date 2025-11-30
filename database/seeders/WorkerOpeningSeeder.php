<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WorkerOpeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $openings = [
            [
                'event_slug' => 'asian-games-2024-preparation',
                'job_category' => 'Konsumsi',
                'title' => 'Food & Beverage Coordinator',
                'description' => 'Menangani distribusi konsumsi atlet dan pelatih selama training camp.',
                'requirements' => [
                    'Pengalaman minimal 1 tahun di event hospitality.',
                    'Mampu bekerja dalam shift panjang.',
                    'Komunikatif dan detail-oriented.',
                ],
                'slots_total' => 6,
                'slots_filled' => 2,
                'shift_start' => Carbon::create(2024, 3, 14, 6),
                'shift_end' => Carbon::create(2024, 4, 30, 22),
                'benefits' => 'Akomodasi & konsumsi disediakan, sertifikat resmi KOI.',
                'status' => 'open',
            ],
            [
                'event_slug' => 'asian-games-2024-preparation',
                'job_category' => 'Keamanan',
                'title' => 'Venue Security Supervisor',
                'description' => 'Mengawasi standar keamanan venue dan akses atlet.',
                'requirements' => [
                    'Memiliki sertifikasi satpam aktif.',
                    'Terbiasa dengan protokol tamu VIP.',
                    'Siap bekerja sistem regu 12 jam.',
                ],
                'slots_total' => 8,
                'slots_filled' => 5,
                'shift_start' => Carbon::create(2024, 3, 15, 7),
                'shift_end' => Carbon::create(2024, 4, 30, 23),
                'benefits' => 'Honor harian + uang lembur, seragam lengkap.',
                'status' => 'open',
            ],
            [
                'event_slug' => 'swimming-national-championship-2024',
                'job_category' => 'Media & Komunikasi',
                'title' => 'Media Relations Assistant',
                'description' => 'Membantu pengelolaan konferensi pers dan dokumentasi digital.',
                'requirements' => [
                    'Mahasiswa komunikasi tingkat akhir lebih disukai.',
                    'Menguasai copywriting Bahasa Inggris.',
                    'Mampu mengoperasikan kamera dasar.',
                ],
                'slots_total' => 4,
                'slots_filled' => 1,
                'shift_start' => Carbon::create(2024, 5, 31, 9),
                'shift_end' => Carbon::create(2024, 6, 7, 21),
                'benefits' => 'Akses all-area, allowance transport & konsumsi.',
                'status' => 'open',
            ],
            [
                'event_slug' => 'youth-olympic-qualifiers-2025',
                'job_category' => 'Medis',
                'title' => 'Field Medical Officer',
                'description' => 'Stand by di venue untuk pertolongan pertama atlet muda.',
                'requirements' => [
                    'Lulusan keperawatan / fisioterapi.',
                    'Memiliki sertifikat BLS/ACLS.',
                    'Berpengalaman menangani cedera olahraga.',
                ],
                'slots_total' => 5,
                'slots_filled' => 0,
                'shift_start' => Carbon::create(2025, 1, 9, 7),
                'shift_end' => Carbon::create(2025, 1, 20, 21),
                'benefits' => 'Honor kompetitif, kit medis lengkap, asuransi event.',
                'status' => 'planned',
            ],
        ];

        foreach ($openings as $opening) {
            $eventId = DB::table('events')->where('slug', $opening['event_slug'])->value('id');
            $jobCategoryId = DB::table('job_categories')->where('name', $opening['job_category'])->value('id');

            if (!$eventId || !$jobCategoryId) {
                continue;
            }

            DB::table('worker_openings')->updateOrInsert(
                [
                    'event_id' => $eventId,
                    'title' => $opening['title'],
                ],
                [
                    'job_category_id' => $jobCategoryId,
                    'description' => $opening['description'],
                    'requirements' => json_encode($opening['requirements']),
                    'slots_total' => $opening['slots_total'],
                    'slots_filled' => $opening['slots_filled'],
                    'shift_start' => $opening['shift_start'],
                    'shift_end' => $opening['shift_end'],
                    'benefits' => $opening['benefits'],
                    'status' => $opening['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
