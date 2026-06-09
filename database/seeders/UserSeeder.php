<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test User Biasa',
            'email' => 'test@example.com',
            'role' => 'biasa',
            'status' => 'Aktif',
            'password' => Hash::make('password'),
            'activated_at' => now(),
        ]);

        User::create([
            'name' => 'Test User Bendahara',
            'email' => 'fauzantrisuladana@gmail.com',
            'role' => 'bendahara',
            'status' => 'Aktif',
            'password' => Hash::make('password'),
            'activated_at' => now(),
        ]);

        User::create([
            'name' => 'User Pending',
            'email' => 'pending@example.com',
            'role' => 'biasa',
            'status' => 'Pending',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'User Tidak Aktif',
            'email' => 'inactive@example.com',
            'role' => 'bendahara',
            'status' => 'Tidak Aktif',
            'password' => Hash::make('password'),
        ]);
    }
}
