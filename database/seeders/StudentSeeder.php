<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create User account for Student
        $user = User::create([
            'name' => 'Siswa Test',
            'username' => 'siswa',
            'email' => 'siswa@gentasyaputra.sch.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'student', // Ensure 'role' column supports 'student'
            'remember_token' => Str::random(10),
        ]);

        // 2. Create Student Data Linked to User
        Student::create([
            'user_id' => $user->id,
            'class' => 'XII IPA 1',
            // Add other fields if required by your Student model, like 'phone', 'address' etc
        ]);

        // Create random dummy students
        $dummyStudents = User::factory()->count(10)->create([
            'role' => 'student',
            'password' => Hash::make('password'),
        ]);

        foreach ($dummyStudents as $studentUser) {
            Student::create([
                'user_id' => $studentUser->id,
                'class' => 'XI IPA ' . rand(1, 3),
            ]);
        }
    }
}
