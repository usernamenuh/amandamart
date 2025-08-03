<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // ✅ Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
            'is_active' => true, // ✅ Tambahkan ini jika perlu
        ]);
        User::factory()->create([
            'name' => 'owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'user',
            'is_active' => true, // ✅ Tambahkan ini jika perlu
        ]);
    }
}
