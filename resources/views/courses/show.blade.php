@extends('layouts.app')

@section('title', $course->title . ' - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ $course->title }}</h1>
                <p class="text-neutral-600 mb-4">{{ $course->description ?? 'No description provided.' }}</p>
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
                <a href="{{ route('courses.edit', $course) }}" class="border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors">
                    Edit Course
                </a>
            @endif
        </div>
    </div>

    @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-neutral-900">Lessons</h2>
            <a href="{{ route('lessons.create', $course) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                + Add Lesson
            </a>
        </div>
    @else
        <h2 class="text-2xl font-bold text-neutral-900 mb-6">Lessons</h2>
    @endif

    @if($course->lessons->count() > 0)
        <div class="space-y-3">
            @foreach($course->lessons as $lesson)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600 font-semibold">
                                {{ $loop->iteration }}
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-neutral-900">{{ $lesson->title }}</h3>
                                <p class="text-sm text-neutral-600">
                                    {{ $lesson->type === 'video' ? 'Video Lesson' : 'Text Lesson' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            @if(auth()->check() && auth()->user()->role === 'student' && $isEnrolled)
                                <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                    View
                                </a>
                            @elseif(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                                <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                    View
                                </a>
                                <a href="{{ route('lessons.edit', [$course, $lesson]) }}" class="border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors text-sm">
                                    Edit
                                </a>
                            @else
                                <span class="text-neutral-400 text-sm">Enroll to view</span>
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
                <a href="{{ route('lessons.create', $course) }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                    Add First Lesson
                </a>
            @endif
        </div>
    @endif

    @if(auth()->check() && auth()->user()->role === 'student' && !$isEnrolled)
        <div class="mt-6 bg-primary-50 border-l-4 border-primary-600 p-4 rounded-lg">
            <form method="POST" action="{{ route('enrollments.store', $course) }}">
                @csrf
                <p class="text-neutral-700 mb-4">Enroll in this course to access all lessons.</p>
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                    Enroll Now
                </button>
            </form>
        </div>
    @endif

    <!-- Assignments Section -->
    @if(auth()->check() && ($isEnrolled || auth()->user()->role === 'instructor' || auth()->user()->role === 'admin'))
        <div class="mt-12">
            @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-neutral-900">Assignments</h2>
                    <a href="{{ route('assignments.create', $course) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
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
                        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-neutral-900">{{ $assignment->title }}</h3>
                                    <p class="text-sm text-neutral-600 mt-1">
                                        Max Score: {{ $assignment->max_score }} points
                                        @if($assignment->due_date)
                                            • Due: {{ $assignment->due_date->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('assignments.show', [$course, $assignment]) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('assignments.index', $course) }}" class="text-primary-600 hover:text-primary-700 text-sm">
                        View all assignments →
                    </a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
                    <p class="text-neutral-600">No assignments yet.</p>
                    @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                        <a href="{{ route('assignments.create', $course) }}" class="inline-block mt-4 bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                            Create First Assignment
                        </a>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

