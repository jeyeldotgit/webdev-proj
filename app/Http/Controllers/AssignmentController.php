<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Course $course)
    {
        // Admin can view any course, instructors can only view their own
        if (Auth::user()->role !== 'admin' && (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id)) {
            abort(403);
        }

        $assignments = $course->assignments()
            ->withCount('submissions')
            ->latest()
            ->get();

        return view('assignments.index', compact('course', 'assignments'));
    }

    public function create(Course $course)
    {
        // Only instructors can create assignments
        if (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        return view('assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        // Only instructors can create assignments
        if (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1|max:1000',
            'due_date' => 'nullable|date|after:now',
        ]);

        $assignment = $course->assignments()->create($validated);

        return redirect()->route('assignments.show', [$course, $assignment])
            ->with('success', 'Assignment created successfully!');
    }

    public function show(Course $course, Assignment $assignment)
    {
        // Admin can view any assignment, instructors can view their own course assignments
        // Students can view if enrolled
        if (Auth::user()->role === 'admin') {
            // Admin can view any
        } elseif (Auth::user()->role === 'instructor') {
            if (Auth::id() !== $course->instructor_id) {
                abort(403);
            }
        } elseif (Auth::user()->role === 'student') {
            // Check if student is enrolled
            $isEnrolled = $course->enrollments()
                ->where('student_id', Auth::id())
                ->exists();
            
            if (!$isEnrolled) {
                abort(403, 'You must be enrolled in this course to view assignments.');
            }
        } else {
            abort(403);
        }

        $assignment->load('course');
        
        // For instructors/admins: load submissions
        $submissions = null;
        if (Auth::user()->role === 'instructor' || Auth::user()->role === 'admin') {
            $submissions = $assignment->submissions()
                ->with('student')
                ->latest()
                ->get();
        }

        // For students: load their submission
        $studentSubmission = null;
        if (Auth::user()->role === 'student') {
            $studentSubmission = $assignment->submissions()
                ->where('student_id', Auth::id())
                ->first();
        }

        return view('assignments.show', compact('course', 'assignment', 'submissions', 'studentSubmission'));
    }

    public function edit(Course $course, Assignment $assignment)
    {
        // Only instructors can edit assignments
        if (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        return view('assignments.edit', compact('course', 'assignment'));
    }

    public function update(Request $request, Course $course, Assignment $assignment)
    {
        // Only instructors can update assignments
        if (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_score' => 'required|integer|min:1|max:1000',
            'due_date' => 'nullable|date',
        ]);

        $assignment->update($validated);

        return redirect()->route('assignments.show', [$course, $assignment])
            ->with('success', 'Assignment updated successfully!');
    }

    public function destroy(Course $course, Assignment $assignment)
    {
        // Only instructors can delete assignments
        if (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $assignment->delete();

        return redirect()->route('assignments.index', $course)
            ->with('success', 'Assignment deleted successfully!');
    }
}
