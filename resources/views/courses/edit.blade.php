@extends('layouts.app')

@section('title', 'Edit Course - SproutLMS')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-neutral-900 mb-8">Edit Course</h1>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
        <form method="POST" action="{{ route('courses.update', $course) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Course Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if(auth()->user()->role === 'admin')
            <div class="mb-6">
                <label for="instructor_id" class="block text-sm font-medium text-neutral-700 mb-2">Assign Instructor</label>
                <select name="instructor_id" id="instructor_id"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                    @foreach(\App\Models\User::where('role', 'instructor')->orderBy('name')->get() as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->email }})
                        </option>
                    @endforeach
                </select>
                @error('instructor_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div class="mb-6">
                <label for="thumbnail" class="block text-sm font-medium text-neutral-700 mb-2">Thumbnail URL (optional)</label>
                <input type="url" name="thumbnail" id="thumbnail" value="{{ old('thumbnail', $course->thumbnail) }}"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600"
                    placeholder="https://example.com/image.jpg">
                @error('thumbnail')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $course->is_published) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary-600 border-neutral-300 rounded">
                    <span class="ml-2 text-sm text-neutral-700">Publish this course</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                    Update Course
                </button>
                <a href="{{ route('courses.show', $course) }}" class="border border-neutral-300 text-neutral-700 px-6 py-3 rounded-lg hover:bg-neutral-50 transition-colors font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

