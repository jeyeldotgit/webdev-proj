<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SproutLMS')</title>
    <meta name="description" content="@yield('description', 'SproutLMS - Lightweight Learning Management System')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm border-b border-neutral-200 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-primary-600">🌱 SproutLMS</a>
                </div>
                
                @auth
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('admin.*') ? 'text-primary-600 font-semibold' : '' }}">Dashboard</a>
                    
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.students.index') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors {{ request()->routeIs('admin.*') ? 'text-primary-600 font-semibold' : '' }}">Manage Students</a>
                        <a href="{{ route('courses.index') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors {{ request()->routeIs('courses.*') ? 'text-primary-600 font-semibold' : '' }}">All Courses</a>
                    @elseif(auth()->user()->role === 'instructor')
                        <a href="{{ route('courses.index') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors {{ request()->routeIs('courses.*') ? 'text-primary-600 font-semibold' : '' }}">My Courses</a>
                    @else
                        <a href="{{ route('courses.browse') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors {{ request()->routeIs('courses.browse') ? 'text-primary-600 font-semibold' : '' }}">Browse Courses</a>
                        <a href="{{ route('enrollments.index') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors {{ request()->routeIs('enrollments.*') ? 'text-primary-600 font-semibold' : '' }}">My Courses</a>
                    @endif
                    
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-neutral-600 hover:text-neutral-900 transition-colors">
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-neutral-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 rounded-lg">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">Sign Up</a>
                </div>
                @endauth
                
                <button class="md:hidden text-neutral-600" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 min-h-screen">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-primary-50 border-l-4 border-primary-600 text-primary-700 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-red-50 border-l-4 border-red-600 text-red-700 p-4 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-red-50 border-l-4 border-red-600 text-red-700 p-4 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-neutral-900 text-neutral-400 py-8 px-4 sm:px-6 lg:px-8 mt-12">
        <div class="max-w-7xl mx-auto text-center text-sm">
            <p>&copy; {{ date('Y') }} SproutLMS. Open source and free to use.</p>
        </div>
    </footer>
</body>
</html>

