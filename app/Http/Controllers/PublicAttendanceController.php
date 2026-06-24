<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicAttendanceController extends Controller
{
    /**
     * Show students who have attended today.
     */
    public function present()
    {
        $today = Carbon::today()->format('Y-m-d');
        $students = Student::whereHas('attendances', function($query) use ($today) {
            $query->whereDate('check_in_at', $today);
        })->with('user')->get();

        return view('attendance.public_list', [
            'title' => 'Siswa Sudah Presensi',
            'students' => $students,
            'theme' => 'green',
            'subtitle' => 'Daftar siswa yang telah tercatat hadir hari ini.',
            'password' => 'PRESENT'
        ]);
    }

    /**
     * Show students who have NOT attended today.
     */
    public function absent()
    {
        $today = Carbon::today()->format('Y-m-d');
        $students = Student::whereDoesntHave('attendances', function($query) use ($today) {
            $query->whereDate('check_in_at', $today);
        })->with('user')->get();

        return view('attendance.public_list', [
            'title' => 'Siswa Belum Presensi',
            'students' => $students,
            'theme' => 'red',
            'subtitle' => 'Daftar siswa yang belum melakukan presensi hari ini.',
            'password' => 'ABSENT'
        ]);
    }

    /**
     * Get recent attendances of today.
     */
    public function recentAttendances()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $attendances = Attendance::with(['student.user'])
            ->where(function($q) use ($today) {
                $q->whereDate('check_in_at', $today)
                  ->orWhereDate('check_out_at', $today)
                  ->orWhereDate('updated_at', $today);
            })
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($attendance) {
                $name = $attendance->student->user->name ?? 'Siswa';
                $class = $attendance->student->class ?? '-';
                $gender = $attendance->student->user->gender ?? 'man';
                $avatar = $attendance->student->user->avatar ?? null;
                
                $type = 'masuk';
                $time = $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : null;
                $statusLabel = $attendance->check_in_status === 'present' ? 'Hadir (Tepat Waktu)' : 'Terlambat';
                
                if ($attendance->check_out_at && (!$attendance->check_in_at || $attendance->check_out_at->gt($attendance->check_in_at))) {
                    $type = 'keluar';
                    $time = $attendance->check_out_at->format('H:i');
                    $statusLabel = 'Pulang';
                }
                
                return [
                    'id' => $attendance->id . '-' . $type,
                    'name' => $name,
                    'class' => $class,
                    'time' => $time,
                    'type' => $type,
                    'status_label' => $statusLabel,
                    'gender' => $gender,
                    'avatar' => $avatar ? asset($avatar) : null,
                    'timestamp' => $type === 'keluar' ? $attendance->check_out_at->timestamp : ($attendance->check_in_at ? $attendance->check_in_at->timestamp : 0)
                ];
            })
            ->sortByDesc('timestamp')
            ->values();

        return response()->json($attendances);
    }
}
