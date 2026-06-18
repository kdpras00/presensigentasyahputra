<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Notifications\StudentAttendanceNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AttendanceController extends Controller
{
    // Hardcoded constants removed in favor of database-driven schedules
    
    /**
     * Get the schedule for the current day.
     */
    private function getCurrentSchedule()
    {
        $daysMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $dayName = Carbon::now()->format('l');
        $indonesianDay = $daysMap[$dayName] ?? $dayName;

        return \App\Models\Schedule::where('day', $indonesianDay)->first();
    }

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
                'assignedClass'  => null,
                'mode'           => null,
                'error'          => 'Anda belum memiliki kelas yang ditugaskan. Hubungi Admin.',
                'sessionStatus'  => 'closed',
                'sessionMessage' => 'Kelas Belum Ditugaskan',
                'timeRules'      => null,
            ]);
        }

        // Default mode based on time
        $now = Carbon::now();
        $currentTime = $now->format('H:i');
        
        $schedule = $this->getCurrentSchedule();

        if (!$schedule || $schedule->is_off) {
            return view('attendance.scan', [
                'assignedClass'  => $teacher->assigned_class,
                'mode'           => null,
                'error'          => 'Hari ini adalah hari libur atau tidak ada jadwal presensi.',
                'sessionStatus'  => 'closed',
                'sessionMessage' => 'Hari Libur',
                'timeRules'      => null,
            ]);
        }

        $checkInStart = $schedule->start_time ? Carbon::parse($schedule->start_time)->format('H:i') : '07:00';
        $checkInLimit = $schedule->late_time ? Carbon::parse($schedule->late_time)->format('H:i') : '07:15';
        $checkOutTime = $schedule->checkout_start_time ? Carbon::parse($schedule->checkout_start_time)->format('H:i') : '11:30';
        $checkOutEnd = $schedule->end_time ? Carbon::parse($schedule->end_time)->format('H:i') : '17:00';

        $aftCheckInStart = $schedule->afternoon_start_time ? Carbon::parse($schedule->afternoon_start_time)->format('H:i') : null;
        $aftCheckInLimit = $schedule->afternoon_late_time ? Carbon::parse($schedule->afternoon_late_time)->format('H:i') : null;
        $aftCheckOutTime = $schedule->afternoon_checkout_start_time ? Carbon::parse($schedule->afternoon_checkout_start_time)->format('H:i') : null;
        $aftCheckOutEnd = $schedule->afternoon_end_time ? Carbon::parse($schedule->afternoon_end_time)->format('H:i') : null;

        // Logic: 
        // 1. If current time is before the morning check-out window, default to 'masuk'
        // 2. If current time is on or after check-out starts, default to 'keluar'
        // 3. If there is an afternoon shift and time is around afternoon check-in, default to 'masuk'
        $mode = 'masuk';
        $isAfternoonWindow = $aftCheckInStart && $currentTime >= $checkOutTime;

        if ($isAfternoonWindow) {
            if ($currentTime >= $aftCheckOutTime) {
                $mode = 'keluar';
            } else {
                $mode = 'masuk';
            }
        } else {
            if ($currentTime >= $checkOutTime) {
                $mode = 'keluar';
            }
        }

        // Determine current session status for UI
        $sessionStatus = 'closed';
        $sessionMessage = 'Sesi Presensi Belum Dimulai';

        if ($isAfternoonWindow) {
            if ($currentTime >= $aftCheckInStart && $currentTime < $aftCheckInLimit) {
                $sessionStatus = 'masuk_tepat';
                $sessionMessage = 'Sesi Masuk Siang: Tepat Waktu';
            } elseif ($currentTime >= $aftCheckInLimit && $currentTime < $aftCheckOutTime) {
                $sessionStatus = 'masuk_telat';
                $sessionMessage = 'Sesi Masuk Siang: Terlambat';
            } elseif ($currentTime >= $aftCheckOutTime && $currentTime <= $aftCheckOutEnd) {
                $sessionStatus = 'keluar';
                $sessionMessage = 'Sesi Pulang Siang: Dibuka';
            } elseif ($currentTime > $aftCheckOutEnd) {
                $sessionStatus = 'closed';
                $sessionMessage = 'Sesi Presensi Hari Ini Berakhir';
            }
        } else {
            if ($currentTime >= $checkInStart && $currentTime < $checkInLimit) {
                $sessionStatus = 'masuk_tepat';
                $sessionMessage = 'Sesi Masuk: Tepat Waktu';
            } elseif ($currentTime >= $checkInLimit && $currentTime < $checkOutTime) {
                $sessionStatus = 'masuk_telat';
                $sessionMessage = 'Sesi Masuk: Terlambat';
            } elseif ($currentTime >= $checkOutTime && $currentTime <= $checkOutEnd) {
                $sessionStatus = 'keluar';
                $sessionMessage = 'Sesi Pulang: Dibuka';
            } elseif ($currentTime > $checkOutEnd) {
                $sessionStatus = 'closed';
                $sessionMessage = 'Sesi Presensi Hari Ini Berakhir';
            }
        }

        return view('attendance.scan', [
            'assignedClass' => $teacher->assigned_class,
            'mode' => $mode,
            'error' => null,
            'sessionStatus' => $sessionStatus,
            'sessionMessage' => $sessionMessage,
            'timeRules' => [
                'masuk_start' => $checkInStart,
                'masuk_end' => $checkInLimit,
                'keluar_start' => $checkOutTime,
                'keluar_end' => $checkOutEnd,
                'aft_masuk_start' => $aftCheckInStart,
                'aft_masuk_end' => $aftCheckInLimit,
                'aft_keluar_start' => $aftCheckOutTime,
                'aft_keluar_end' => $aftCheckOutEnd,
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
        $student = Student::with('user')->whereHas('user', function($query) use ($username) {
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

        // Get today's attendance record (FIXED: proper where grouping)
        $attendance = Attendance::where('student_id', $student->id)
            ->where(function ($query) use ($today) {
                $query->whereDate('check_in_at', $today)
                      ->orWhereDate('scanned_at', $today);
            })
            ->first();

        $schedule = $this->getCurrentSchedule();

        if (!$schedule || $schedule->is_off) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini tidak ada jadwal presensi.'
            ], 403);
        }

        // Use mode from frontend (teacher's choice) — wrapped in transaction for data integrity
        try {
            return DB::transaction(function () use ($mode, $student, $teacher, $attendance, $now, $schedule) {
                if ($mode === 'masuk') {
                    return $this->handleCheckIn($student, $teacher, $attendance, $now, $schedule);
                } else {
                    return $this->handleCheckOut($student, $teacher, $attendance, $now, $schedule);
                }
            });
        } catch (\Exception $e) {
            Log::error('Attendance scan failed', [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'mode' => $mode,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Handle Check-In logic.
     */
    private function handleCheckIn(Student $student, Teacher $teacher, ?Attendance $attendance, Carbon $now, Schedule $schedule)
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

        $checkInStart = $schedule->start_time ? Carbon::parse($schedule->start_time)->format('H:i') : '07:00';
        $checkInLimit = $schedule->late_time ? Carbon::parse($schedule->late_time)->format('H:i') : '07:15';
        $checkOutStart = $schedule->checkout_start_time ? Carbon::parse($schedule->checkout_start_time)->format('H:i') : '11:30';

        $aftCheckInStart = $schedule->afternoon_start_time ? Carbon::parse($schedule->afternoon_start_time)->format('H:i') : null;
        $aftCheckInLimit = $schedule->afternoon_late_time ? Carbon::parse($schedule->afternoon_late_time)->format('H:i') : null;
        $aftCheckOutStart = $schedule->afternoon_checkout_start_time ? Carbon::parse($schedule->afternoon_checkout_start_time)->format('H:i') : null;

        $isAfternoon = false;
        if ($aftCheckInStart && $currentTime >= $checkOutStart) {
            $isAfternoon = true;
        }

        $activeStart = $isAfternoon ? $aftCheckInStart : $checkInStart;
        $activeLimit = $isAfternoon ? $aftCheckInLimit : $checkInLimit;
        $activeClose = $isAfternoon ? $aftCheckOutStart : $checkOutStart;

        // Block if before start
        if ($currentTime < $activeStart) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, jadwal absensi masuk baru akan dimulai pada pukul {$activeStart}.",
                'type' => 'masuk',
            ], 403);
        }

        // Block if after checkout session starts (Hard limit for check-in)
        if ($currentTime >= $activeClose) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, sesi absensi masuk sudah ditutup karena sudah memasuki waktu absensi pulang ({$activeClose}).",
                'type' => 'masuk',
            ], 403);
        }

        // Determine check-in status
        $checkInStatus = 'present';
        $statusLabel = 'HADIR (Tepat Waktu)';
        // Logic for late can be more complex, but for now we follow the limit
        if ($currentTime > $activeLimit) {
            $checkInStatus = 'late';
            $statusLabel = 'TERLAMBAT';
        }

        // Create attendance record
        $newAttendance = Attendance::create([
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
            'message' => $checkInStatus === 'present' ? "{$student->user->name} ({$student->class}) hadir tepat waktu." : "{$student->user->name} ({$student->class}) datang terlambat.",
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
    private function handleCheckOut(Student $student, Teacher $teacher, ?Attendance $attendance, Carbon $now, Schedule $schedule)
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

        $checkOutStart = $schedule->checkout_start_time ? Carbon::parse($schedule->checkout_start_time)->format('H:i') : '11:30';
        $checkOutEnd = $schedule->end_time ? Carbon::parse($schedule->end_time)->format('H:i') : '17:00';

        $aftCheckOutStart = $schedule->afternoon_checkout_start_time ? Carbon::parse($schedule->afternoon_checkout_start_time)->format('H:i') : null;
        $aftCheckOutEnd = $schedule->afternoon_end_time ? Carbon::parse($schedule->afternoon_end_time)->format('H:i') : null;

        // Determine if they checked in during the afternoon shift
        $checkInTimeStr = Carbon::parse($attendance->check_in_at)->format('H:i');
        $isAfternoon = false;
        
        // If they checked in after the morning checkout started, they are in the afternoon shift
        if ($aftCheckOutStart && $checkInTimeStr >= $checkOutStart) {
            $isAfternoon = true;
        }

        $activeCheckOutStart = $isAfternoon ? $aftCheckOutStart : $checkOutStart;
        $activeCheckOutEnd = $isAfternoon ? $aftCheckOutEnd : $checkOutEnd;

        // Block if before start
        if ($currentTime < $activeCheckOutStart) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, jadwal absensi kepulangan baru akan dimulai pada pukul {$activeCheckOutStart}.",
                'type' => 'keluar',
            ], 403);
        }

        // Block if after end
        if ($currentTime > $activeCheckOutEnd) {
            return response()->json([
                'status' => 'error',
                'message' => "Mohon maaf, batas waktu absensi kepulangan untuk hari ini telah berakhir pada pukul {$activeCheckOutEnd}.",
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
            'message' => "{$student->user->name} ({$student->class}) sudah pulang.",
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

        if (!$user->student) {
            abort(404, 'Data profil siswa tidak ditemukan.');
        }

        $attendances = Attendance::where('student_id', $user->student->id)
            ->latest('check_in_at')
            ->paginate(10);

        return view('attendance.history', compact('attendances'));
    }
}
