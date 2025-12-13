<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@nocis.id'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'), // Simple password for testing
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Realistic Customers with Profiles
        $customers = [
            // Original 5
            ['name' => 'Budi Santoso', 'username' => 'budisantoso', 'email' => 'budi@example.com', 'phone' => '081234567890', 'address' => 'Jl. Sudirman No. 1, Jakarta', 'bio' => 'Experienced event volunteer enthusiast.', 'headline' => 'Event Crew'],
            ['name' => 'Siti Aminah', 'username' => 'sitiaminah', 'email' => 'siti@example.com', 'phone' => '081298765432', 'address' => 'Jl. Asia Afrika No. 10, Bandung', 'bio' => 'Passionate about sports and community service.', 'headline' => 'Social Media Manager'],
            ['name' => 'Reza Rahardian', 'username' => 'rezarahardian', 'email' => 'reza@example.com', 'phone' => '081345678901', 'address' => 'Jl. Malioboro No. 5, Yogyakarta', 'bio' => 'Creative professional looking for event roles.', 'headline' => 'Security Specialist'],
            ['name' => 'Dewi Persik', 'username' => 'dewipersik', 'email' => 'dewi@example.com', 'phone' => '081456789012', 'address' => 'Jl. Pahlawan No. 3, Surabaya', 'bio' => 'Medical student eager to help in sports medicine.', 'headline' => 'Medical Student'],
            ['name' => 'Andi Lau', 'username' => 'andilau', 'email' => 'andi@example.com', 'phone' => '081567890123', 'address' => 'Jl. Merdeka No. 8, Medan', 'bio' => 'Logistics expert with 5 years experience.', 'headline' => 'Logistics Coordinator'],
            
            // New 10+ Users
            ['name' => 'Rina Wulandari', 'username' => 'rinawulandari', 'email' => 'rina@example.com', 'phone' => '081678901234', 'address' => 'Jl. Diponegoro No. 12, Semarang', 'bio' => 'A keen photographer and content creator.', 'headline' => 'Freelance Photographer'],
            ['name' => 'Fajar Nugraha', 'username' => 'fajarnugraha', 'email' => 'fajar@example.com', 'phone' => '081789012345', 'address' => 'Jl. Gajah Mada No. 4, Jakarta', 'bio' => 'Certified First Aid responder.', 'headline' => 'Paramedic'],
            ['name' => 'Hana Pratiwi', 'username' => 'hanapratiwi', 'email' => 'hana@example.com', 'phone' => '081890123456', 'address' => 'Jl. Pemuda No. 7, Surabaya', 'bio' => 'Loves organizing community events.', 'headline' => 'Community Manager'],
            ['name' => 'Eko Prasetyo', 'username' => 'ekoprasetyo', 'email' => 'eko@example.com', 'phone' => '081901234567', 'address' => 'Jl. Jendral Sudirman No. 9, Balikpapan', 'bio' => 'Security personnel with event experience.', 'headline' => 'Security Guard'],
            ['name' => 'Maya Kartika', 'username' => 'mayakartika', 'email' => 'maya@example.com', 'phone' => '081212345678', 'address' => 'Jl. Anggrek No. 2, Denpasar', 'bio' => 'Fluent in English and Mandarin, great for liaison roles.', 'headline' => 'Liaison Officer'],
            ['name' => 'Dimas Anggara', 'username' => 'dimasanggara', 'email' => 'dimas@example.com', 'phone' => '081323456789', 'address' => 'Jl. Mawar No. 11, Bandung', 'bio' => 'Tech savvy and esports enthusiast.', 'headline' => 'IT Support'],
            ['name' => 'Lia Indriani', 'username' => 'liaindriani', 'email' => 'lia@example.com', 'phone' => '081434567890', 'address' => 'Jl. Melati No. 6, Malang', 'bio' => 'Detail oriented with admin experience.', 'headline' => 'Administrative Assistant'],
            ['name' => 'Bayu Saputra', 'username' => 'bayusaputra', 'email' => 'bayu@example.com', 'phone' => '081545678901', 'address' => 'Jl. Kenanga No. 15, Bogor', 'bio' => 'Strong swimmer and lifeguard certified.', 'headline' => 'Lifeguard'],
            ['name' => 'Tari Utami', 'username' => 'tariutami', 'email' => 'tari@example.com', 'phone' => '081656789012', 'address' => 'Jl. Cempaka No. 8, Solo', 'bio' => 'Culinary student, ready for consumption team.', 'headline' => 'Culinary Student'],
            ['name' => 'Rizky Billar', 'username' => 'rizkybillar', 'email' => 'rizky@example.com', 'phone' => '081767890123', 'address' => 'Jl. Flamboyan No. 20, Makassar', 'bio' => 'Outgoing personality, good for crowd control.', 'headline' => 'Crowd Control'],
            ['name' => 'Sarah Azhari', 'username' => 'sarahazhari', 'email' => 'sarah@example.com', 'phone' => '081878901234', 'address' => 'Jl. Dahlia No. 5, Palembang', 'bio' => 'Experienced in ticket sales and customer service.', 'headline' => 'Customer Service'],
            ['name' => 'Doni Salmanan', 'username' => 'donisalmanan', 'email' => 'doni@example.com', 'phone' => '081989012345', 'address' => 'Jl. Kamboja No. 14, Bandung', 'bio' => 'Fast runner, willing to be a pacer.', 'headline' => 'Runner / Pacer'],
        ];

        foreach ($customers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );

            // Create or Update Profile
            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'summary' => $data['bio'],
                    // 'cv_file' => $data['cv'] ?? null,
                    'profile_photo' => null,
                    'professional_headline' => $data['headline'] ?? null,
                ]
            );
        }
    }
}