@extends('layouts.app')

@section('title', $course->title . ' - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Course Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden mb-8">
        @if($course->thumbnail)
            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-64 object-cover">
        @else
            <div class="w-full h-64 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                <svg class="w-24 h-24 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
        <div class="p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-neutral-900 mb-3">{{ $course->title }}</h1>
                <p class="text-neutral-600 mb-4 leading-relaxed">{{ $course->description ?? 'No description provided.' }}</p>
                <div class="flex items-center gap-4 text-sm text-neutral-600">
                    <span>By {{ $course->instructor->name }}</span>
                    <span>•</span>
                    <span>{{ $course->lessons->count() }} lessons</span>
                    @if($course->enrollments_count > 0)
                        <span>•</span>
                        <span>{{ $course->enrollments_count }} students</span>
                    @endif
                </div>
            </div>
            @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                <a href="{{ route('courses.edit', $course) }}" class="inline-flex items-center border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors text-sm font-medium">
                    Edit Course
                </a>
            @endif
        </div>
    </div>

    <!-- Lessons Section -->
    <div class="mb-8">
        @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-neutral-900">Lessons</h2>
                <a href="{{ route('lessons.create', $course) }}" class="inline-flex items-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                    + Add Lesson
                </a>
            </div>
        @else
            <h2 class="text-2xl font-bold text-neutral-900 mb-6">Lessons</h2>
        @endif

        @if($course->lessons->count() > 0)
            <div class="space-y-3">
                @foreach($course->lessons as $lesson)
                    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600 font-semibold flex-shrink-0">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-neutral-900 mb-1">{{ $lesson->title }}</h3>
                                    <p class="text-sm text-neutral-600">
                                        {{ $lesson->type === 'video' ? 'Video Lesson' : 'Text Lesson' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                @if(auth()->check() && auth()->user()->role === 'student' && $isEnrolled)
                                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="inline-flex items-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                                        View
                                    </a>
                                @elseif(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="inline-flex items-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                                        View
                                    </a>
                                    <a href="{{ route('lessons.edit', [$course, $lesson]) }}" class="inline-flex items-center border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors text-sm font-medium">
                                        Edit
                                    </a>
                                @else
                                    <span class="inline-flex items-center text-neutral-400 text-sm px-4 py-2">Enroll to view</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
                <p class="text-neutral-600 mb-4">No lessons yet.</p>
                @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                    <a href="{{ route('lessons.create', $course) }}" class="inline-flex items-center bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                        Add First Lesson
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Enrollment Section -->
    @if(auth()->check() && auth()->user()->role === 'student' && !$isEnrolled)
        <div class="mb-8 bg-primary-50 border-l-4 border-primary-600 p-6 rounded-lg">
            <form method="POST" action="{{ route('enrollments.store', $course) }}">
                @csrf
                <p class="text-neutral-700 mb-4">Enroll in this course to access all lessons and assignments.</p>
                <button type="submit" class="inline-flex items-center bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                    Enroll Now
                </button>
            </form>
        </div>
    @endif

    <!-- Assignments Section -->
    @if(auth()->check() && ($isEnrolled || auth()->user()->role === 'instructor' || auth()->user()->role === 'admin'))
        <div class="mb-8">
            @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-neutral-900">Assignments</h2>
                    <a href="{{ route('assignments.create', $course) }}" class="inline-flex items-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                        + Create Assignment
                    </a>
                </div>
            @else
                <h2 class="text-2xl font-bold text-neutral-900 mb-6">Assignments</h2>
            @endif

            @php
                $assignments = $course->assignments()->withCount('submissions')->latest()->get();
            @endphp

            @if($assignments->count() > 0)
                <div class="space-y-3">
                    @foreach($assignments as $assignment)
                        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-neutral-900 mb-2">{{ $assignment->title }}</h3>
                                    <p class="text-sm text-neutral-600">
                                        Max Score: {{ $assignment->max_score }} points
                                        @if($assignment->due_date)
                                            • Due: {{ $assignment->due_date->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('assignments.show', [$course, $assignment]) }}" class="inline-flex items-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <a href="{{ route('assignments.index', $course) }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm font-medium">
                        View all assignments →
                    </a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
                    <p class="text-neutral-600 mb-4">No assignments yet.</p>
                    @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                        <a href="{{ route('assignments.create', $course) }}" class="inline-flex items-center bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                            Create First Assignment
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
