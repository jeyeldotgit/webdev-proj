@extends('layouts.app')

@section('title', 'Browse Courses - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-neutral-900 mb-8">Browse Courses</h1>

    @if($courses->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 mb-2">{{ $course->title }}</h3>
                        <p class="text-neutral-600 text-sm mb-4 line-clamp-2">{{ $course->description ?? 'No description' }}</p>
                        <div class="flex items-center justify-between text-sm text-neutral-600 mb-4">
                            <span>By {{ $course->instructor->name }}</span>
                            <span>{{ $course->lessons_count }} lessons</span>
                        </div>
                        <a href="{{ route('courses.show', $course) }}" class="block w-full text-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                            View Course
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <p class="text-neutral-600">No published courses available yet.</p>
        </div>
    @endif
</div>
@endsection

