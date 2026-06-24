<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Fauzan Trisuladana',
            'email' => 'fauzantrisuladana@gmail.com',
            'role' => 'bendahara',
            'status' => 'Aktif',
            'provider' => 'google',
            'id_provider' => '105102120350029888485',
            'profile_image' => 'https://lh3.googleusercontent.com/a/ACg8ocJgjcrmN_OSQiAu_cwa0iqqeCT2DJCNExzdL7ztc3_I2er2KRM1=s96-c',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'activated_at' => now(),
        ]);
    }
}
