<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus user lama jika ada, lalu buat user Admin baru
        User::query()->delete();

        User::create([
            'name' => 'Admin Arsitek',
            'email' => 'Admin@akademiarsitek.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'role' => 'Admin',
            'access_expires_at' => now()->addYears(100), // Akses Admin tidak terbatas
        ]);

        // Membuat 10 user biasa untuk data contoh
        User::factory(10)->create();
    }
}
