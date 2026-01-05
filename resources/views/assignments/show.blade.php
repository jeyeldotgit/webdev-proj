@extends('layouts.app')

@section('title', $assignment->title . ' - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('assignments.index', $course) }}" class="text-primary-600 hover:text-primary-700 mb-4 inline-block">
            ← Back to Assignments
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">{{ $assignment->title }}</h1>
                <p class="text-neutral-600 mt-1">Course: {{ $course->title }}</p>
            </div>
            @if(auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                <a href="{{ route('assignments.edit', [$course, $assignment]) }}" class="border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors">
                    Edit Assignment
                </a>
            @endif
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- Assignment Details -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-bold text-neutral-900 mb-4">Assignment Details</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-neutral-700 mb-2">Description</h3>
                        <p class="text-neutral-900 whitespace-pre-wrap">{{ $assignment->description ?? 'No description provided.' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-neutral-200">
                        <div>
                            <p class="text-sm text-neutral-600">Maximum Score</p>
                            <p class="text-lg font-semibold text-neutral-900">{{ $assignment->max_score }} points</p>
                        </div>
                        @if($assignment->due_date)
                            <div>
                                <p class="text-sm text-neutral-600">Due Date</p>
                                <p class="text-lg font-semibold text-neutral-900">{{ $assignment->due_date->format('M d, Y g:i A') }}</p>
                                @if($assignment->due_date->isPast())
                                    <span class="text-xs text-red-600">Past due</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'student')
                <!-- Student Submission Section -->
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-xl font-bold text-neutral-900 mb-4">Your Submission</h2>
                    
                    @if($studentSubmission)
                        <div class="mb-4 p-4 bg-neutral-50 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-sm text-neutral-600">Submitted on</p>
                                    <p class="font-semibold text-neutral-900">{{ $studentSubmission->submitted_at->format('M d, Y g:i A') }}</p>
                                </div>
                                @if($studentSubmission->grade !== null)
                                    <div class="text-right">
                                        <p class="text-sm text-neutral-600">Grade</p>
                                        <p class="text-2xl font-bold text-primary-600">{{ $studentSubmission->grade }} / {{ $assignment->max_score }}</p>
                                    </div>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Pending Review</span>
                                @endif
                            </div>
                            <div class="mt-4">
                                <p class="text-sm font-medium text-neutral-700 mb-2">Your Submission:</p>
                                <p class="text-neutral-900 whitespace-pre-wrap">{{ $studentSubmission->submission_text }}</p>
                            </div>
                            @if($studentSubmission->feedback)
                                <div class="mt-4 p-4 bg-primary-50 rounded-lg border-l-4 border-primary-600">
                                    <p class="text-sm font-medium text-primary-900 mb-2">Instructor Feedback:</p>
                                    <p class="text-primary-800 whitespace-pre-wrap">{{ $studentSubmission->feedback }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <form method="POST" action="{{ route('assignment-submissions.store', [$course, $assignment]) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="submission_text" class="block text-sm font-medium text-neutral-700 mb-2">Your Answer *</label>
                                <textarea name="submission_text" id="submission_text" rows="10" required
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600"
                                    placeholder="Type your assignment submission here...">{{ old('submission_text') }}</textarea>
                                @error('submission_text')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-neutral-500 mt-1">Minimum 10 characters required</p>
                            </div>
                            <div class="mb-4">
                                <label for="attachment" class="block text-sm font-medium text-neutral-700 mb-2">Attachment URL (optional)</label>
                                <input type="url" name="attachment" id="attachment" value="{{ old('attachment') }}"
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600"
                                    placeholder="https://example.com/file.pdf">
                                @error('attachment')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                                Submit Assignment
                            </button>
                        </form>
                    @endif
                </div>
            @elseif(auth()->user()->role === 'instructor' || auth()->user()->role === 'admin')
                <!-- Instructor: Submissions List -->
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-xl font-bold text-neutral-900 mb-4">Submissions ({{ $submissions->count() }})</h2>
                    
                    @if($submissions->count() > 0)
                        <div class="space-y-4">
                            @foreach($submissions as $submission)
                                <div class="border border-neutral-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="font-semibold text-neutral-900">{{ $submission->student->name }}</p>
                                            <p class="text-sm text-neutral-600">{{ $submission->student->email }}</p>
                                            <p class="text-xs text-neutral-500 mt-1">Submitted: {{ $submission->submitted_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                        @if($submission->grade !== null)
                                            <div class="text-right">
                                                <p class="text-sm text-neutral-600">Grade</p>
                                                <p class="text-xl font-bold text-primary-600">{{ $submission->grade }} / {{ $assignment->max_score }}</p>
                                            </div>
                                        @else
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Not Graded</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-neutral-700 mb-2">Submission:</p>
                                        <p class="text-neutral-900 whitespace-pre-wrap text-sm">{{ $submission->submission_text }}</p>
                                    </div>

                                    @if(auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                                        <form method="POST" action="{{ route('assignment-submissions.update', [$course, $assignment, $submission]) }}" class="mt-4 pt-4 border-t border-neutral-200">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label for="grade_{{ $submission->id }}" class="block text-sm font-medium text-neutral-700 mb-2">Grade (0-{{ $assignment->max_score }})</label>
                                                    <input type="number" name="grade" id="grade_{{ $submission->id }}" 
                                                        value="{{ old('grade', $submission->grade) }}" 
                                                        min="0" max="{{ $assignment->max_score }}" step="0.01"
                                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="feedback_{{ $submission->id }}" class="block text-sm font-medium text-neutral-700 mb-2">Feedback</label>
                                                <textarea name="feedback" id="feedback_{{ $submission->id }}" rows="3"
                                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">{{ old('feedback', $submission->feedback) }}</textarea>
                                            </div>
                                            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                                {{ $submission->grade !== null ? 'Update Grade' : 'Grade Assignment' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-neutral-600 text-center py-8">No submissions yet.</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                <h3 class="font-semibold text-neutral-900 mb-4">Assignment Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-neutral-600">Course</p>
                        <p class="font-medium text-neutral-900">{{ $course->title }}</p>
                    </div>
                    <div>
                        <p class="text-neutral-600">Max Score</p>
                        <p class="font-medium text-neutral-900">{{ $assignment->max_score }} points</p>
                    </div>
                    @if($assignment->due_date)
                        <div>
                            <p class="text-neutral-600">Due Date</p>
                            <p class="font-medium text-neutral-900">{{ $assignment->due_date->format('M d, Y') }}</p>
                            <p class="text-xs text-neutral-500">{{ $assignment->due_date->format('g:i A') }}</p>
                        </div>
                    @endif
                    @if(auth()->user()->role === 'instructor' || auth()->user()->role === 'admin')
                        <div>
                            <p class="text-neutral-600">Total Submissions</p>
                            <p class="font-medium text-neutral-900">{{ $submissions->count() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

