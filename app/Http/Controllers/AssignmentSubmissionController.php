<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentSubmissionController extends Controller
{
    public function store(Request $request, Course $course, Assignment $assignment)
    {
        // Only students can submit assignments
        if (Auth::user()->role !== 'student') {
            abort(403);
        }

        // Check if student is enrolled
        $isEnrolled = $course->enrollments()
            ->where('student_id', Auth::id())
            ->exists();
        
        if (!$isEnrolled) {
            return redirect()->back()
                ->with('error', 'You must be enrolled in this course to submit assignments.');
        }

        // Check if already submitted
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'You have already submitted this assignment.');
        }

        $validated = $request->validate([
            'submission_text' => 'required|string|min:10',
            'attachment' => 'nullable|string|max:255',
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => Auth::id(),
            'submission_text' => $validated['submission_text'],
            'attachment' => $validated['attachment'] ?? null,
            'submitted_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Assignment submitted successfully!');
    }

    public function update(Request $request, Course $course, Assignment $assignment, AssignmentSubmission $submission)
    {
        // Only instructors can grade submissions
        if (Auth::user()->role !== 'instructor' || Auth::id() !== $course->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:' . $assignment->max_score,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'] ?? null,
            'graded_by' => Auth::id(),
            'graded_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Assignment graded successfully!');
    }
}
