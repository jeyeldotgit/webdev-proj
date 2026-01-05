@extends('layouts.app')

@section('title', 'Assignments - ' . $course->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-neutral-900">Assignments</h1>
            <p class="text-neutral-600 mt-1">{{ $course->title }}</p>
        </div>
        <a href="{{ route('courses.show', $course) }}" class="text-primary-600 hover:text-primary-700">
            ← Back to Course
        </a>
    </div>

    @if(auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
        <div class="mb-6">
            <a href="{{ route('assignments.create', $course) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                + Create Assignment
            </a>
        </div>
    @endif

    @if($assignments->count() > 0)
        <div class="space-y-4">
            @foreach($assignments as $assignment)
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-neutral-900 mb-2">{{ $assignment->title }}</h3>
                            <p class="text-neutral-600 mb-3">{{ $assignment->description ?? 'No description' }}</p>
                            <div class="flex items-center gap-4 text-sm text-neutral-600">
                                <span>Max Score: {{ $assignment->max_score }}</span>
                                @if($assignment->due_date)
                                    <span>•</span>
                                    <span>Due: {{ $assignment->due_date->format('M d, Y g:i A') }}</span>
                                @endif
                                @if(auth()->user()->role === 'instructor')
                                    <span>•</span>
                                    <span>{{ $assignment->submissions_count }} submissions</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2 ml-4">
                            <a href="{{ route('assignments.show', [$course, $assignment]) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                View
                            </a>
                            @if(auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                                <a href="{{ route('assignments.edit', [$course, $assignment]) }}" class="border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors text-sm">
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No assignments yet</h3>
            <p class="text-neutral-600 mb-6">
                @if(auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                    Create your first assignment for this course
                @else
                    This course doesn't have any assignments yet.
                @endif
            </p>
            @if(auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
                <a href="{{ route('assignments.create', $course) }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                    Create Assignment
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

