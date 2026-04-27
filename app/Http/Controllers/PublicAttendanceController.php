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
            'subtitle' => 'Daftar siswa yang telah tercatat hadir hari ini.'
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
            'subtitle' => 'Daftar siswa yang belum melakukan presensi hari ini.'
        ]);
    }
}
