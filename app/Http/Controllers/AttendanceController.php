<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Notifications\StudentAttendanceNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AttendanceController extends Controller
{
    // Time constants
    const CHECK_IN_START = '07:00';   // Mulai boleh absen masuk
    const CHECK_IN_LIMIT = '07:15';   // Batas akhir absen masuk & jam masuk
    const CHECK_OUT_TIME = '11:30';   // Mulai boleh absen pulang
    const CHECK_OUT_END  = '17:15';   // Akhir sesi absen pulang

    /**
     * Show the scan page for GURU only.
     */
    public function scan()
    {
        $user = Auth::user();

        if (!$user->isGuru()) {
            abort(403, 'Hanya Guru yang dapat melakukan scan.');
        }

        $teacher = $user->teacher;

        if (!$teacher || !$teacher->assigned_class) {
            return view('attendance.scan', [
                'assignedClass' => null,
                'mode' => null,
                'error' => 'Anda belum memiliki kelas yang ditugaskan. Hubungi Admin.',
            ]);
        }

        // Default mode based on time
        $now = Carbon::now();
        $currentTime = $now->format('H:i');
        
        // Logic: 
        // 1. If current time is before the check-out window, default to 'masuk'
        // 2. If current time is on or after check-out starts, default to 'keluar'
        $mode = 'masuk';
        if ($currentTime >= self::CHECK_OUT_TIME) {
            $mode = 'keluar';
        }

        return view('attendance.scan', [
            'assignedClass' => $teacher->assigned_class,
            'mode' => $mode,
            'error' => null,
            'timeRules' => [
                'masuk_start' => self::CHECK_IN_START,
                'masuk_end' => self::CHECK_IN_LIMIT,
                'keluar_start' => self::CHECK_OUT_TIME,
                'keluar_end' => self::CHECK_OUT_END,
            ]
        ]);
    }

    /**
     * Handle the scanned QR code (Scanned by GURU).
     * Expected QR Content: Student NIS (e.g., "12345678").
     */
    public function store(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'mode' => 'required|in:masuk,keluar',
        ]);

        $user = Auth::user();

        if (!$user->isGuru()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya Guru yang dapat melakukan scan.'
            ], 403);
        }

        $teacher = $user->teacher;

        if (!$teacher || !$teacher->assigned_class) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda belum memiliki kelas yang ditugaskan.'
            ], 403);
        }

        // Find student by username (from the users table)
        $username = $request->input('qr_code');
        $student = Student::whereHas('user', function($query) use ($username) {
            $query->where('username', $username);
        })->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mohon maaf, data siswa dengan Username tersebut tidak ditemukan dalam sistem.'
            ], 404);
        }

        // Validate class assignment
        if ($student->class !== $teacher->assigned_class) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, {$student->user->name} tercatat sebagai siswa kelas {$student->class}."
            ], 403);
        }

        $now = Carbon::now();
        $today = Carbon::today()->format('Y-m-d');
        $mode = $request->input('mode');

        // Get or create today's attendance record
        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('check_in_at', $today)
            ->orWhere(function ($query) use ($student, $today) {
                $query->where('student_id', $student->id)
                      ->whereDate('scanned_at', $today);
            })
            ->first();

        // Use mode from frontend (teacher's choice)
        if ($mode === 'masuk') {
            return $this->handleCheckIn($student, $teacher, $attendance, $now);
        } else {
            return $this->handleCheckOut($student, $teacher, $attendance, $now);
        }
    }

    /**
     * Handle Check-In logic.
     */
    private function handleCheckIn(Student $student, $teacher, $attendance, Carbon $now)
    {
        // Already checked in today?
        if ($attendance && $attendance->check_in_at) {
            $checkInTime = Carbon::parse($attendance->check_in_at)->format('H:i');
            return response()->json([
                'status' => 'warning',
                'message' => "Mohon maaf, {$student->user->name} sudah tercatat melakukan absensi masuk pada pukul {$checkInTime}.",
                'type' => 'masuk',
            ]);
        }

        $currentTime = $now->format('H:i');

        // Block if before 06:00
        if ($currentTime < self::CHECK_IN_START) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, jadwal absensi masuk baru akan dimulai pada pukul " . self::CHECK_IN_START . ".",
                'type' => 'masuk',
            ], 403);
        }

        // Block if after 07:15
        if ($currentTime > self::CHECK_IN_LIMIT) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, sesi absensi masuk untuk hari ini telah ditutup pada pukul " . self::CHECK_IN_LIMIT . ".",
                'type' => 'masuk',
            ], 403);
        }

        // Determine check-in status
        $checkInStatus = 'present';
        $statusLabel = 'HADIR (Tepat Waktu)';
        if ($currentTime > self::CHECK_IN_LIMIT) {
            $checkInStatus = 'late';
            $statusLabel = 'TERLAMBAT';
        }

        // Create attendance record
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'scanned_at' => $now,
            'status' => $checkInStatus,
            'check_in_at' => $now,
            'check_in_status' => $checkInStatus,
        ]);

        // Send Notifications
        $details = [
            'title' => 'Absensi Masuk Baru',
            'message' => "Siswa {$student->user->name} ({$student->class}) telah melakukan absensi masuk ({$statusLabel}).",
            'type' => $checkInStatus === 'present' ? 'success' : 'warning',
            'student_name' => $student->user->name,
            'status' => $checkInStatus
        ];
        
        // Notify Teacher
        if ($teacher->user) {
            $teacher->user->notify(new StudentAttendanceNotification($details));
        }

        // Notify Student
        if ($student->user) {
            $student->user->notify(new StudentAttendanceNotification([
                'title' => 'Konfirmasi Absensi Masuk',
                'message' => "Absensi masuk Anda telah tercatat pada pukul {$now->format('H:i')} sebagai {$statusLabel}.",
                'type' => 'info',
            ]));
        }

        return response()->json([
            'status' => 'success',
            'message' => "{$student->user->name} ({$student->class}) • {$statusLabel}",
            'type' => 'masuk',
        ]);
    }

    /**
     * Handle Check-Out logic.
     */
    private function handleCheckOut(Student $student, $teacher, $attendance, Carbon $now)
    {
        // Must check-in first
        if (!$attendance || !$attendance->check_in_at) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, {$student->user->name} belum melakukan absensi masuk hari ini, sehingga belum dapat absen keluar.",
                'type' => 'keluar',
            ]);
        }

        // Already checked out today?
        if ($attendance->check_out_at) {
            $checkOutTime = Carbon::parse($attendance->check_out_at)->format('H:i');
            return response()->json([
                'status' => 'warning',
                'message' => "Mohon maaf, {$student->user->name} sudah tercatat melakukan absensi keluar pada pukul {$checkOutTime}.",
                'type' => 'keluar',
            ]);
        }

        $currentTime = $now->format('H:i');

        // Block if before 11:30
        if ($currentTime < self::CHECK_OUT_TIME) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, jadwal absensi kepulangan baru akan dimulai pada pukul " . self::CHECK_OUT_TIME . ".",
                'type' => 'keluar',
            ], 403);
        }

        // Block if after 18:00
        if ($currentTime > self::CHECK_OUT_END) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, batas waktu absensi kepulangan untuk hari ini telah berakhir.",
                'type' => 'keluar',
            ], 403);
        }

        // Determine check-out status
        $checkOutStatus = 'present';
        $statusLabel = 'PULANG (Tepat Waktu)';

        // Update attendance record with check-out
        $attendance->update([
            'check_out_at' => $now,
            'check_out_status' => $checkOutStatus,
        ]);

        // Send Notifications
        $details = [
            'title' => 'Absensi Keluar Baru',
            'message' => "Siswa {$student->user->name} ({$student->class}) telah melakukan absensi keluar ({$statusLabel}).",
            'type' => 'success',
            'student_name' => $student->user->name,
            'status' => $checkOutStatus
        ];

        // Notify Teacher
        if ($teacher->user) {
            $teacher->user->notify(new StudentAttendanceNotification($details));
        }

        // Notify Student
        if ($student->user) {
            $student->user->notify(new StudentAttendanceNotification([
                'title' => 'Konfirmasi Absensi Keluar',
                'message' => "Absensi keluar Anda telah tercatat pada pukul {$now->format('H:i')} sebagai {$statusLabel}.",
                'type' => 'info',
            ]));
        }

        return response()->json([
            'status' => 'success',
            'message' => "{$student->user->name} ({$student->class}) • {$statusLabel}",
            'type' => 'keluar',
        ]);
    }

    /**
     * Show the Digital ID Card for STUDENT.
     * Contains Static QR with their NIS (username).
     *
     * Best Practice: Validate ALL required data in the controller
     * before the view is rendered. Never let null reach the view layer.
     */
    public function showStudentQr()
    {
        $user = Auth::user();

        // 1. Ensure student profile exists
        if (!$user->student) {
            abort(404, 'Data profil siswa tidak ditemukan.');
        }

        // 2. Ensure user relation on student is loaded
        $student = $user->student->load('user');

        // 3. Ensure QR text (username) is not null — fail early with a clear message
        if (empty($user->username)) {
            return redirect()->back()->withErrors([
                'qr' => 'Username / NIS belum diatur. Silakan hubungi administrator untuk melengkapi data Anda.'
            ]);
        }

        // 4. Cast to string explicitly as a final safety measure before passing to view
        return view('attendance.my-qr', [
            'student'   => $student,
            'qrContent' => (string) $user->username,
        ]);
    }

    /**
     * Student: View history.
     */
    public function history()
    {
        $user = Auth::user();
        $attendances = Attendance::where('student_id', $user->student->id)
            ->latest('check_in_at')
            ->paginate(10);

        return view('attendance.history', compact('attendances'));
    }
}
