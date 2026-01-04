@extends('layouts.app')

@section('title', 'Sign Up - SproutLMS')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Create Account</h1>
        <p class="text-neutral-600 mb-8">Join SproutLMS and start learning or teaching</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-neutral-700 mb-2">I want to</label>
                <select name="role" id="role" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                    <option value="">Select...</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Learn (Student)</option>
                    <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>Teach (Instructor)</option>
                </select>
                @error('role')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-neutral-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
            </div>

            <button type="submit" class="w-full bg-primary-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                Create Account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-neutral-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold">Sign in</a>
        </p>
    </div>
</div>
@endsection

