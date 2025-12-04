<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // Aceh
            ['name' => 'Banda Aceh', 'province' => 'Aceh', 'type' => 'city'],
            ['name' => 'Sabang', 'province' => 'Aceh', 'type' => 'city'],
            ['name' => 'Lhokseumawe', 'province' => 'Aceh', 'type' => 'city'],
            
            // Sumatera Utara
            ['name' => 'Medan', 'province' => 'Sumatera Utara', 'type' => 'city'],
            ['name' => 'Pematang Siantar', 'province' => 'Sumatera Utara', 'type' => 'city'],
            ['name' => 'Tanjungbalai', 'province' => 'Sumatera Utara', 'type' => 'city'],
            ['name' => 'Tebing Tinggi', 'province' => 'Sumatera Utara', 'type' => 'city'],
            ['name' => 'Binjai', 'province' => 'Sumatera Utara', 'type' => 'city'],
            ['name' => 'Sibolga', 'province' => 'Sumatera Utara', 'type' => 'city'],
            
            // Sumatera Barat
            ['name' => 'Padang', 'province' => 'Sumatera Barat', 'type' => 'city'],
            ['name' => 'Padang Panjang', 'province' => 'Sumatera Barat', 'type' => 'city'],
            ['name' => 'Bukittinggi', 'province' => 'Sumatera Barat', 'type' => 'city'],
            ['name' => 'Pagar Alam', 'province' => 'Sumatera Barat', 'type' => 'city'],
            ['name' => 'Pariaman', 'province' => 'Sumatera Barat', 'type' => 'city'],
            ['name' => 'Payakumbuh', 'province' => 'Sumatera Barat', 'type' => 'city'],
            ['name' => 'Sawahlunto', 'province' => 'Sumatera Barat', 'type' => 'city'],
            ['name' => 'Solok', 'province' => 'Sumatera Barat', 'type' => 'city'],
            
            // Riau
            ['name' => 'Pekanbaru', 'province' => 'Riau', 'type' => 'city'],
            ['name' => 'Dumai', 'province' => 'Riau', 'type' => 'city'],
            
            // Jambi
            ['name' => 'Jambi', 'province' => 'Jambi', 'type' => 'city'],
            ['name' => 'Sungai Penuh', 'province' => 'Jambi', 'type' => 'city'],
            
            // Sumatera Selatan
            ['name' => 'Palembang', 'province' => 'Sumatera Selatan', 'type' => 'city'],
            ['name' => 'Lubuklinggau', 'province' => 'Sumatera Selatan', 'type' => 'city'],
            ['name' => 'Pagar Alam', 'province' => 'Sumatera Selatan', 'type' => 'city'],
            ['name' => 'Prabumulih', 'province' => 'Sumatera Selatan', 'type' => 'city'],
            
            // Bengkulu
            ['name' => 'Bengkulu', 'province' => 'Bengkulu', 'type' => 'city'],
            
            // Lampung
            ['name' => 'Bandar Lampung', 'province' => 'Lampung', 'type' => 'city'],
            ['name' => 'Metro', 'province' => 'Lampung', 'type' => 'city'],
            
            // Kep. Bangka Belitung
            ['name' => 'Pangkal Pinang', 'province' => 'Kepulauan Bangka Belitung', 'type' => 'city'],
            
            // Kep. Riau
            ['name' => 'Tanjung Pinang', 'province' => 'Kepulauan Riau', 'type' => 'city'],
            ['name' => 'Batam', 'province' => 'Kepulauan Riau', 'type' => 'city'],
            
            // DKI Jakarta
            ['name' => 'Jakarta Pusat', 'province' => 'DKI Jakarta', 'type' => 'city'],
            ['name' => 'Jakarta Utara', 'province' => 'DKI Jakarta', 'type' => 'city'],
            ['name' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'type' => 'city'],
            ['name' => 'Jakarta Timur', 'province' => 'DKI Jakarta', 'type' => 'city'],
            ['name' => 'Jakarta Barat', 'province' => 'DKI Jakarta', 'type' => 'city'],
            ['name' => 'Kepulauan Seribu', 'province' => 'DKI Jakarta', 'type' => 'city'],
            
            // Jawa Barat
            ['name' => 'Bogor', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Depok', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Bekasi', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Cimahi', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Tasikmalaya', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Cirebon', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Sukabumi', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Bandung', 'province' => 'Jawa Barat', 'type' => 'city'],
            ['name' => 'Banjar', 'province' => 'Jawa Barat', 'type' => 'city'],
            
            // Jawa Tengah
            ['name' => 'Semarang', 'province' => 'Jawa Tengah', 'type' => 'city'],
            ['name' => 'Salatiga', 'province' => 'Jawa Tengah', 'type' => 'city'],
            ['name' => 'Pekalongan', 'province' => 'Jawa Tengah', 'type' => 'city'],
            ['name' => 'Tegal', 'province' => 'Jawa Tengah', 'type' => 'city'],
            ['name' => 'Magelang', 'province' => 'Jawa Tengah', 'type' => 'city'],
            ['name' => 'Surakarta', 'province' => 'Jawa Tengah', 'type' => 'city'],
            ['name' => 'Ungaran', 'province' => 'Jawa Tengah', 'type' => 'city'],
            ['name' => 'Kediri', 'province' => 'Jawa Tengah', 'type' => 'city'],
            
            // DI Yogyakarta
            ['name' => 'Yogyakarta', 'province' => 'DI Yogyakarta', 'type' => 'city'],
            
            // Jawa Timur
            ['name' => 'Malang', 'province' => 'Jawa Timur', 'type' => 'city'],
            ['name' => 'Blitar', 'province' => 'Jawa Timur', 'type' => 'city'],
            ['name' => 'Kediri', 'province' => 'Jawa Timur', 'type' => 'city'],
            ['name' => 'Madiun', 'province' => 'Jawa Timur', 'type' => 'city'],
            ['name' => 'Pasuruan', 'province' => 'Jawa Timur', 'type' => 'city'],
            ['name' => 'Probolinggo', 'province' => 'Jawa Timur', 'type' => 'city'],
            ['name' => 'Surabaya', 'province' => 'Jawa Timur', 'type' => 'city'],
            ['name' => 'Batu', 'province' => 'Jawa Timur', 'type' => 'city'],
            
            // Banten
            ['name' => 'Serang', 'province' => 'Banten', 'type' => 'city'],
            ['name' => 'Cilegon', 'province' => 'Banten', 'type' => 'city'],
            ['name' => 'Tangerang', 'province' => 'Banten', 'type' => 'city'],
            ['name' => 'Tangerang Selatan', 'province' => 'Banten', 'type' => 'city'],
            
            // Bali
            ['name' => 'Denpasar', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Badung', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Gianyar', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Tabanan', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Bangli', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Karangasem', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Klungkung', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Jembrana', 'province' => 'Bali', 'type' => 'city'],
            ['name' => 'Buleleng', 'province' => 'Bali', 'type' => 'city'],
            
            // Nusa Tenggara Barat
            ['name' => 'Mataram', 'province' => 'Nusa Tenggara Barat', 'type' => 'city'],
            ['name' => 'Bima', 'province' => 'Nusa Tenggara Barat', 'type' => 'city'],
            
            // Nusa Tenggara Timur
            ['name' => 'Kupang', 'province' => 'Nusa Tenggara Timur', 'type' => 'city'],
            
            // Kalimantan Barat
            ['name' => 'Pontianak', 'province' => 'Kalimantan Barat', 'type' => 'city'],
            ['name' => 'Singkawang', 'province' => 'Kalimantan Barat', 'type' => 'city'],
            
            // Kalimantan Tengah
            ['name' => 'Palangkaraya', 'province' => 'Kalimantan Tengah', 'type' => 'city'],
            
            // Kalimantan Selatan
            ['name' => 'Banjarmasin', 'province' => 'Kalimantan Selatan', 'type' => 'city'],
            ['name' => 'Banjarbari', 'province' => 'Kalimantan Selatan', 'type' => 'city'],
            
            // Kalimantan Timur
            ['name' => 'Balikpapan', 'province' => 'Kalimantan Timur', 'type' => 'city'],
            ['name' => 'Bontang', 'province' => 'Kalimantan Timur', 'type' => 'city'],
            ['name' => 'Samarinda', 'province' => 'Kalimantan Timur', 'type' => 'city'],
            
            // Kalimantan Utara
            ['name' => 'Tarakan', 'province' => 'Kalimantan Utara', 'type' => 'city'],
            
            // Sulawesi Utara
            ['name' => 'Manado', 'province' => 'Sulawesi Utara', 'type' => 'city'],
            ['name' => 'Bitung', 'province' => 'Sulawesi Utara', 'type' => 'city'],
            ['name' => 'Tomohon', 'province' => 'Sulawesi Utara', 'type' => 'city'],
            ['name' => 'Kotamobagu', 'province' => 'Sulawesi Utara', 'type' => 'city'],
            
            // Sulawesi Tengah
            ['name' => 'Palu', 'province' => 'Sulawesi Tengah', 'type' => 'city'],
            
            // Sulawesi Selatan
            ['name' => 'Makassar', 'province' => 'Sulawesi Selatan', 'type' => 'city'],
            ['name' => 'Parepare', 'province' => 'Sulawesi Selatan', 'type' => 'city'],
            ['name' => 'Palopo', 'province' => 'Sulawesi Selatan', 'type' => 'city'],
            
            // Sulawesi Tenggara
            ['name' => 'Kendari', 'province' => 'Sulawesi Tenggara', 'type' => 'city'],
            ['name' => 'Baubau', 'province' => 'Sulawesi Tenggara', 'type' => 'city'],
            
            // Gorontalo
            ['name' => 'Gorontalo', 'province' => 'Gorontalo', 'type' => 'city'],
            
            // Sulawesi Barat
            ['name' => 'Mamuju', 'province' => 'Sulawesi Barat', 'type' => 'city'],
            
            // Maluku
            ['name' => 'Ambon', 'province' => 'Maluku', 'type' => 'city'],
            ['name' => 'Tual', 'province' => 'Maluku', 'type' => 'city'],
            
            // Maluku Utara
            ['name' => 'Ternate', 'province' => 'Maluku Utara', 'type' => 'city'],
            ['name' => 'Tidore', 'province' => 'Maluku Utara', 'type' => 'city'],
            
            // Papua Barat
            ['name' => 'Sorong', 'province' => 'Papua Barat', 'type' => 'city'],
            ['name' => 'Manokwari', 'province' => 'Papua Barat', 'type' => 'city'],
            
            // Papua
            ['name' => 'Jayapura', 'province' => 'Papua', 'type' => 'city'],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => $city['name'],
                'province' => $city['province'],
                'type' => $city['type'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}