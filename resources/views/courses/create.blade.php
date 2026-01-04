@extends('layouts.app')

@section('title', 'Create Course - SproutLMS')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-neutral-900 mb-8">Create New Course</h1>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
        <form method="POST" action="{{ route('courses.store') }}">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Course Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="thumbnail" class="block text-sm font-medium text-neutral-700 mb-2">Thumbnail URL (optional)</label>
                <input type="url" name="thumbnail" id="thumbnail" value="{{ old('thumbnail') }}"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600"
                    placeholder="https://example.com/image.jpg">
                @error('thumbnail')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                    Create Course
                </button>
                <a href="{{ route('courses.index') }}" class="border border-neutral-300 text-neutral-700 px-6 py-3 rounded-lg hover:bg-neutral-50 transition-colors font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

