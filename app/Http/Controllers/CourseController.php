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
        $courses = Course::where('instructor_id', Auth::id())
            ->withCount(['lessons', 'enrollments'])
            ->latest()
            ->get();

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
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:255',
        ]);

        $course = Course::create([
            'instructor_id' => Auth::id(),
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
        }]);

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
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $course->update($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }
}
