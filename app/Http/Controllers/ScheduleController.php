<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::all();
        return view('schedules.index', compact('schedules'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.late_time' => 'nullable|date_format:H:i',
            'schedules.*.checkout_start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i',
        ]);

        foreach ($request->schedules as $id => $data) {
            $schedule = Schedule::find($id);
            if ($schedule) {
                $schedule->update([
                    'start_time' => $data['start_time'] ?? null,
                    'late_time' => $data['late_time'] ?? null,
                    'checkout_start_time' => $data['checkout_start_time'] ?? null,
                    'end_time' => $data['end_time'] ?? null,
                    'is_off' => isset($data['is_off']),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
    }
}
