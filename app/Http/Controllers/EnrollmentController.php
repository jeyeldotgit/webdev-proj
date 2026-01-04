<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::where('student_id', Auth::id())
            ->with(['course' => function($query) {
                $query->withCount('lessons');
            }])
            ->latest()
            ->get();

        return view('enrollments.index', compact('enrollments'));
    }

    public function store(Request $request, Course $course)
    {
        // Check if already enrolled
        $existing = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'student_id' => Auth::id(),
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Successfully enrolled in the course!');
    }

    public function destroy(Course $course)
    {
        $enrollment = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->route('enrollments.index')
                ->with('success', 'Successfully unenrolled from the course.');
        }

        return redirect()->route('enrollments.index')
            ->with('error', 'Enrollment not found.');
    }
}
