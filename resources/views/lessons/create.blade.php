@extends('layouts.app')

@section('title', 'Create Lesson - SproutLMS')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-neutral-900 mb-2">Add Lesson to: {{ $course->title }}</h1>
    <p class="text-neutral-600 mb-8">Create a new lesson for your course</p>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
        <form method="POST" action="{{ route('lessons.store', $course) }}">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Lesson Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-neutral-700 mb-2">Lesson Type *</label>
                <select name="type" id="type" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600"
                    onchange="toggleVideoField()">
                    <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Text Lesson</option>
                    <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video Lesson</option>
                </select>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6" id="video-url-field" style="display: none;">
                <label for="video_url" class="block text-sm font-medium text-neutral-700 mb-2">Video URL *</label>
                <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600"
                    placeholder="https://youtube.com/watch?v=... or https://vimeo.com/...">
                @error('video_url')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6" id="content-field">
                <label for="content" class="block text-sm font-medium text-neutral-700 mb-2">Content</label>
                <textarea name="content" id="content" rows="10"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="order" class="block text-sm font-medium text-neutral-700 mb-2">Order (optional)</label>
                <input type="number" name="order" id="order" value="{{ old('order') }}" min="0"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                <p class="text-sm text-neutral-500 mt-1">Leave empty to add at the end</p>
                @error('order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                    Create Lesson
                </button>
                <a href="{{ route('courses.show', $course) }}" class="border border-neutral-300 text-neutral-700 px-6 py-3 rounded-lg hover:bg-neutral-50 transition-colors font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleVideoField() {
    const type = document.getElementById('type').value;
    const videoField = document.getElementById('video-url-field');
    const contentField = document.getElementById('content-field');
    
    if (type === 'video') {
        videoField.style.display = 'block';
        contentField.style.display = 'none';
        document.getElementById('video_url').required = true;
    } else {
        videoField.style.display = 'none';
        contentField.style.display = 'block';
        document.getElementById('video_url').required = false;
    }
}

// Initialize on page load
toggleVideoField();
</script>
@endsection

