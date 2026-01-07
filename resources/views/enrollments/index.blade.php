@extends('layouts.app')

@section('title', 'My Enrollments - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-neutral-900 mb-8">My Enrolled Courses</h1>

    @if($enrollments->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $totalLessons = $course->lessons_count;
                    $completedLessons = \App\Models\Progress::where('student_id', auth()->id())
                        ->whereIn('lesson_id', $course->lessons->pluck('id'))
                        ->where('is_completed', true)
                        ->count();
                    $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-neutral-900 mb-2">{{ $course->title }}</h3>
                        <p class="text-neutral-600 text-sm mb-4 line-clamp-2">{{ $course->description ?? 'No description' }}</p>
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-neutral-600 mb-2">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-xs text-neutral-500 mt-2">{{ $completedLessons }} of {{ $totalLessons }} lessons completed</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('courses.show', $course) }}" class="flex-1 text-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                Continue
                            </a>
                            <form method="POST" action="{{ route('enrollments.destroy', $course) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors text-sm"
                                    onclick="return confirm('Are you sure you want to unenroll from this course?')">
                                    Unenroll
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No enrolled courses yet</h3>
            <p class="text-neutral-600 mb-6">Browse available courses and start learning</p>
            <a href="{{ route('courses.browse') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                Browse Courses
            </a>
        </div>
    @endif
</div>
@endsection

