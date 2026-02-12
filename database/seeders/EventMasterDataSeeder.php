<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Sport;

class EventMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();

        if ($events->isEmpty()) {
            $this->command->warn('No events found. Skipping master data seeding.');
            return;
        }

        foreach ($events as $event) {
            // Venue Locations
            $venues = [];
            foreach (
                [
                    ['gugus' => 'Gugus A', 'nama' => 'GBK Stadium Utama', 'alamat' => 'Jl. Pintu Satu Senayan, Jakarta'],
                    ['gugus' => 'Gugus A', 'nama' => 'GBK Tennis Indoor', 'alamat' => 'Kompleks GBK, Senayan'],
                    ['gugus' => 'Gugus B', 'nama' => 'Jakarta Velodrome', 'alamat' => 'Jl. Balap Sepeda, Rawamangun'],
                    ['gugus' => 'Gugus B', 'nama' => 'Aquatic Center', 'alamat' => 'Kompleks GBK, Senayan'],
                ] as $v
            ) {
                $venues[] = $event->venueLocations()->firstOrCreate(
                    ['nama' => $v['nama'], 'event_id' => $event->id],
                    $v
                );
            }

            // Jabatan
            $jabatanMap = [];
            foreach (['Ketua Umum', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Pelatih', 'Wasit', 'Atlet', 'Official'] as $nama) {
                $j = $event->jabatan()->firstOrCreate(
                    ['nama_jabatan' => $nama, 'event_id' => $event->id]
                );
                $jabatanMap[$nama] = $j->id;
            }

            // Disciplins — attach to event sports and first venue
            $eventSports = $event->sports;
            if ($eventSports->isNotEmpty() && !empty($venues)) {
                $disciplinData = [
                    'ATH' => ['100m Sprint', 'Long Jump', 'Shot Put'],
                    'SWM' => ['50m Freestyle', '100m Backstroke'],
                    'BDM' => ['Men Singles', 'Women Singles', 'Mixed Doubles'],
                    'CYC' => ['Road Race', 'Time Trial'],
                    'ESP' => ['Mobile Legends', 'PUBG Mobile'],
                    'SOC' => ['Men Football', 'Women Football'],
                    'BKB' => ['3x3 Basketball', '5x5 Basketball'],
                    'WLF' => ['56kg Class', '69kg Class'],
                    'VOLL' => ['Indoor Volleyball', 'Beach Volleyball'],
                    'ARC' => ['Recurve Individual', 'Compound Team'],
                ];

                foreach ($eventSports as $sport) {
                    $names = $disciplinData[$sport->code] ?? ['Umum'];
                    $venueIdx = 0;
                    foreach ($names as $name) {
                        $event->disciplins()->firstOrCreate(
                            ['nama_disiplin' => $name, 'event_id' => $event->id, 'sport_id' => $sport->id],
                            [
                                'sport_id' => $sport->id,
                                'venue_location_id' => $venues[$venueIdx % count($venues)]->id,
                                'nama_disiplin' => $name,
                                'keterangan' => "Disiplin {$name} untuk {$sport->name}",
                            ]
                        );
                        $venueIdx++;
                    }
                }
            }

            // Accreditations
            $accredData = [
                ['jabatan' => 'Atlet', 'nama_akreditasi' => 'Akreditasi Atlet', 'warna' => '#3B82F6'],
                ['jabatan' => 'Pelatih', 'nama_akreditasi' => 'Akreditasi Pelatih', 'warna' => '#10B981'],
                ['jabatan' => 'Wasit', 'nama_akreditasi' => 'Akreditasi Wasit', 'warna' => '#F59E0B'],
                ['jabatan' => 'Official', 'nama_akreditasi' => 'Akreditasi Official', 'warna' => '#EF4444'],
                ['jabatan' => 'Ketua Umum', 'nama_akreditasi' => 'Akreditasi VIP', 'warna' => '#8B5CF6'],
            ];
            foreach ($accredData as $a) {
                if (isset($jabatanMap[$a['jabatan']])) {
                    $event->accreditations()->firstOrCreate(
                        ['nama_akreditasi' => $a['nama_akreditasi'], 'event_id' => $event->id],
                        [
                            'jabatan_id' => $jabatanMap[$a['jabatan']],
                            'nama_akreditasi' => $a['nama_akreditasi'],
                            'warna' => $a['warna'],
                            'keterangan' => $a['nama_akreditasi'],
                        ]
                    );
                }
            }

            // Accommodation Codes
            foreach (
                [
                    ['kode' => 'HTL-01', 'keterangan' => 'Hotel Bintang 5 - Atlet Utama'],
                    ['kode' => 'HTL-02', 'keterangan' => 'Hotel Bintang 4 - Official & Pelatih'],
                    ['kode' => 'WIS-01', 'keterangan' => 'Wisma Atlet Kemayoran'],
                ] as $ac
            ) {
                $event->accommodationCodes()->firstOrCreate(
                    ['kode' => $ac['kode'], 'event_id' => $event->id],
                    $ac
                );
            }

            // Transportation Codes
            foreach (
                [
                    ['kode' => 'BUS-A', 'keterangan' => 'Bus Shuttle Venue A - Hotel'],
                    ['kode' => 'BUS-B', 'keterangan' => 'Bus Shuttle Venue B - Wisma'],
                    ['kode' => 'VIP-01', 'keterangan' => 'Kendaraan VIP'],
                ] as $tc
            ) {
                $event->transportationCodes()->firstOrCreate(
                    ['kode' => $tc['kode'], 'event_id' => $event->id],
                    $tc
                );
            }

            // Zone Access Codes
            foreach (
                [
                    ['kode_zona' => 'ZONA-1', 'keterangan' => 'Zona Pertandingan (Field of Play)'],
                    ['kode_zona' => 'ZONA-2', 'keterangan' => 'Zona Media & Press'],
                    ['kode_zona' => 'ZONA-3', 'keterangan' => 'Zona VIP & VVIP'],
                    ['kode_zona' => 'ZONA-4', 'keterangan' => 'Zona Publik'],
                ] as $za
            ) {
                $event->zoneAccessCodes()->firstOrCreate(
                    ['kode_zona' => $za['kode_zona'], 'event_id' => $event->id],
                    $za
                );
            }

            // Venue Accesses
            foreach (
                [
                    ['nama_vanue' => 'Pintu Masuk Utama', 'keterangan' => 'Gate utama untuk semua akreditasi'],
                    ['nama_vanue' => 'Pintu Atlet', 'keterangan' => 'Khusus atlet dan pelatih'],
                    ['nama_vanue' => 'Pintu VIP', 'keterangan' => 'Khusus VIP dan VVIP'],
                    ['nama_vanue' => 'Pintu Media', 'keterangan' => 'Khusus media dan press'],
                ] as $va
            ) {
                $event->venueAccesses()->firstOrCreate(
                    ['nama_vanue' => $va['nama_vanue'], 'event_id' => $event->id],
                    $va
                );
            }
        }

        $this->command->info('Event master data seeded for ' . $events->count() . ' event(s).');
    }
}
