<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = ['XII IPA 1', 'XII IPA 2', 'XII IPA 3', 'XI IPA 1', 'XI IPA 2', 'XI IPA 3'];

        // Main test teacher
        if (!User::where('email', 'guru@gentasyaputra.sch.id')->exists()) {
            $guru = User::create([
                'name' => 'Guru Test',
                'username' => 'guru',
                'email' => 'guru@gentasyaputra.sch.id',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'guru',
                'remember_token' => Str::random(10),
            ]);
            
            \App\Models\Teacher::create([
                'user_id' => $guru->id,
                'assigned_class' => 'XII IPA 1',
            ]);
        }

        // Additional teachers with different class assignments
        $additionalTeachers = [
            ['name' => 'Ibu Siti Aminah, S.Pd', 'email' => 'siti@gentasyaputra.sch.id', 'class' => 'XII IPA 2'],
            ['name' => 'Bapak Ahmad Fauzi, M.Pd', 'email' => 'ahmad@gentasyaputra.sch.id', 'class' => 'XII IPA 3'],
            ['name' => 'Ibu Dewi Lestari, S.Pd', 'email' => 'dewi@gentasyaputra.sch.id', 'class' => 'XI IPA 1'],
        ];

        foreach ($additionalTeachers as $data) {
            if (!User::where('email', $data['email'])->exists()) {
                $username = strstr($data['email'], '@', true);
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $username,
                    'email' => $data['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'guru',
                    'remember_token' => Str::random(10),
                ]);

                \App\Models\Teacher::create([
                    'user_id' => $user->id,
                    'assigned_class' => $data['class'],
                ]);
            }
        }
    }
}
