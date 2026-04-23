<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isGuru()) {
            return $this->teacherDashboard();
        } elseif ($user->isStudent()) {
            return $this->studentDashboard();
        }

        return view('dashboard'); // Fallback
    }

    private function adminDashboard()
    {
        $totalStudents = Student::count();
        $totalTeachers = User::where('role', 'guru')->count();
        
        $today = Carbon::today();
        $presentToday = Attendance::whereDate('check_in_at', $today)->where('check_in_status', 'present')->count();
        $lateToday = Attendance::whereDate('check_in_at', $today)->where('check_in_status', 'late')->count();
        $checkedOutToday = Attendance::whereDate('check_out_at', $today)->whereNotNull('check_out_at')->count();
        $absentToday = $totalStudents - ($presentToday + $lateToday);

        return view('dashboard.admin', compact('totalStudents', 'totalTeachers', 'presentToday', 'lateToday', 'absentToday', 'checkedOutToday'));
    }

    private function teacherDashboard()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $assignedClass = $teacher ? $teacher->assigned_class : null;

        $today = Carbon::today();

        // Filter by teacher's assigned class
        $classStudentIds = [];
        $totalClassStudents = 0;
        if ($assignedClass) {
            $classStudents = Student::where('class', $assignedClass)->get();
            $classStudentIds = $classStudents->pluck('id')->toArray();
            $totalClassStudents = $classStudents->count();
        }

        $presentToday = Attendance::whereDate('check_in_at', $today)
            ->where('check_in_status', 'present')
            ->whereIn('student_id', $classStudentIds)
            ->count();
        
        $lateToday = Attendance::whereDate('check_in_at', $today)
            ->where('check_in_status', 'late')
            ->whereIn('student_id', $classStudentIds)
            ->count();

        $checkedOutToday = Attendance::whereDate('check_out_at', $today)
            ->whereNotNull('check_out_at')
            ->whereIn('student_id', $classStudentIds)
            ->count();

        $absentToday = $totalClassStudents - ($presentToday + $lateToday);

        $recentActivity = Attendance::with('student.user')
            ->whereDate('check_in_at', $today)
            ->whereIn('student_id', $classStudentIds)
            ->latest('check_in_at')
            ->take(10)
            ->get();

        return view('dashboard.teacher', compact(
            'presentToday', 'lateToday', 'checkedOutToday', 'absentToday',
            'recentActivity', 'assignedClass', 'totalClassStudents'
        ));
    }

    private function studentDashboard()
    {
        $user = Auth::user();
        $student = $user->student;
        
        $myAttendance = collect();
        $attendanceStats = ['present' => 0, 'late' => 0, 'alpha' => 0];

        if ($student) {
            $myAttendance = Attendance::where('student_id', $student->id)->latest('check_in_at')->take(5)->get();
            $attendanceStats['present'] = Attendance::where('student_id', $student->id)->where('check_in_status', 'present')->count();
            $attendanceStats['late'] = Attendance::where('student_id', $student->id)->where('check_in_status', 'late')->count();
        }

        return view('dashboard.student', compact('myAttendance', 'attendanceStats'));
    }
}
