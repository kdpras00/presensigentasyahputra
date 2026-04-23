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

        // Find student by NIS
        $nis = $request->input('qr_code');
        $student = Student::where('nis', $nis)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa dengan NIS tersebut tidak ditemukan.'
            ], 404);
        }

        // Validate class assignment
        if ($student->class !== $teacher->assigned_class) {
            return response()->json([
                'status' => 'error',
                'message' => "Siswa {$student->user->name} bukan dari kelas {$teacher->assigned_class}. Siswa ini dari kelas {$student->class}."
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
                'message' => "{$student->user->name} sudah absen masuk hari ini pukul {$checkInTime}.",
                'type' => 'masuk',
            ]);
        }

        $currentTime = $now->format('H:i');

        // Block if before 06:00
        if ($currentTime < self::CHECK_IN_START) {
            return response()->json([
                'status' => 'error',
                'message' => "Belum waktunya absen masuk. Absen masuk dimulai pukul " . self::CHECK_IN_START,
                'type' => 'masuk',
            ], 403);
        }

        // Block if after 07:15
        if ($currentTime > self::CHECK_IN_LIMIT) {
            return response()->json([
                'status' => 'error',
                'message' => "Sesi absen masuk sudah berakhir (pukul " . self::CHECK_IN_LIMIT . ").",
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
            'message' => "{$student->user->name} ({$student->class}) tercatat MASUK — {$statusLabel} pukul {$now->format('H:i')}.",
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
                'message' => "{$student->user->name} belum absen masuk hari ini. Tidak bisa absen keluar.",
                'type' => 'keluar',
            ]);
        }

        // Already checked out today?
        if ($attendance->check_out_at) {
            $checkOutTime = Carbon::parse($attendance->check_out_at)->format('H:i');
            return response()->json([
                'status' => 'warning',
                'message' => "{$student->user->name} sudah absen keluar hari ini pukul {$checkOutTime}.",
                'type' => 'keluar',
            ]);
        }

        $currentTime = $now->format('H:i');

        // Block if before 11:30
        if ($currentTime < self::CHECK_OUT_TIME) {
            return response()->json([
                'status' => 'error',
                'message' => "Belum waktunya pulang. Absen pulang dimulai pukul " . self::CHECK_OUT_TIME,
                'type' => 'keluar',
            ], 403);
        }

        // Block if after 18:00
        if ($currentTime > self::CHECK_OUT_END) {
            return response()->json([
                'status' => 'error',
                'message' => "Sesi absen pulang sudah berakhir.",
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
            'message' => "{$student->user->name} ({$student->class}) tercatat KELUAR — {$statusLabel} pukul {$now->format('H:i')}.",
            'type' => 'keluar',
        ]);
    }

    /**
     * Show the Digital ID Card for STUDENT.
     * Contains Static QR with their NIS.
     */
    public function showStudentQr()
    {
        $user = Auth::user();
        if (!$user->student) {
             abort(404, 'Data siswa tidak lengkap.');
        }

        return view('attendance.my-qr', [
            'student' => $user->student
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
