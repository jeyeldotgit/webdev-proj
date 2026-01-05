@extends('layouts.app')

@section('title', 'Student Details - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.students.index') }}" class="text-primary-600 hover:text-primary-700 mb-4 inline-block">
            ← Back to Students
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">{{ $student->name }}</h1>
                <p class="text-neutral-600 mt-1">{{ $student->email }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.students.edit', $student) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                    Edit Student
                </a>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-neutral-200">
            <p class="text-sm text-neutral-600 mb-1">Enrolled Courses</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $enrollments->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-neutral-200">
            <p class="text-sm text-neutral-600 mb-1">Completed Lessons</p>
            <p class="text-3xl font-bold text-neutral-900">{{ $completedLessons }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-neutral-200">
            <p class="text-sm text-neutral-600 mb-1">Member Since</p>
            <p class="text-lg font-semibold text-neutral-900">{{ $student->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-neutral-900">Enroll Student in Course</h2>
        </div>
        <form method="POST" action="{{ route('admin.students.enroll', $student) }}" class="flex gap-3">
            @csrf
            <select name="course_id" required class="flex-1 px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                <option value="">Select a course...</option>
                @foreach(\App\Models\Course::where('is_published', true)->orderBy('title')->get() as $course)
                    @if(!$enrollments->pluck('course_id')->contains($course->id))
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endif
                @endforeach
            </select>
            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                Enroll
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <h2 class="text-xl font-bold text-neutral-900 mb-4">Enrolled Courses</h2>
        
        @if($enrollments->count() > 0)
            <div class="space-y-4">
                @foreach($enrollments as $enrollment)
                    @php
                        $course = $enrollment->course;
                        $totalLessons = $course->lessons_count;
                        $completedLessons = \App\Models\Progress::where('student_id', $student->id)
                            ->whereIn('lesson_id', $course->lessons->pluck('id'))
                            ->where('is_completed', true)
                            ->count();
                        $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                    @endphp
                    <div class="border border-neutral-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-neutral-900">{{ $course->title }}</h3>
                                <p class="text-sm text-neutral-600 mt-1">{{ $course->description ?? 'No description' }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.students.unenroll', ['student' => $student, 'course' => $course]) }}" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm" onclick="return confirm('Are you sure you want to unenroll this student?')">
                                    Unenroll
                                </button>
                            </form>
                        </div>
                        <div class="mb-2">
                            <div class="flex justify-between text-sm text-neutral-600 mb-1">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1">{{ $completedLessons }} of {{ $totalLessons }} lessons completed</p>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <a href="{{ route('courses.show', $course) }}" class="text-sm text-primary-600 hover:text-primary-700">
                                View Course →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-neutral-600 text-center py-8">This student is not enrolled in any courses yet.</p>
        @endif
    </div>
</div>
@endsection

