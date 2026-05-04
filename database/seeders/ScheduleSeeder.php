<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            'Senin' => ['07:00', '07:15', '11:30', '17:00', false],
            'Selasa' => ['07:00', '07:15', '11:30', '17:00', false],
            'Rabu' => ['07:00', '07:15', '11:30', '17:00', false],
            'Kamis' => ['07:00', '07:15', '11:30', '17:00', false],
            'Jumat' => ['07:00', '07:15', '11:30', '17:00', false],
            'Sabtu' => [null, null, null, null, true],
            'Minggu' => [null, null, null, null, true],
        ];

        foreach ($days as $day => $config) {
            \App\Models\Schedule::create([
                'day' => $day,
                'start_time' => $config[0],
                'late_time' => $config[1],
                'checkout_start_time' => $config[2],
                'end_time' => $config[3],
                'is_off' => $config[4],
            ]);
        }
    }
}
