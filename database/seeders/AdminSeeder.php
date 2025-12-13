<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin account
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@selat.com',
            'password' => Hash::make('desaSelat2025'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        $this->command->info('Admin account created successfully!');
        $this->command->info('Email: superadmin@selat.com');
        $this->command->info('Password: desaSelat2025');
    }
}
