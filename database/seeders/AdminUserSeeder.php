<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '08123456789',
            'bio' => 'Administrator sistem',
        ]);

        // User default
        User::create([
            'name' => 'User Customer',
            'email' => 'user@user.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '08987654321',
            'bio' => 'Customer biasa',
        ]);
    }
}