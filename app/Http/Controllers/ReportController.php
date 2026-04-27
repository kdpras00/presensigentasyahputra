<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display the attendance report.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $search = $request->input('search');
        
        // Base query
        $query = Attendance::with(['student.user', 'teacher.user'])
                    ->whereDate('check_in_at', $date);

        // Filter by search
        if ($search) {
            $query->whereHas('student.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // If guru, filter by assigned class only
        $assignedClass = null;
        if ($user->isGuru() && $user->teacher) {
            $assignedClass = $user->teacher->assigned_class;
            if ($assignedClass) {
                $classStudentIds = Student::where('class', $assignedClass)->pluck('id');
                $query->whereIn('student_id', $classStudentIds);
            }
        }

        $attendances = $query->latest('check_in_at')->get();
        
        // Calculate totals
        if ($assignedClass) {
            $totalStudents = Student::where('class', $assignedClass)->count();
        } else {
            $totalStudents = Student::count();
        }

        $presentCount = $attendances->where('check_in_status', 'present')->count();
        $lateCount = $attendances->where('check_in_status', 'late')->count();
        $checkedOutCount = $attendances->whereNotNull('check_out_at')->count();
        
        return view('reports.index', compact(
            'attendances', 'date', 'totalStudents', 'presentCount', 
            'lateCount', 'checkedOutCount', 'assignedClass'
        ));
    }
}
