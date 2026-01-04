<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SproutLMS - Lightweight Learning Management System</title>
    <meta name="description" content="SproutLMS is a lightweight, open-source Learning Management System for individual creators and small teams. Start small, grow big.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-sm border-b border-neutral-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-primary-600">🌱 SproutLMS</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-neutral-600 hover:text-neutral-900 transition-colors">Features</a>
                    <a href="#about" class="text-neutral-600 hover:text-neutral-900 transition-colors">About</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">Get Started</a>
                    @endauth
                </div>
                <button class="md:hidden text-neutral-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-neutral-900 mb-6">
                    Start Small, <span class="text-primary-600">Grow Big</span>
                </h1>
                <p class="text-xl md:text-2xl text-neutral-600 mb-8 max-w-3xl mx-auto">
                    A lightweight, no-fuss Learning Management System for individual creators and small teams. 
                    Host your courses, track progress, and focus on what matters—teaching.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-700 transition-colors shadow-lg hover:shadow-xl">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-700 transition-colors shadow-lg hover:shadow-xl">
                            Get Started Free
                        </a>
                    @endauth
                    <a href="#features" class="border-2 border-neutral-300 text-neutral-700 px-8 py-4 rounded-lg text-lg font-semibold hover:border-neutral-400 transition-colors">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-neutral-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-neutral-900 mb-4">Everything You Need, Nothing You Don't</h2>
                <p class="text-xl text-neutral-600 max-w-2xl mx-auto">
                    Built for simplicity. Designed for growth.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Text Lessons</h3>
                    <p class="text-neutral-600">
                        Host your content the way you want. Support for text-based courses with a clean, distraction-free interface.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Student Enrollment</h3>
                    <p class="text-neutral-600">
                        Easy student management. Track who's enrolled in your courses with a simple, intuitive dashboard.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-3">Progress Tracking</h3>
                    <p class="text-neutral-600">
                        Monitor student completion with basic progress tracking. See who's engaged and who needs a nudge.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-neutral-900 mb-4">Fundamental Learning</h2>
                <p class="text-xl text-neutral-600">
                    Our Philosophy
                </p>
            </div>
            
            <div class="prose prose-lg max-w-none">
                <div class="bg-primary-50 border-l-4 border-primary-600 p-6 rounded-r-lg mb-8">
                    <p class="text-neutral-700 text-lg leading-relaxed mb-4">
                        SproutLMS is built on a simple principle: <strong>Start small, grow big.</strong> 
                        We believe that learning management shouldn't be complicated. You shouldn't need 
                        a team of developers or a massive budget to share your knowledge with the world.
                    </p>
                    <p class="text-neutral-700 text-lg leading-relaxed">
                        Whether you're teaching coding, cooking, or anything in between, SproutLMS gives 
                        you the essentials—hosting your lessons, managing students, and tracking progress—without 
                        the bloat. Focus on creating great content, and let us handle the rest.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mt-12">
                    <div class="p-6 bg-neutral-50 rounded-lg">
                        <h3 class="text-xl font-semibold text-neutral-900 mb-3">Open Source</h3>
                        <p class="text-neutral-600">
                            Built with Laravel and completely open source. Customize it to fit your needs, 
                            or use it as-is. It's yours.
                        </p>
                    </div>
                    <div class="p-6 bg-neutral-50 rounded-lg">
                        <h3 class="text-xl font-semibold text-neutral-900 mb-3">Lightweight</h3>
                        <p class="text-neutral-600">
                            No unnecessary features. No confusing interfaces. Just what you need to get 
                            started and grow your teaching business.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="get-started" class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-600 to-primary-700">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Start Teaching?
            </h2>
            <p class="text-xl text-primary-50 mb-8 max-w-2xl mx-auto">
                Join creators who are sharing their knowledge with SproutLMS. 
                Get started in minutes, not days.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-primary-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-neutral-100 transition-colors shadow-lg">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-primary-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-neutral-100 transition-colors shadow-lg">
                        Get Started Free
                    </a>
                @endauth
                <a href="{{ route('courses.browse') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white/10 transition-colors">
                    Browse Courses
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-neutral-900 text-neutral-400 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <span class="text-2xl font-bold text-white mb-4 block">🌱 SproutLMS</span>
                    <p class="text-sm">
                        A lightweight, open-source Learning Management System for creators and small teams.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Sign Up</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        @auth
                            @if(auth()->user()->role === 'student')
                                <li><a href="{{ route('courses.browse') }}" class="hover:text-white transition-colors">Browse Courses</a></li>
                                <li><a href="{{ route('enrollments.index') }}" class="hover:text-white transition-colors">My Courses</a></li>
                            @else
                                <li><a href="{{ route('courses.index') }}" class="hover:text-white transition-colors">My Courses</a></li>
                                <li><a href="{{ route('courses.create') }}" class="hover:text-white transition-colors">Create Course</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('courses.browse') }}" class="hover:text-white transition-colors">Browse Courses</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Account</h4>
                    <ul class="space-y-2 text-sm">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="hover:text-white transition-colors text-left">Logout</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Sign Up</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="border-t border-neutral-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} SproutLMS. Open source and free to use.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth Scroll Script -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>

