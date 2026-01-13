<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SproutLMS') }} - {{ $title ?? 'Authentication' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-neutral-900 antialiased bg-gradient-to-br from-neutral-50 via-primary-50/30 to-neutral-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <div class="mb-6">
                <a href="/" class="flex items-center space-x-2 group">
                    <span class="text-3xl">🌱</span>
                    <span class="text-2xl font-bold text-primary-600 group-hover:text-primary-700 transition-colors">SproutLMS</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white shadow-xl rounded-2xl overflow-hidden border border-neutral-200">
                <div class="px-8 py-10">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-6 text-center text-sm text-neutral-600">
                <a href="/" class="hover:text-primary-600 transition-colors">← Back to home</a>
            </div>
        </div>
    </body>
</html>
