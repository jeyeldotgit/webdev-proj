@extends('layouts.app')

@section('title', 'Login - SproutLMS')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Welcome Back</h1>
        <p class="text-neutral-600 mb-8">Sign in to your SproutLMS account</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600">
                @error('email')
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

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-primary-600 border-neutral-300 rounded">
                <label for="remember" class="ml-2 text-sm text-neutral-600">Remember me</label>
            </div>

            <button type="submit" class="w-full bg-primary-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                Sign In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-neutral-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold">Sign up</a>
        </p>
    </div>
</div>
@endsection

