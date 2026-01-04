@extends('layouts.app')

@section('title', 'My Courses - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-neutral-900">My Courses</h1>
        <a href="{{ route('courses.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
            + Create Course
        </a>
    </div>

    @if($courses->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-xl font-semibold text-neutral-900">{{ $course->title }}</h3>
                            @if($course->is_published)
                                <span class="px-2 py-1 text-xs font-semibold bg-primary-100 text-primary-700 rounded">Published</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-neutral-100 text-neutral-700 rounded">Draft</span>
                            @endif
                        </div>
                        <p class="text-neutral-600 text-sm mb-4 line-clamp-2">{{ $course->description ?? 'No description' }}</p>
                        <div class="flex items-center justify-between text-sm text-neutral-600 mb-4">
                            <span>{{ $course->lessons_count }} lessons</span>
                            <span>{{ $course->enrollments_count }} students</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('courses.show', $course) }}" class="flex-1 text-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                View
                            </a>
                            <a href="{{ route('courses.edit', $course) }}" class="flex-1 text-center border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors text-sm">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No courses yet</h3>
            <p class="text-neutral-600 mb-6">Create your first course to get started</p>
            <a href="{{ route('courses.create') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                Create Your First Course
            </a>
        </div>
    @endif
</div>
@endsection

