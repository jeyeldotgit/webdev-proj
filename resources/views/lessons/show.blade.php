@extends('layouts.app')

@section('title', $lesson->title . ' - SproutLMS')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <nav class="text-sm text-neutral-600 mb-4">
            <a href="{{ route('courses.show', $course) }}" class="hover:text-primary-600">{{ $course->title }}</a>
            <span class="mx-2">/</span>
            <span class="text-neutral-900">{{ $lesson->title }}</span>
        </nav>
        
        @if(auth()->check() && auth()->user()->role === 'instructor' && auth()->id() === $course->instructor_id)
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-3xl font-bold text-neutral-900">{{ $lesson->title }}</h1>
                <a href="{{ route('lessons.edit', [$course, $lesson]) }}" class="border border-neutral-300 text-neutral-700 px-4 py-2 rounded-lg hover:bg-neutral-50 transition-colors">
                    Edit Lesson
                </a>
            </div>
        @else
            <h1 class="text-3xl font-bold text-neutral-900 mb-4">{{ $lesson->title }}</h1>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8 mb-6">
        @if($lesson->type === 'video' && $lesson->video_url)
            <div class="mb-6">
                <div class="aspect-video bg-neutral-100 rounded-lg overflow-hidden">
                    @if(str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be'))
                        @php
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $lesson->video_url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        @if($videoId)
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <a href="{{ $lesson->video_url }}" target="_blank" class="text-primary-600 hover:text-primary-700">Open Video</a>
                            </div>
                        @endif
                    @elseif(str_contains($lesson->video_url, 'vimeo.com'))
                        @php
                            $videoId = '';
                            if (preg_match('/vimeo\.com\/(\d+)/', $lesson->video_url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        @if($videoId)
                            <iframe class="w-full h-full" src="https://player.vimeo.com/video/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <a href="{{ $lesson->video_url }}" target="_blank" class="text-primary-600 hover:text-primary-700">Open Video</a>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <a href="{{ $lesson->video_url }}" target="_blank" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700">
                                Watch Video
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($lesson->content)
            <div class="prose max-w-none">
                {!! nl2br(e($lesson->content)) !!}
            </div>
        @endif
    </div>

    <div class="flex justify-between items-center">
        @if($previousLesson)
            <a href="{{ route('lessons.show', [$course, $previousLesson]) }}" class="flex items-center gap-2 text-primary-600 hover:text-primary-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Previous: {{ $previousLesson->title }}
            </a>
        @else
            <span></span>
        @endif

        <a href="{{ route('courses.show', $course) }}" class="text-neutral-600 hover:text-neutral-900">
            Back to Course
        </a>

        @if($nextLesson)
            <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="flex items-center gap-2 text-primary-600 hover:text-primary-700">
                Next: {{ $nextLesson->title }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        @else
            <span></span>
        @endif
    </div>
</div>
@endsection

