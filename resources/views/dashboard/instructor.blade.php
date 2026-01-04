@extends('layouts.app')

@section('title', 'Instructor Dashboard - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Instructor Dashboard</h1>
        <p class="text-neutral-600">Manage your courses and track student progress</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-600 mb-1">Total Courses</p>
                    <p class="text-3xl font-bold text-neutral-900">{{ $courses->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-600 mb-1">Total Students</p>
                    <p class="text-3xl font-bold text-neutral-900">{{ $totalStudents }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-600 mb-1">Published Courses</p>
                    <p class="text-3xl font-bold text-neutral-900">{{ $courses->where('is_published', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-neutral-900">My Courses</h2>
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
            <svg class="w-16 h-16 text-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No courses yet</h3>
            <p class="text-neutral-600 mb-6">Create your first course to get started</p>
            <a href="{{ route('courses.create') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                Create Your First Course
            </a>
        </div>
    @endif
</div>
@endsection

