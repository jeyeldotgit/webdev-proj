<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        // Admin can see all courses, instructors see only their own
        if (Auth::user()->role === 'admin') {
            $courses = Course::with(['instructor'])
                ->withCount(['lessons', 'enrollments'])
                ->latest()
                ->get();
        } else {
            $courses = Course::where('instructor_id', Auth::id())
                ->withCount(['lessons', 'enrollments'])
                ->latest()
                ->get();
        }

        return view('courses.index', compact('courses'));
    }

    public function browse()
    {
        $courses = Course::where('is_published', true)
            ->with(['instructor', 'lessons'])
            ->withCount('lessons')
            ->latest()
            ->get();

        return view('courses.browse', compact('courses'));
    }

    public function create()
    {
        // Get instructors list for admin to assign
        $instructors = null;
        if (Auth::user()->role === 'admin') {
            $instructors = \App\Models\User::where('role', 'instructor')
                ->orderBy('name')
                ->get();
        }
        
        return view('courses.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:255',
            'instructor_id' => 'nullable|exists:users,id', // Admin can assign instructor
        ]);

        // Admin can assign instructor, otherwise use current user
        $instructorId = Auth::user()->role === 'admin' && $validated['instructor_id']
            ? $validated['instructor_id']
            : Auth::id();

        $course = Course::create([
            'instructor_id' => $instructorId,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'thumbnail' => $validated['thumbnail'] ?? null,
            'is_published' => false,
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Course created successfully!');
    }

    public function show(Course $course)
    {
        $course->load(['instructor', 'lessons' => function($query) {
            $query->orderBy('order');
        }, 'assignments']);

        $isEnrolled = false;
        if (Auth::check() && Auth::user()->role === 'student') {
            $isEnrolled = $course->enrollments()
                ->where('student_id', Auth::id())
                ->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }

    public function edit(Course $course)
    {
        // Admin can edit any course, instructors can only edit their own
        if (Auth::user()->role !== 'admin' && Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // Admin can update any course, instructors can only update their own
        if (Auth::user()->role !== 'admin' && Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'instructor_id' => 'nullable|exists:users,id', // Admin can reassign instructor
        ]);

        // Only admin can change instructor
        if (Auth::user()->role === 'admin' && isset($validated['instructor_id'])) {
            $course->instructor_id = $validated['instructor_id'];
            unset($validated['instructor_id']);
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        // Admin can delete any course, instructors can only delete their own
        if (Auth::user()->role !== 'admin' && Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }
}
