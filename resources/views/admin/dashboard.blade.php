@extends('layouts.app')

@section('title', 'Admin Dashboard - SproutLMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Admin Dashboard</h1>
        <p class="text-neutral-600">Manage courses, students, and system overview</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                    <p class="text-sm text-neutral-600 mb-1">Total Instructors</p>
                    <p class="text-3xl font-bold text-neutral-900">{{ $totalInstructors }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-600 mb-1">Total Courses</p>
                    <p class="text-3xl font-bold text-neutral-900">{{ $totalCourses }}</p>
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
                    <p class="text-sm text-neutral-600 mb-1">Total Enrollments</p>
                    <p class="text-3xl font-bold text-neutral-900">{{ $totalEnrollments }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-neutral-900">Quick Actions</h2>
            </div>
            <div class="space-y-3">
                <a href="{{ route('admin.students.index') }}" class="block w-full bg-primary-600 text-white px-4 py-3 rounded-lg hover:bg-primary-700 transition-colors text-center">
                    Manage Students
                </a>
                <a href="{{ route('courses.create') }}" class="block w-full border border-primary-600 text-primary-600 px-4 py-3 rounded-lg hover:bg-primary-50 transition-colors text-center">
                    Create Course
                </a>
                <a href="{{ route('courses.index') }}" class="block w-full border border-neutral-300 text-neutral-700 px-4 py-3 rounded-lg hover:bg-neutral-50 transition-colors text-center">
                    View All Courses
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-neutral-900">Recent Students</h2>
                <a href="{{ route('admin.students.index') }}" class="text-sm text-primary-600 hover:text-primary-700">View All</a>
            </div>
            @if($recentStudents->count() > 0)
                <div class="space-y-3">
                    @foreach($recentStudents as $student)
                        <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-neutral-900">{{ $student->name }}</p>
                                <p class="text-sm text-neutral-600">{{ $student->email }}</p>
                            </div>
                            <a href="{{ route('admin.students.show', $student) }}" class="text-primary-600 hover:text-primary-700 text-sm">
                                View →
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-600 text-center py-4">No students yet</p>
            @endif
        </div>
    </div>
</div>
@endsection

