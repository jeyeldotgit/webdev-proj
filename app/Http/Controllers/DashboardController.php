<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'instructor') {
            $courses = Course::where('instructor_id', $user->id)
                ->withCount('lessons')
                ->withCount('enrollments')
                ->latest()
                ->get();

            $totalStudents = Enrollment::whereIn('course_id', $courses->pluck('id'))->distinct('student_id')->count();

            return view('dashboard.instructor', compact('courses', 'totalStudents'));
        } else {
            $enrollments = Enrollment::where('student_id', $user->id)
                ->with(['course' => function($query) {
                    $query->withCount('lessons');
                }])
                ->latest()
                ->get();

            $completedLessons = \App\Models\Progress::where('student_id', $user->id)
                ->where('is_completed', true)
                ->count();

            return view('dashboard.student', compact('enrollments', 'completedLessons'));
        }
    }
}
