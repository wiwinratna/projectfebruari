<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\WorkerType;
use App\Models\JobCategory;
use App\Models\User;
use App\Models\Event;
use App\Models\WorkerOpening;
use App\Models\Application;

class ComprehensiveWorkerSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Starting comprehensive worker system seeder...\n";

        // 1. Create Worker Types
        echo "Creating worker types...\n";
        $this->createWorkerTypes();

        // 2. Create Job Categories
        echo "Creating job categories...\n";
        $this->createJobCategories();

        // 3. Create Users
        echo "Creating users...\n";
        $this->createUsers();

        // 4. Create Events
        echo "Creating events...\n";
        $this->createEvents();

        // 5. Create Worker Openings
        echo "Creating worker openings...\n";
        $this->createWorkerOpenings();

        // 6. Create Applications
        echo "Creating applications...\n";
        $this->createApplications();

        echo "Comprehensive worker system seeder completed!\n";
    }

    private function createWorkerTypes()
    {
        $workerTypes = [
            [
                'name' => 'LO',
                'description' => 'Local Organizer - Mengelola operasional dan koordinasi event di tingkat lokal/daerah',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VO',
                'description' => 'Volunteer Organizer - Mengelola dan mengoordinasi relawan untuk mendukung suksesnya event',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($workerTypes as $type) {
            WorkerType::firstOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }

    private function createJobCategories()
    {
        // Get worker type IDs
        $loType = DB::table('worker_types')->where('name', 'LO')->first();
        $voType = DB::table('worker_types')->where('name', 'VO')->first();

        $categories = [
            // LO Categories
            [
                'name' => 'Operations Manager',
                'description' => 'Mengelola operasi umum event, koordinasi logistik, dan memastikan semua aspek operasional berjalan lancar',
                'requires_certification' => true,
                'default_shift_hours' => 12,
                'is_active' => true,
                'worker_type_id' => $loType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Technical Coordinator',
                'description' => 'Mengelola aspek teknis termasuk sound system, lighting, dan teknologi event',
                'requires_certification' => true,
                'default_shift_hours' => 10,
                'is_active' => true,
                'worker_type_id' => $loType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Security Coordinator',
                'description' => 'Mengelola keamanan venue, pengunjung, dan atlet selama event berlangsung',
                'requires_certification' => true,
                'default_shift_hours' => 12,
                'is_active' => true,
                'worker_type_id' => $loType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Media Relations',
                'description' => 'Mengelola hubungan dengan media, press kit, dan coverage event',
                'requires_certification' => false,
                'default_shift_hours' => 8,
                'is_active' => true,
                'worker_type_id' => $loType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Logistics Coordinator',
                'description' => 'Mengelola transportasi, akomodasi, dan logistik peserta event',
                'requires_certification' => false,
                'default_shift_hours' => 10,
                'is_active' => true,
                'worker_type_id' => $loType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // VO Categories
            [
                'name' => 'Event Volunteer',
                'description' => 'Relawan umum untuk mendukung kelancaran event, registrasi peserta, dan assist pengunjung',
                'requires_certification' => false,
                'default_shift_hours' => 8,
                'is_active' => true,
                'worker_type_id' => $voType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Athlete Assistant',
                'description' => 'Membantu atlet dengan registrasi, teknis support, dan kebutuhan selama kompetisi',
                'requires_certification' => false,
                'default_shift_hours' => 8,
                'is_active' => true,
                'worker_type_id' => $voType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Crowd Control',
                'description' => 'Mengelola alur pengunjung, antrian, dan memberikan informasi kepada peserta',
                'requires_certification' => false,
                'default_shift_hours' => 8,
                'is_active' => true,
                'worker_type_id' => $voType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medical Support',
                'description' => 'Tim medis untuk memberikan first aid dan dukungan kesehatan selama event',
                'requires_certification' => true,
                'default_shift_hours' => 12,
                'is_active' => true,
                'worker_type_id' => $voType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Refreshment Team',
                'description' => 'Tim yang mengelola konsumsi, water station, dan food service untuk peserta',
                'requires_certification' => false,
                'default_shift_hours' => 6,
                'is_active' => true,
                'worker_type_id' => $voType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Documentation Team',
                'description' => 'Tim foto/videographer untuk mendokumentasikan event',
                'requires_certification' => false,
                'default_shift_hours' => 8,
                'is_active' => true,
                'worker_type_id' => $voType->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($categories as $category) {
            JobCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }

    private function createUsers()
    {
        $users = [
            [
                'name' => 'Ahmad Sutrisno',
                'email' => 'ahmad.sutrisno@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sari Indrawati',
                'email' => 'sari.indrawati@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Hartono',
                'email' => 'budi.hartono@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maya Putri',
                'email' => 'maya.putri@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rudi Santoso',
                'email' => 'rudi.santoso@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lina Marlina',
                'email' => 'lina.marlina@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rina Sartika',
                'email' => 'rina.sartika@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
    private function createEvents()
    {
        // Get some city IDs
        $jakarta = DB::table('cities')->where('name', 'Jakarta Selatan')->first();
        $bandung = DB::table('cities')->where('name', 'Bandung')->first();
        $surabaya = DB::table('cities')->where('name', 'Surabaya')->first();
        $medan = DB::table('cities')->where('name', 'Medan')->first();
        $makassar = DB::table('cities')->where('name', 'Makassar')->first();

        $events = [
            [
                'title' => 'Indonesia Basketball Championship 2025',
                'description' => 'Kejuaraan basket nasional untuk mempercepat perkembangan basket Indonesia dan mencari talenta muda berbakat untuk timnas',
                'start_at' => Carbon::now()->addDays(15)->setTime(8, 0),
                'end_at' => Carbon::now()->addDays(17)->setTime(20, 0),
                'venue' => 'Gelora Bung Karno Sports Complex',
                'city_id' => $jakarta->id,
                'status' => 'upcoming',
                'stage' => 'national',
                'penyelenggara' => 'Perbasi DKI Jakarta',
                'instagram' => '@indonesiabasketball2025',
                'email' => 'info@indonesiabasketball.org',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'West Java Marathon 2025',
                'description' => 'Maraton nasional untuk membuktikan semangat sportivitas dan kesehatan masyarakat Jawa Barat',
                'start_at' => Carbon::now()->addDays(25)->setTime(5, 0),
                'end_at' => Carbon::now()->addDays(25)->setTime(15, 0),
                'venue' => 'Alun-Alun Bandung',
                'city_id' => $bandung->id,
                'status' => 'planning',
                'stage' => 'province',
                'penyelenggara' => 'Federasi Atletik Indonesia Jawa Barat',
                'instagram' => '@westjavamarathon',
                'email' => 'marathon@atletikjabar.or.id',
                'created_at' => Carbon::now()->subDays(45),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'East Java Swimming Championship',
                'description' => 'Kejuaraan Renang Provinsi Jawa Timur untuk menyeleksi atlet terbaik menuju even nasional',
                'start_at' => Carbon::now()->subDays(2)->setTime(7, 0),
                'end_at' => Carbon::now()->subDays(2)->setTime(18, 0),
                'venue' => 'Mas Tirta Swimming Pool',
                'city_id' => $surabaya->id,
                'status' => 'active',
                'stage' => 'national',
                'penyelenggara' => 'Persatuan Renang Seluruh Indonesia Jawa Timur',
                'instagram' => '@swimmingjawatimur',
                'email' => 'renang@prsi-jateng.or.id',
                'created_at' => Carbon::now()->subDays(60),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'title' => 'North Sumatra Badminton Open 2025',
                'description' => 'Turnamen bulu tangkis terbuka untuk mengharumkan nama Sumatera Utara di kancah nasional',
                'start_at' => Carbon::now()->subDays(10)->setTime(8, 0),
                'end_at' => Carbon::now()->subDays(8)->setTime(19, 0),
                'venue' => 'Mikie Holiday Badminton Hall',
                'city_id' => $medan->id,
                'status' => 'completed',
                'stage' => 'province',
                'penyelenggara' => 'Persatuan Bulu Tangkis Seluruh Indonesia Sumatera Utara',
                'instagram' => '@badminton.sumut',
                'email' => 'badminton@pbsi-sumut.or.id',
                'created_at' => Carbon::now()->subDays(90),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'title' => 'South Sulawesi Volleyball League',
                'description' => 'Liga bola voli antar klub se-Sulawesi Selatan untuk mempromosikan olahraga bola voli di kawasan Timur Indonesia',
                'start_at' => Carbon::now()->addDays(8)->setTime(9, 0),
                'end_at' => Carbon::now()->addDays(12)->setTime(21, 0),
                'venue' => 'GOR Sabil Alamsyah',
                'city_id' => $makassar->id,
                'status' => 'upcoming',
                'stage' => 'province',
                'penyelenggara' => 'Persatuan Bola Voli Seluruh Indonesia Sulawesi Selatan',
                'instagram' => '@volleysulsel',
                'email' => 'volley@pvoli-sulsel.or.id',
                'created_at' => Carbon::now()->subDays(40),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'Jakarta International Fencing Championship',
                'description' => 'Kejuaraan tenis national Indonesia untuk atlet pemula hingga professional',
                'start_at' => Carbon::now()->addDays(35)->setTime(10, 0),
                'end_at' => Carbon::now()->addDays(38)->setTime(17, 0),
                'venue' => 'Gelora Bung Karno Sports Complex',
                'city_id' => $jakarta->id,
                'status' => 'planning',
                'stage' => 'world',
                'penyelenggara' => 'Federasi Tenis Indonesia',
                'instagram' => '@jktfencing2025',
                'email' => 'fencing@fti.or.id',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        ];

        foreach ($events as $event) {
            DB::table('events')->insert($event);
        }
    }

    private function createWorkerOpenings()
    {
        // Get event and job category IDs
        $events = DB::table('events')->get();
        $categories = DB::table('job_categories')->get();

        $openings = [
            // Indonesia Basketball Championship 2025 (upcoming event)
            [
                'event_id' => $events->where('title', 'Indonesia Basketball Championship 2025')->first()->id,
                'job_category_id' => $categories->where('name', 'Operations Manager')->first()->id,
                'title' => 'Operations Manager - Basketball Championship',
                'description' => 'Mengelola operasi umum event basket, termasuk registrasi peserta, manajemen venue, dan koordinasi tim during event',
                'requirements' => json_encode([
                    'Pengalaman minimal 3 tahun dalam manajemen event olahraga',
                    'Kemampuan leadership dan komunikasi yang baik',
                    'Mampu bekerja dalam tekanan dan deadline ketat',
                    'Memahami regulasi federasi basket internasional',
                    'Bersedia bekerja full-time selama event'
                ]),
                'slots_total' => 2,
                'slots_filled' => 1,
                'application_deadline' => Carbon::now()->addDays(7)->setTime(23, 59),
                'benefits' => 'Honorarium 2.5 juta, sertifikat, coaching session, dan networking peluang karier',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'event_id' => $events->where('title', 'Indonesia Basketball Championship 2025')->first()->id,
                'job_category_id' => $categories->where('name', 'Technical Coordinator')->first()->id,
                'title' => 'Technical Coordinator - Audio Visual',
                'description' => 'Mengelola aspek teknis sound system, lighting, scoreboard, dan live streaming untuk event basket',
                'requirements' => json_encode([
                    'Background teknik elektro atau broadcasting',
                    'Pengalaman minimal 2 tahun dalam event broadcasting',
                    'Memahami teknik audio dan video production',
                    'Dapat bekerja dalam tim teknis multinasional',
                    'Bersedia kerja lembur jika diperlukan'
                ]),
                'slots_total' => 3,
                'slots_filled' => 2,
                'application_deadline' => Carbon::now()->addDays(7)->setTime(23, 59),
                'benefits' => 'Honorarium 2 juta, sertifikat teknis, peluang magang di perusahaan broadcasting',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'event_id' => $events->where('title', 'Indonesia Basketball Championship 2025')->first()->id,
                'job_category_id' => $categories->where('name', 'Event Volunteer')->first()->id,
                'title' => 'Event Volunteer - Registration & Information',
                'description' => 'Membantu registrasi peserta, memberikan informasi kepada pengunjung, dan support operasional umum',
                'requirements' => json_encode([
                    'Mahasiswa atau fresh graduate',
                    'Komunikatif dan ramah',
                    'Bisa berbahasa Inggris dasar',
                    'Fleksibel dan responsif',
                    'Minat di bidang event management'
                ]),
                'slots_total' => 15,
                'slots_filled' => 8,
                'application_deadline' => Carbon::now()->addDays(10)->setTime(23, 59),
                'benefits' => 'Transpor, makan siang, sertifikat relawan, dan referensi karier',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(3),
            ],

            // West Java Marathon 2025 (planning event)
            [
                'event_id' => $events->where('title', 'West Java Marathon 2025')->first()->id,
                'job_category_id' => $categories->where('name', 'Logistics Coordinator')->first()->id,
                'title' => 'Logistics Coordinator - Marathon Transportation',
                'description' => 'Mengelola transportasi peserta marathon dari start point ke finish line dan distribusi Bib number',
                'requirements' => json_encode([
                    'Pengalaman event manajemen, preferensi maraton/road race',
                    'Kemampuan koordinasi multi-titik',
                    'Memahami traffic management dan route planning',
            'Memiliki SIM C dan kendaraan sendiri',
                    'Bersedia subuh sekali dalam seminggu'
                ]),
                'slots_total' => 4,
                'slots_filled' => 0,
                'application_deadline' => Carbon::now()->addDays(5)->setTime(23, 59),
                'benefits' => 'Honorarium 1.8 juta, sertifikat manajemen logistik, networking dengan industry',
                'status' => 'draft',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'event_id' => $events->where('title', 'West Java Marathon 2025')->first()->id,
                'job_category_id' => $categories->where('name', 'Medical Support')->first()->id,
                'title' => 'Medical Support Team - Marathon',
                'description' => 'Tim medis untuk memberikan first aid, emergency response, dan health monitoring selama maraton',
                'requirements' => json_encode([
                    'Profesional kesehatan (dokter, perawat, physiotherapist)',
                    'Sertifikat BTCLS atau equivalent',
                    'Pengalaman event sports medicine',
                    'Physically fit dan dapat berlari',
                    'Bersedia standby 12 jam'
                ]),
                'slots_total' => 8,
                'slots_filled' => 5,
                'application_deadline' => Carbon::now()->addDays(12)->setTime(23, 59),
                'benefits' => 'Honorarium 3 juta, sertifikat medical support, peluang melanjutkan specialization',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'event_id' => $events->where('title', 'West Java Marathon 2025')->first()->id,
                'job_category_id' => $categories->where('name', 'Event Volunteer')->first()->id,
                'title' => 'Marathon Volunteer - Route Support',
                'description' => 'Relawan untuk mendukung peserta di berbagai titik route maraton dengan water station dan cheer support',
                'requirements' => json_encode([
                    'Energetic dan suka membantu orang',
                    'Dapat bekerja di outdoor condition',
                    'Memahami basic marathon etiquette',
                    'Bersedia bangun subuh',
                    'Team player dengan attitude positive'
                ]),
                'slots_total' => 25,
                'slots_filled' => 12,
                'application_deadline' => Carbon::now()->addDays(15)->setTime(23, 59),
                'benefits' => 'Seragam volunteer, sarapan, sertifikat community service, dan snack',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(2),
            ],

            // East Java Swimming Championship (active event)
            [
                'event_id' => $events->where('title', 'East Java Swimming Championship')->first()->id,
                'job_category_id' => $categories->where('name', 'Athlete Assistant')->first()->id,
                'title' => 'Athlete Assistant - Swimming Competition',
                'description' => 'Membantu atlet dengan warm-up, equipment check, lane assignment, dan race timing coordination',
                'requirements' => json_encode([
                    'Background swimming atau aquatic sports',
                    'Memahami rule dan regulasi FINA',
                    'Attentive to detail dan dapat bekerja dengan pressure',
                    'Bisa bekerja dengan air dan wet condition',
                    'Excellent swimming skills required'
                ]),
                'slots_total' => 6,
                'slots_filled' => 4,
                'application_deadline' => Carbon::now()->subDays(5)->setTime(23, 59),
                'benefits' => 'Honorarium 1.5 juta, swimsuit gratis, akses coaching clinic',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'event_id' => $events->where('title', 'East Java Swimming Championship')->first()->id,
                'job_category_id' => $categories->where('name', 'Documentation Team')->first()->id,
                'title' => 'Swimming Event Photographer',
                'description' => 'Tim fotografer untuk menangkap moment terbaik atlet swimming dan ceremony award',
                'requirements' => json_encode([
                    'Professional photographer dengan portofolio sports',
                    'Memahami underwater photography (bonus)',
                    'Own camera equipment dan editing software',
                    'Dapat bekerja dengan fast-paced environment',
                    'Memahami compose rules untuk athletic photography'
                ]),
                'slots_total' => 4,
                'slots_filled' => 3,
                'application_deadline' => Carbon::now()->subDays(8)->setTime(23, 59),
                'benefits' => 'Honorarium 2 juta, photo credit dalam publikasi, networking dengan media',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(1),
            ],

            // South Sulawesi Volleyball League (upcoming event)
            [
                'event_id' => $events->where('title', 'South Sulawesi Volleyball League')->first()->id,
                'job_category_id' => $categories->where('name', 'Crowd Control')->first()->id,
                'title' => 'Crowd Management Volunteer',
                'description' => 'Mengelola penonton, antrian tiket, dan memastikan keamanan selama pertandingan voli',
                'requirements' => json_encode([
                    'Tinggi badan minimal 160cm (wanita) dan 170cm (pria)',
                    'Komunikasi yang baik dan tegas',
                    'Pengalaman crowd management atau security',
                    'Physical fitness dan stamina baik',
                    'Dapat bekerja dengan stress level tinggi'
                ]),
                'slots_total' => 10,
                'slots_filled' => 7,
                'application_deadline' => Carbon::now()->addDays(3)->setTime(23, 59),
                'benefits' => 'Honorarium 1.2 juta, training crowd management, sertifikat security',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'event_id' => $events->where('title', 'South Sulawesi Volleyball League')->first()->id,
                'job_category_id' => $categories->where('name', 'Refreshment Team')->first()->id,
                'title' => 'Food & Beverage Coordinator',
                'description' => 'Tim untuk mengelola food court, water station, dan refreshment untuk penonton dan atlet',
                'requirements' => json_encode([
                    'Experience dalam food service atau hospitality',
                    'Memahami food safety dan hygiene standards',
                    'Can work in fast-paced kitchen environment',
                    'Team player dengan good attitude',
                    'Available untuk flexible timing'
                ]),
                'slots_total' => 12,
                'slots_filled' => 9,
                'application_deadline' => Carbon::now()->addDays(4)->setTime(23, 59),
                'benefits' => 'Honorarium 1 juta, free meal, sertifikat food safety, referensi hospitality',
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(3),
            ],

            // Jakarta International Fencing Championship (planning event)
            [
                'event_id' => $events->where('title', 'Jakarta International Fencing Championship')->first()->id,
                'job_category_id' => $categories->where('name', 'Media Relations')->first()->id,
                'title' => 'Fencing Championship Media Officer',
                'description' => 'Mengelola press coverage, interview scheduling, dan media relations untuk event fencing internasional',
                'requirements' => json_encode([
                    'Background journalism, PR, atau communications',
                    'Excellent written dan verbal communication skills',
                    'Experience dalam event media management',
                    'Bisa berbahasa Inggris fluently',
                    'Network dengan media lokal/internasional (preferred)'
                ]),
                'slots_total' => 3,
                'slots_filled' => 0,
                'application_deadline' => Carbon::now()->addDays(25)->setTime(23, 59),
                'benefits' => 'Honorarium 2.8 juta, networking international media, referensi PR career',
                'status' => 'draft',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'event_id' => $events->where('title', 'Jakarta International Fencing Championship')->first()->id,
                'job_category_id' => $categories->where('name', 'Event Volunteer')->first()->id,
                'title' => 'Fencing Technical Volunteer',
                'description' => 'Volunteer untuk bantuan teknis fencing, score keeping, dan athlete guidance',
                'requirements' => json_encode([
                    'Memahami basic fencing rules dan equipment',
                    'Detail-oriented dan dapat Focus panjang',
                    'Physical ability untuk stand dan walk extended period',
                    'Team player dan dapat follow instruction precisely',
                    'Bersedia belajar fencing technique basics'
                ]),
                'slots_total' => 8,
                'slots_filled' => 2,
                'application_deadline' => Carbon::now()->addDays(20)->setTime(23, 59),
                'benefits' => 'Honorarium 800 ribu, fencing training gratis, sertifikat international volunteer',
                'status' => 'draft',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        ];

        foreach ($openings as $opening) {
            DB::table('worker_openings')->insert($opening);
        }
    }

    private function createApplications()
    {
        // Get user IDs and worker opening IDs
        $users = DB::table('users')->get();
        $openings = DB::table('worker_openings')->get();

        $applications = [
            // Applications for Indonesia Basketball Championship 2025 - Operations Manager
            [
                'worker_opening_id' => $openings->where('title', 'Operations Manager - Basketball Championship')->first()->id,
                'user_id' => $users->where('name', 'Ahmad Sutrisno')->first()->id,
                'motivation' => 'Saya memiliki passion besar dalam event manajemen olahraga. Dengan pengalaman 4 tahun di bidang ini, saya yakin dapat berkontribusi dalam kesuksesan Indonesia Basketball Championship 2025. Olahraga basket adalah olahraga yang saya cinta sejak SMA dan terlibat aktif di klub kampus.',
                'experience' => 'Project Manager di Jakarta Marathon 2023-2024 (2 tahun), Assistant Event Coordinator di Indonesia Badminton Open 2022 (1 tahun), sebagai volunteer di Asian Games 2018. Pernah mengelola event dengan peserta hingga 5000 orang dan budget 500 juta rupiah.',
                'cv_path' => 'cvs/ahmad_sutrisno_basketball_operations.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Budi Hartono')->first()->id,
                'review_notes' => 'Excellent background dalam event management. Pengalaman basketball interest adalah value added. Leadership skill terbukti dari project yang dikelola. Recommended untuk senior position.',
                'reviewed_at' => Carbon::now()->subDays(10)->setTime(14, 30),
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Operations Manager - Basketball Championship')->first()->id,
                'user_id' => $users->where('name', 'Sari Indrawati')->first()->id,
                'motivation' => 'Saya sangat antusias untuk berkontribusi dalam event olahraga prestisius ini. Basketball telah menjadi bagian integral dalam kehidupan saya, dan kesempatan untuk membantu发酵риян event basket internasional adalah dream come true.',
                'experience' => 'Event Coordinator di Nike Basketball Clinic 2023-2024, Operations Staff di Indonesian Basketball League 2022-2023, Marketing Coordinator di Sports Arena Magazine. Familiar dengan basketball terminology dan player management.',
                'cv_path' => 'cvs/sari_indrawati_basketball_ops.pdf',
                'status' => 'under_review',
                'reviewed_by' => null,
                'review_notes' => null,
                'reviewed_at' => null,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],

            // Applications for Technical Coordinator - Audio Visual
            [
                'worker_opening_id' => $openings->where('title', 'Technical Coordinator - Audio Visual')->first()->id,
                'user_id' => $users->where('name', 'Budi Hartono')->first()->id,
                'motivation' => 'Sebagai someone dengan background teknik elektro, saya tertarik untuk berkontribusi dalam event olahraga bergengsi. Kesempatan untuk mengintegrasikan teknologi dengan passion olahraga sangat sesuai dengan career goal saya.',
                'experience' => 'Technical Director di RCTI Sports Department (3 tahun), AV Technician untuk Indonesian Super League 2021-2023, freelance broadcast technician untuk berbagai event national. Pengalaman dengan live streaming, scoreboard management, dan sound engineering.',
                'cv_path' => 'cvs/budi_hartono_broadcast_tech.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Ahmad Sutrisno')->first()->id,
                'review_notes' => 'Strong technical background dan portfolio yang impressive. Pengalaman broadcast dengan skala besar sangat relevant. Leadership skill good. Highly recommended.',
                'reviewed_at' => Carbon::now()->subDays(9)->setTime(16, 45),
                'created_at' => Carbon::now()->subDays(11),
                'updated_at' => Carbon::now()->subDays(9),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Technical Coordinator - Audio Visual')->first()->id,
                'user_id' => $users->where('name', 'Maya Putri')->first()->id,
                'motivation' => 'Saya memiliki antusiasme tinggi untuk berkontribusi dalam event basket ini. Background saya dalam multimedia dan broadcast memberikan value tambahan untuk quality production event.',
                'experience' => 'Video Producer di tvOne Sports (2 tahun), Freelance Content Creator untuk berbagai brand olahraga. Experience dengan camera operation, video editing, dan live streaming setup. Memahami workflow production basket event.',
                'cv_path' => 'cvs/maya_putri_video_production.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Budi Hartono')->first()->id,
                'review_notes' => 'Good creative eye dan technical understanding. Experience dengan sports production adalah plus. attitude positive dan quick learner.',
                'reviewed_at' => Carbon::now()->subDays(8)->setTime(11, 20),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Technical Coordinator - Audio Visual')->first()->id,
                'user_id' => $users->where('name', 'Rudi Santoso')->first()->id,
                'motivation' => 'Saya ingin berpartisipasi dalam event basket prestisius ini karena percaya pada power olahraga untuk unite people. Background teknik saya dapat membantu memastikan technical aspect event berjalan lancar.',
                'experience' => 'IT Support Specialist di Grab Indonesia (2 tahun), Network Administrator di local university, hobbyist photographer dan videographer. Familiar dengan networking, live streaming setup, dan basic electrical troubleshooting.',
                'cv_path' => 'cvs/rudi_santoso_technical_support.pdf',
                'status' => 'rejected',
                'reviewed_by' => $users->where('name', 'Budi Hartono')->first()->id,
                'review_notes' => 'Technical background cukup tapi kurang experience dalam broadcast/live event. CV menunjukkan lebih ke IT support than broadcast technology. Better fit for IT support role, not technical coordinator.',
                'reviewed_at' => Carbon::now()->subDays(7)->setTime(15, 30),
                'created_at' => Carbon::now()->subDays(9),
                'updated_at' => Carbon::now()->subDays(7),
            ],

            // Applications for Medical Support Team - Marathon
            [
                'worker_opening_id' => $openings->where('title', 'Medical Support Team - Marathon')->first()->id,
                'user_id' => $users->where('name', 'Dewi Lestari')->first()->id,
                'motivation' => 'Sebagai dokter olahraga, saya berkomitmen penuh untuk mendukung event maraton ini. Pengalaman saya dalam sports medicine akan memastikan safety dan health coverage yang optimal untuk semua peserta.',
                'experience' => 'Sports Medicine Physician di RS Premiere Bintaro (4 tahun), Medical Officer untuk Jakarta Marathon 2022-2024, Team Doctor untuk Indonesian National Swimming Team (2 tahun). Certified BTCLS dan sports medicine specialist.',
                'cv_path' => 'cvs/dewi_lestari_doctor_marathon.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Maya Putri')->first()->id,
                'review_notes' => 'Excellent medical credentials dan specialized experience. Track record dengan maraton events proven. Leadership quality strong untuk lead medical team.',
                'reviewed_at' => Carbon::now()->subDays(6)->setTime(13, 15),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Medical Support Team - Marathon')->first()->id,
                'user_id' => $users->where('name', 'Andi Wijaya')->first()->id,
                'motivation' => 'Sebagai physiotherapist dengan passion di marathon, saya excited untuk membantu participants achieve their goals while ensuring their safety. Marathon adalah event yang memerlukan medical support professional.',
                'experience' => 'Physiotherapist di Sports Medicine Clinic Jakarta (3 tahun), Medical Volunteer untuk Bali Marathon 2023-2024, consultant untuk amateur marathon runners. Specialization dalam running injuries prevention dan rehabilitation.',
                'cv_path' => 'cvs/andi_wijaya_physiotherapist.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Dewi Lestari')->first()->id,
                'review_notes' => 'Strong background dalam sports rehabilitation. Experience dengan marathon medical support proven. Practical skills excellent untuk emergency response.',
                'reviewed_at' => Carbon::now()->subDays(5)->setTime(10, 45),
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Medical Support Team - Marathon')->first()->id,
                'user_id' => $users->where('name', 'Lina Marlina')->first()->id,
                'motivation' => 'Sebagai perawat dengan pengalaman emergency, saya ingin berkontribusi dalam maraton ini. Pengalaman saya dalam critical care akan valuable untuk medical response team.',
                'experience' => 'Emergency Nurse di RS Cipto Mangunkusumo (5 tahun), Medical Volunteer untuk Jakarta Half Marathon 2022-2023, Certified in Advanced Cardiac Life Support. Experienced dalam high-pressure medical situations.',
                'cv_path' => 'cvs/lina_marlina_emergency_nurse.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Dewi Lestari')->first()->id,
                'review_notes' => 'Solid emergency nursing experience. Calm under pressure dan excellent clinical skills. Good addition to medical team.',
                'reviewed_at' => Carbon::now()->subDays(5)->setTime(14, 20),
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Medical Support Team - Marathon')->first()->id,
                'user_id' => $users->where('name', 'Eko Prasetyo')->first()->id,
                'motivation' => 'Saya adalah runner enthusiast yang juga professional healthcare. Passion saya untuk marathon dan medical background membuat saya perfect fit untuk medical support team. Ingin memberikan back to community.',
                'experience' => 'Paramedic di Dinas Kesehatan DKI Jakarta (3 years), Marathon runner personal best 3:45, volunteer medical support untuk local running events. Experienced dalam field medical response.',
                'cv_path' => 'cvs/eko_prasetyo_paramedic.pdf',
                'status' => 'under_review',
                'reviewed_by' => null,
                'review_notes' => null,
                'reviewed_at' => null,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Medical Support Team - Marathon')->first()->id,
                'user_id' => $users->where('name', 'Rina Sartika')->first()->id,
                'motivation' => 'Medicine adalah passion saya, dan marathon adalah platform perfect untuk menggabungkan passion dengan professional skill. Ingin membantu people achieve their dreams safely.',
                'experience' => 'General Practitioner di Klinik Pratama (2 tahun), Medical volunteer untuk community health programs. Baru certified BTCLS bulan lalu, eager to gain more experience dalam event medicine.',
                'cv_path' => 'cvs/rina_sartika_general_practitioner.pdf',
                'status' => 'under_review',
                'reviewed_by' => null,
                'review_notes' => null,
                'reviewed_at' => null,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],

            // Applications for Event Volunteer - Registration & Information
            [
                'worker_opening_id' => $openings->where('title', 'Event Volunteer - Registration & Information')->first()->id,
                'user_id' => $users->where('name', 'Lina Marlina')->first()->id,
                'motivation' => 'Saya mahasiswa manajemen event yang antusias untuk mendapatkan hands-on experience dalam event basket international. Basketball adalah sport favorit saya sejak pequeno.',
                'experience' => 'Event volunteer untuk Indonesia Music Festival 2023-2024, campus event coordinator untuk University sports day. Pengalaman dengan customer service dan data entry.',
                'cv_path' => 'cvs/lina_marlina_event_volunteer.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Sari Indrawati')->first()->id,
                'review_notes' => 'Good customer service attitude dan event experience. Communication skills excellent. Recommended untuk public-facing role.',
                'reviewed_at' => Carbon::now()->subDays(7)->setTime(16, 00),
                'created_at' => Carbon::now()->subDays(9),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Event Volunteer - Registration & Information')->first()->id,
                'user_id' => $users->where('name', 'Eko Prasetyo')->first()->id,
                'motivation' => 'Sebagai someone yang suka membantu orang, volunteer opportunity ini sangat menarik. Basketball adalah sport exciting dan ingin berkontribusi dalam event sukses.',
                'experience' => 'Community volunteer untuk local charity events, part-time retail staff. Experience dengan public interaction dan problem solving.',
                'cv_path' => 'cvs/eko_prasetyo_volunteer.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Sari Indrawati')->first()->id,
                'review_notes' => 'Positive attitude dan willingness to help. Good dengan people dan dapat bekerja dalam tim. Suitable untuk volunteer role.',
                'reviewed_at' => Carbon::now()->subDays(6)->setTime(11, 30),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Event Volunteer - Registration & Information')->first()->id,
                'user_id' => $users->where('name', 'Rina Sartika')->first()->id,
                'motivation' => 'Mahasiswi komunikasi yang interested dalam event management. Basketball volunteer opportunity akan give me insight into international event organization.',
                'experience' => 'Student ambassador untuk university open house, volunteer coordinator untuk community service program. Leadership experience dan good communication.',
                'cv_path' => 'cvs/rina_sartika_comm_student.pdf',
                'status' => 'under_review',
                'reviewed_by' => null,
                'review_notes' => null,
                'reviewed_at' => null,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Event Volunteer - Registration & Information')->first()->id,
                'user_id' => $users->where('name', 'Ahmad Sutrisno')->first()->id,
                'motivation' => 'Ingin terlibat dalam event basket international dan contribute untuk development olahraga Indonesia. Experienced dalam event management dan ingin share knowledge.',
                'experience' => 'Event manager dengan 5+ tahun experience, trained dalam customer service excellence. Previous experience sebagai event coordinator untuk berbagai sports events.',
                'cv_path' => 'cvs/ahmad_sutrisno_overqualified_volunteer.pdf',
                'status' => 'rejected',
                'reviewed_by' => $users->where('name', 'Sari Indrawati')->first()->id,
                'review_notes' => 'Candidate terlalu qualified untuk volunteer position. Better fit untuk paid coordinator role. Appreciate interest though.',
                'reviewed_at' => Carbon::now()->subDays(4)->setTime(13, 45),
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(4),
            ],

            // Applications for Crowd Management Volunteer - Volleyball
            [
                'worker_opening_id' => $openings->where('title', 'Crowd Management Volunteer')->first()->id,
                'user_id' => $users->where('name', 'Andi Wijaya')->first()->id,
                'motivation' => 'Volleyball adalah sport favorit keluarga saya. Experience dalam crowd management akan give me opportunity untuk contribute dalam event yang meaningful sambil apply my skills.',
                'experience' => 'Security supervisor di mall department (3 tahun), crowd control volunteer untuk JakCloth festival 2023-2024. Physical fitness excellent dan experience dalam de-escalation techniques.',
                'cv_path' => 'cvs/andi_wijaya_security_experience.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Rudi Santoso')->first()->id,
                'review_notes' => 'Solid security background dan proven crowd management skills. Physical requirements met. Good attitude untuk customer-facing role.',
                'reviewed_at' => Carbon::now()->subDays(8)->setTime(15, 20),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(8),
            ],

            // Applications for Swimming Event Photographer
            [
                'worker_opening_id' => $openings->where('title', 'Swimming Event Photographer')->first()->id,
                'user_id' => $users->where('name', 'Maya Putri')->first()->id,
                'motivation' => 'Photography adalah passion saya, dan swimming event photographer opportunity combine my technical skills dengan love untuk sports. Ingin capture memorable moments dari athletes.',
                'experience' => 'Freelance photographer specializing in sports (2 tahun), previous work dengan Indonesian Swimming Federation untuk regional meets. Equipment: Canon 5D Mark IV, underwater housing, professional lighting setup.',
                'cv_path' => 'cvs/maya_putri_photographer_portfolio.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Budi Hartono')->first()->id,
                'review_notes' => 'Strong portfolio dengan sports photography experience. Technical skills proven dan equipment professional grade. Understanding untuk swimming photography specific challenges.',
                'reviewed_at' => Carbon::now()->subDays(15)->setTime(12, 00),
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Swimming Event Photographer')->first()->id,
                'user_id' => $users->where('name', 'Dewi Lestari')->first()->id,
                'motivation' => 'Photography hobbyist yang specifically interested dalam capturing human achievement moments. Swimming provides unique challenges dan opportunities untuk artistic expression.',
                'experience' => 'Hobbyist photographer dengan focus pada portrait dan action shots. Won local photography contest untuk action category. Own basic DSLR equipment dan editing software experience.',
                'cv_path' => 'cvs/dewi_lestari_hobbyist_photographer.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Maya Putri')->first()->id,
                'review_notes' => 'Good artistic eye dan enthusiasm untuk learning. While less professional experience, attitude excellent dan willing to learn. Good potential untuk growth.',
                'reviewed_at' => Carbon::now()->subDays(14)->setTime(16, 30),
                'created_at' => Carbon::now()->subDays(16),
                'updated_at' => Carbon::now()->subDays(14),
            ],
            [
                'worker_opening_id' => $openings->where('title', 'Swimming Event Photographer')->first()->id,
                'user_id' => $users->where('name', 'Rudi Santoso')->first()->id,
                'motivation' => 'Interested dalam sports photography dan swimming specifically. Want to contribute dalam event professional while developing photography skills.',
                'experience' => 'Beginner photographer dengan interest dalam sports documentation. Self-taught basic photography skills, learning advanced techniques. Own entry-level DSLR dan eager to learn.',
                'cv_path' => 'cvs/rudi_santoso_beginner_photographer.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Maya Putri')->first()->id,
                'review_notes' => 'Good potential dan willingness to learn. While skills masih developing, enthusiasm high dan coachable attitude. Perfect untuk junior photographer role under mentorship.',
                'reviewed_at' => Carbon::now()->subDays(13)->setTime(14, 45),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(13),
            ],

            // Applications for Food & Beverage Coordinator - Volleyball
            [
                'worker_opening_id' => $openings->where('title', 'Food & Beverage Coordinator')->first()->id,
                'user_id' => $users->where('name', 'Rina Sartika')->first()->id,
                'motivation' => 'Hospitality industry enthusiast yang excited untuk contribute dalam sports event. Food service background dan passion untuk creating positive experience untuk visitors.',
                'experience' => 'Restaurant supervisor di local café chain (2 tahun), catering coordinator untuk corporate events. Experience dengan food safety protocols, inventory management, dan team coordination.',
                'cv_path' => 'cvs/rina_sartika_hospitality_experience.pdf',
                'status' => 'accepted',
                'reviewed_by' => $users->where('name', 'Lina Marlina')->first()->id,
                'review_notes' => 'Strong hospitality background dan proven ability untuk manage food operations. Good organizational skills dan customer service oriented.',
                'reviewed_at' => Carbon::now()->subDays(9)->setTime(10, 15),
                'created_at' => Carbon::now()->subDays(11),
                'updated_at' => Carbon::now()->subDays(9),
            ],

            // Applications for various other positions
            [
                'worker_opening_id' => $openings->where('title', 'Medical Support Team - Marathon')->first()->id,
                'user_id' => $users->where('name', 'Sari Indrawati')->first()->id,
                'motivation' => 'Bachelor in Nursing dan memiliki interest dalam sports medicine. Want to gain experience dalam event medical support sambil contribute untuk community health.',
                'experience' => 'Fresh graduate Bachelor of Nursing, clinical experience dari magang di RS umum. Certified basic life support, eager to learn sports medicine specifics.',
                'cv_path' => 'cvs/sari_indrawati_nursing_grad.pdf',
                'status' => 'rejected',
                'reviewed_by' => $users->where('name', 'Dewi Lestari')->first()->id,
                'review_notes' => 'Good nursing foundation tapi lack experience untuk medical support role di event skala besar. Better fit untuk assistant role atau seek more experience first.',
                'reviewed_at' => Carbon::now()->subDays(4)->setTime(9, 30),
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(4),
            ]
        ];

        foreach ($applications as $application) {
            DB::table('applications')->insert($application);
        }
    }
}