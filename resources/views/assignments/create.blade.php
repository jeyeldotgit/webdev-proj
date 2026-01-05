@extends('layouts.app')

@section('title', 'Create Assignment - SproutLMS')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('assignments.index', $course) }}" class="text-primary-600 hover:text-primary-700 mb-4 inline-block">
            ← Back to Assignments
        </a>
        <h1 class="text-3xl font-bold text-neutral-900">Create Assignment</h1>
        <p class="text-neutral-600 mt-1">Course: {{ $course->title }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
        <form method="POST" action="{{ route('assignments.store', $course) }}">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Assignment Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="6"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="max_score" class="block text-sm font-medium text-neutral-700 mb-2">Maximum Score *</label>
                <input type="number" name="max_score" id="max_score" value="{{ old('max_score', 100) }}" required min="1" max="1000"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('max_score')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="due_date" class="block text-sm font-medium text-neutral-700 mb-2">Due Date (optional)</label>
                <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                <p class="text-sm text-neutral-500 mt-1">Leave empty if there's no deadline</p>
                @error('due_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                    Create Assignment
                </button>
                <a href="{{ route('assignments.index', $course) }}" class="border border-neutral-300 text-neutral-700 px-6 py-3 rounded-lg hover:bg-neutral-50 transition-colors font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

