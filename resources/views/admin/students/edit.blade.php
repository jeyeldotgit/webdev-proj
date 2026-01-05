@extends('layouts.app')

@section('title', 'Edit Student - SproutLMS')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.students.show', $student) }}" class="text-primary-600 hover:text-primary-700 mb-4 inline-block">
            ← Back to Student Details
        </a>
        <h1 class="text-3xl font-bold text-neutral-900">Edit Student</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
        <form method="POST" action="{{ route('admin.students.update', $student) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $student->name) }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-neutral-700 mb-2">New Password</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                <p class="text-sm text-neutral-500 mt-1">Leave blank to keep current password</p>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-primary-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                    Update Student
                </button>
                <a href="{{ route('admin.students.show', $student) }}" class="flex-1 text-center border border-neutral-300 text-neutral-700 px-4 py-3 rounded-lg font-semibold hover:bg-neutral-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

