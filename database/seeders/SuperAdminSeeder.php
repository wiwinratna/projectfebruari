<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@nocis.id'],
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('password'), // Change this in production!
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'event_id' => null, // Super admin doesn't need event assignment
            ]
        );

        // Output message
        echo "Super Admin created/updated:\n";
        echo "  Email: superadmin@nocis.id\n";
        echo "  Username: superadmin\n";
        echo "  Password: password (change in production!)\n";
    }
}
