<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();

        $recentStudents = User::where('role', 'student')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalInstructors',
            'totalCourses',
            'totalEnrollments',
            'recentStudents'
        ));
    }

    /**
     * Display list of students
     */
    public function students()
    {
        $students = User::where('role', 'student')
            ->withCount('enrollments')
            ->latest()
            ->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show student details
     */
    public function showStudent(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['course' => function($query) {
                $query->withCount('lessons');
            }])
            ->latest()
            ->get();

        $completedLessons = \App\Models\Progress::where('student_id', $student->id)
            ->where('is_completed', true)
            ->count();

        return view('admin.students.show', compact('student', 'enrollments', 'completedLessons'));
    }

    /**
     * Show form to create a new student
     */
    public function createStudent()
    {
        return view('admin.students.create');
    }

    /**
     * Store a new student
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $student = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student created successfully!');
    }

    /**
     * Show form to edit a student
     */
    public function editStudent(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update a student
     */
    public function updateStudent(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $student->update($updateData);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Delete a student
     */
    public function destroyStudent(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Enroll a student in a course
     */
    public function enrollStudent(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        // Check if already enrolled
        $existing = Enrollment::where('student_id', $student->id)
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Student is already enrolled in this course.');
        }

        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $validated['course_id'],
            'enrolled_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Student enrolled successfully!');
    }

    /**
     * Unenroll a student from a course
     */
    public function unenrollStudent(User $student, Course $course)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->back()
                ->with('success', 'Student unenrolled successfully!');
        }

        return redirect()->back()
            ->with('error', 'Enrollment not found.');
    }
}
