<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        // Only instructor can access this
        if (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        
        $lessons = $course->lessons()->orderBy('order')->get();
        return view('lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        return view('lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:text,video',
            'video_url' => 'nullable|url|required_if:type,video',
            'order' => 'nullable|integer|min:0',
        ]);

        $maxOrder = $course->lessons()->max('order') ?? 0;
        $validated['order'] = $validated['order'] ?? ($maxOrder + 1);

        $course->lessons()->create($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Lesson created successfully!');
    }

    public function show(Course $course, Lesson $lesson)
    {
        // Check if user is enrolled (for students) or is instructor
        if (Auth::user()->role === 'student') {
            $isEnrolled = $course->enrollments()
                ->where('student_id', Auth::id())
                ->exists();
            
            if (!$isEnrolled) {
                return redirect()->route('courses.show', $course)
                    ->with('error', 'You must enroll in this course to view lessons.');
            }
        } else {
            // Instructor must own the course
            if (Auth::id() !== $course->instructor_id) {
                abort(403);
            }
        }

        $lesson->load('course');
        $allLessons = $course->lessons()->orderBy('order')->get();
        $currentIndex = $allLessons->search(function($item) use ($lesson) {
            return $item->id === $lesson->id;
        });

        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        // Mark as completed if student
        if (Auth::user()->role === 'student') {
            \App\Models\Progress::updateOrCreate(
                [
                    'student_id' => Auth::id(),
                    'lesson_id' => $lesson->id,
                ],
                [
                    'is_completed' => true,
                    'completed_at' => now(),
                ]
            );
        }

        return view('lessons.show', compact('course', 'lesson', 'previousLesson', 'nextLesson'));
    }

    public function edit(Course $course, Lesson $lesson)
    {
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        return view('lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:text,video',
            'video_url' => 'nullable|url|required_if:type,video',
            'order' => 'nullable|integer|min:0',
        ]);

        $lesson->update($validated);

        return redirect()->route('lessons.show', [$course, $lesson])->with('success', 'Lesson updated successfully!');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        if (Auth::id() !== $course->instructor_id) {
            abort(403);
        }
        $lesson->delete();

        return redirect()->route('courses.show', $course)->with('success', 'Lesson deleted successfully!');
    }
}
