<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '08123456789',
            'bio' => 'Administrator sistem',
        ]);

        // User
        User::create([
            'name' => 'User Customer',
            'email' => 'user@user.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '08987654321',
            'bio' => 'Customer biasa',
        ]);

        // Sample Products
        Product::create([
            'name' => 'Laptop ASUS ROG',
            'price' => 15500000,
            'category' => 'Elektronik',
            'stock' => 25,
            'popular' => true,
            'description' => 'Gaming laptop high-end',
        ]);

        Product::create([
            'name' => 'iPhone 14 Pro',
            'price' => 18200000,
            'category' => 'Elektronik',
            'stock' => 50,
            'popular' => true,
            'description' => 'Latest iPhone',
        ]);

        Product::create([
            'name' => 'Mechanical Keyboard',
            'price' => 1250000,
            'category' => 'Aksesoris',
            'stock' => 100,
            'popular' => true,
            'description' => 'RGB Mechanical Keyboard',
        ]);
    }
}