<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists to prevent duplicates
        if (!User::where('email', 'admin@gentasyaputra.sch.id')->exists()) {
            User::create([
                'name' => 'Admin Genta',
                'email' => 'admin@gentasyaputra.sch.id',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
