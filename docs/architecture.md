# Architecture Guide – SproutLMS

This document defines the architecture and development approach for SproutLMS. It's designed to guide developers, especially those new to Laravel, through building features using a **Frontend First** mental model.

---

## Table of Contents

1. [Philosophy: Frontend First](#philosophy-frontend-first)
2. [Understanding Laravel (Simple Terms)](#understanding-laravel-simple-terms)
3. [Project Structure](#project-structure)
4. [Current Features](#current-features)
5. [Development Workflow](#development-workflow)
6. [Building a Feature (Step-by-Step)](#building-a-feature-step-by-step)
7. [Key Concepts](#key-concepts)
8. [Laravel 11+ Important Notes](#laravel-11-important-notes)
9. [Best Practices](#best-practices)
10. [Common Patterns](#common-patterns)
11. [Route Ordering Rules](#route-ordering-rules)

---

## Philosophy: Frontend First

### What Does "Frontend First" Mean?

**Frontend First** means we start with what the user sees and experiences, then build the backend to support it.

### Why This Approach?

1. **User-Centric**: We design for users, not databases
2. **Faster Iteration**: See results immediately in the browser
3. **Clear Requirements**: The UI shows exactly what data/features are needed
4. **Easier to Learn**: Visual feedback helps understand what's happening

### The Mental Model

```
User sees something → We build the view → We create the route → We add the controller → We design the database
```

Instead of:

```
We design the database → We create models → We build controllers → We create routes → Finally, we build the view
```

### Example Flow

**Traditional (Backend First):**

1. Create database table
2. Create model
3. Create controller
4. Create route
5. Create view
6. Test in browser

**Frontend First:**

1. Create view (mockup with fake data)
2. See it in browser
3. Create route to show the view
4. Add controller to handle logic
5. Create model for data
6. Create database migration

---

## Understanding Laravel (Simple Terms)

### What is Laravel?

Laravel is a PHP framework that helps you build web applications. Think of it as a toolbox with pre-built tools for common tasks.

### Key Laravel Concepts (Simplified)

#### 1. **Routes** (`routes/web.php`)

-   **What it is**: A map that says "when someone visits this URL, do this"
-   **Example**: `Route::get('/courses', ...)` means "when someone goes to `/courses`, show them something"
-   **Important**: Route order matters! Specific routes must come before parameterized routes.

#### 2. **Views** (`resources/views/`)

-   **What it is**: The HTML templates (what users see)
-   **File type**: `.blade.php` files (Blade is Laravel's templating engine)
-   **Example**: `landing.blade.php` is the landing page HTML
-   **Layout**: Use `@extends('layouts.app')` for consistent page structure

#### 3. **Controllers** (`app/Http/Controllers/`)

-   **What it is**: PHP classes that handle the logic
-   **Purpose**: Get data, process it, send it to the view
-   **Example**: `CourseController` handles everything related to courses
-   **Note**: In Laravel 11+, middleware is applied in routes, not controller constructors

#### 4. **Models** (`app/Models/`)

-   **What it is**: PHP classes that represent database tables
-   **Purpose**: Easy way to work with database data
-   **Example**: `Course` model represents the `courses` table
-   **Relationships**: Models can have relationships (hasMany, belongsTo, etc.)

#### 5. **Migrations** (`database/migrations/`)

-   **What it is**: Files that create/modify database tables
-   **Purpose**: Version control for your database structure
-   **Example**: `create_courses_table.php` creates the courses table

#### 6. **Middleware** (`app/Http/Middleware/`)

-   **What it is**: Code that runs before/after requests
-   **Purpose**: Authentication, authorization, validation
-   **Example**: `EnsureUserIsInstructor` checks if user is an instructor

### How They Work Together

```
User visits URL
    ↓
Route receives request
    ↓
Middleware checks permissions
    ↓
Controller handles logic
    ↓
Model gets data from database
    ↓
Controller sends data to View
    ↓
View renders HTML
    ↓
User sees the page
```

---

## Project Structure

### Important Directories

```
lms-app/
├── app/                          # Backend PHP code
│   ├── Http/
│   │   ├── Controllers/         # Controllers (business logic)
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── ...             # Feature controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                   # Models (database representation)
│   ├── Policies/                 # Authorization policies
│   └── Providers/                # Service providers
│
├── resources/
│   ├── views/                    # Frontend templates (Blade files)
│   │   ├── auth/                 # Login/register pages
│   │   ├── courses/              # Course pages
│   │   ├── dashboard/            # Dashboard pages
│   │   ├── enrollments/          # Enrollment pages
│   │   ├── lessons/              # Lesson pages
│   │   ├── layouts/              # Page layouts
│   │   └── landing.blade.php     # Landing page
│   ├── css/
│   │   └── app.css              # Tailwind CSS (our color scheme)
│   └── js/
│       └── app.js               # JavaScript
│
├── routes/
│   └── web.php                  # Web routes (URLs → Controllers)
│
├── database/
│   └── migrations/              # Database structure files
│
└── public/                       # Public files (accessible via URL)
    └── index.php                # Entry point (don't touch)
```

### File Naming Conventions

-   **Controllers**: `PascalCase` + `Controller` suffix
    -   Example: `CourseController.php`, `LessonController.php`
-   **Models**: `PascalCase`, singular
    -   Example: `Course.php`, `Lesson.php`, `User.php`
-   **Views**: `kebab-case` (lowercase with hyphens)
    -   Example: `course-list.blade.php`, `lesson-show.blade.php`
-   **Migrations**: `snake_case` with timestamp
    -   Example: `2024_01_15_120000_create_courses_table.php`
-   **Middleware**: `PascalCase` descriptive name
    -   Example: `EnsureUserIsInstructor.php`, `EnsureUserIsStudent.php`

---

## Current Features

### Implemented Features

#### Authentication & Authorization

-   ✅ User registration (student/instructor roles)
-   ✅ Login/logout functionality
-   ✅ Role-based access control (middleware)
-   ✅ Session-based authentication

#### Instructor Features

-   ✅ Dashboard with course/student statistics
-   ✅ Course CRUD (Create, Read, Update, Delete)
-   ✅ Course publishing/unpublishing
-   ✅ Lesson management (text and video lessons)
-   ✅ View enrolled students

#### Student Features

-   ✅ Dashboard with progress tracking
-   ✅ Browse published courses
-   ✅ Enroll in courses
-   ✅ View lessons (text and video)
-   ✅ Automatic progress tracking
-   ✅ Course progress percentage

#### Public Features

-   ✅ Landing page with functional buttons
-   ✅ Browse published courses (no login required)
-   ✅ View course details (no login required)
-   ✅ Registration and login access
-   ✅ Conditional UI based on authentication status

#### Database Structure

-   ✅ Users table with roles
-   ✅ Courses table
-   ✅ Lessons table (text/video support)
-   ✅ Enrollments table
-   ✅ Progress tracking table

### Route Access Levels

#### Public Routes (No Authentication)

-   `/` - Landing page
-   `/login` - Login page
-   `/register` - Registration page
-   `/courses/browse` - Browse all published courses
-   `/courses/{course}` - View course details

#### Protected Routes (Authentication Required)

-   `/dashboard` - User dashboard (role-specific)
-   `/courses` - Instructor's course list (instructor only)
-   `/courses/create` - Create course (instructor only)
-   `/courses/{course}/edit` - Edit course (instructor only)
-   `/courses/{course}/lessons/*` - Lesson management (instructor only)
-   `/enrollments` - Student enrollments (student only)
-   `/courses/{course}/enroll` - Enroll in course (student only)

---

## Development Workflow

### The Frontend First Workflow

#### Step 1: Design the UI (View First)

1. Create a Blade view file in `resources/views/`
2. Write HTML with Tailwind CSS
3. Use fake/mock data to see how it looks
4. Test in browser

#### Step 2: Create the Route

1. Add route in `routes/web.php`
2. Point it to the view (temporarily)
3. **Important**: Place specific routes before parameterized routes
4. Test the URL works

#### Step 3: Add Controller Logic

1. Create controller
2. Move logic from route to controller
3. Update route to use controller

#### Step 4: Connect to Database

1. Create migration (database table)
2. Create model
3. Update controller to use model
4. Replace fake data with real data

#### Step 5: Add Authorization

1. Add middleware to routes
2. Add authorization checks in controllers
3. Test access control

---

## Building a Feature (Step-by-Step)

### Checklist for Every Feature

-   [ ] **1. Design the UI** (create Blade view with mock data)
-   [ ] **2. Test the view** (create temporary route)
-   [ ] **3. Style it** (add Tailwind classes)
-   [ ] **4. Create proper route** (in `routes/web.php`, mind the order!)
-   [ ] **5. Create controller** (move logic from route)
-   [ ] **6. Create model** (if you need database)
-   [ ] **7. Create migration** (database structure)
-   [ ] **8. Connect everything** (controller → model → view)
-   [ ] **9. Add middleware** (if route needs protection)
-   [ ] **10. Test with real data**
-   [ ] **11. Refine and polish**

### Quick Reference: Laravel Commands

```bash
# Create a controller
php artisan make:controller CourseController

# Create a resource controller (CRUD)
php artisan make:controller CourseController --resource

# Create a model
php artisan make:model Course

# Create a migration
php artisan make:migration create_courses_table

# Create model + migration together
php artisan make:model Course -m

# Create middleware
php artisan make:middleware EnsureUserIsInstructor

# Create policy
php artisan make:policy CoursePolicy --model=Course

# Run migrations
php artisan migrate

# View all routes
php artisan route:list

# Start development server
php artisan serve

# Watch and compile frontend assets
npm run dev

# Build frontend assets for production
npm run build
```

---

## Key Concepts

### 1. Blade Syntax

Blade is Laravel's templating engine. It lets you write PHP in a cleaner way.

#### Displaying Variables

```blade
{{ $variable }}              {{-- Escaped output (safe) --}}
{!! $html !!}                {{-- Raw HTML (use carefully) --}}
```

#### Conditionals

```blade
@if($condition)
    <p>This shows if true</p>
@else
    <p>This shows if false</p>
@endif

@auth
    <p>User is logged in</p>
    <p>Welcome, {{ auth()->user()->name }}!</p>
@else
    <p>Please <a href="{{ route('login') }}">login</a> to continue</p>
@endauth

@if(auth()->check() && auth()->user()->role === 'instructor')
    <p>User is an instructor</p>
@endif

@guest
    <a href="{{ route('register') }}">Sign Up</a>
@endguest
```

#### Conditional Links Based on Auth Status

```blade
{{-- Show different buttons based on authentication --}}
@auth
    <a href="{{ route('dashboard') }}">Go to Dashboard</a>
@else
    <a href="{{ route('register') }}">Get Started Free</a>
@endauth

{{-- Role-based links --}}
@auth
    @if(auth()->user()->role === 'student')
        <a href="{{ route('courses.browse') }}">Browse Courses</a>
    @else
        <a href="{{ route('courses.index') }}">My Courses</a>
    @endif
@endauth
```

#### Loops

```blade
@foreach($items as $item)
    <p>{{ $item->name }}</p>
@endforeach
```

#### Including Layouts

```blade
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
    <h1>Page Content</h1>
@endsection
```

### 2. Passing Data to Views

**From Route:**

```php
Route::get('/courses', function () {
    return view('courses.index', ['courses' => $courses]);
});
```

**From Controller:**

```php
public function index()
{
    $courses = Course::all();
    return view('courses.index', compact('courses'));
    // OR
    return view('courses.index', ['courses' => $courses]);
}
```

**In View:**

```blade
@foreach($courses as $course)
    {{ $course->title }}
@endforeach
```

### 3. Routes

**Basic Route:**

```php
Route::get('/courses', [CourseController::class, 'index']);
```

**Route with Parameter:**

```php
Route::get('/courses/{course}', [CourseController::class, 'show']);
```

**Route with Middleware:**

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

**Nested Routes:**

```php
Route::prefix('courses/{course}')->group(function () {
    Route::get('/lessons/{lesson}', [LessonController::class, 'show']);
});
```

### 4. Controllers

**Basic Controller:**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }
}
```

**Note**: In Laravel 11+, do NOT use `$this->middleware()` in constructors. Apply middleware in routes instead.

### 5. Models

**Basic Model:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relationships
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
}
```

**Using Models:**

```php
// Get all
$courses = Course::all();

// Get one
$course = Course::find(1);
// OR with route model binding
public function show(Course $course) { ... }

// Create
Course::create(['title' => 'New Course']);

// Update
$course->update(['title' => 'Updated Title']);

// Delete
$course->delete();

// With relationships
$course->load('lessons', 'instructor');
$course->lessons()->count();
```

### 6. Middleware

**Creating Middleware:**

```php
// app/Http/Middleware/EnsureUserIsInstructor.php
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check() || auth()->user()->role !== 'instructor') {
        abort(403, 'Only instructors can access this page.');
    }

    return $next($request);
}
```

**Registering Middleware:**

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'instructor' => \App\Http\Middleware\EnsureUserIsInstructor::class,
        'student' => \App\Http\Middleware\EnsureUserIsStudent::class,
    ]);
})
```

**Using Middleware:**

```php
// In routes/web.php
Route::middleware('instructor')->group(function () {
    Route::get('/courses/create', [CourseController::class, 'create']);
});
```

---

## Laravel 11+ Important Notes

### ⚠️ No Middleware in Controller Constructors

**Laravel 11+ removed the ability to use `$this->middleware()` in controller constructors.**

**❌ Don't do this:**

```php
class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // This will cause an error!
        $this->middleware('instructor')->except(['browse', 'show']);
    }
}
```

**✅ Do this instead:**

```php
// In routes/web.php
Route::middleware('auth')->group(function () {
    Route::middleware('instructor')->group(function () {
        Route::get('/courses', [CourseController::class, 'index']);
        Route::get('/courses/create', [CourseController::class, 'create']);
    });
});
```

### Route Model Binding

Laravel automatically resolves model instances from route parameters:

```php
// Route
Route::get('/courses/{course}', [CourseController::class, 'show']);

// Controller - $course is automatically resolved
public function show(Course $course)
{
    // $course is already loaded from database
    return view('courses.show', compact('course'));
}
```

---

## Route Ordering Rules

### ⚠️ Critical: Route Order Matters!

Laravel matches routes in the order they're defined. **Specific routes must come before parameterized routes.**

### ❌ Wrong Order (Will Cause 404 Errors)

```php
// This will match /courses/create as /courses/{course} where course = "create"
Route::get('/courses/{course}', [CourseController::class, 'show']);
Route::get('/courses/create', [CourseController::class, 'create']); // 404!
```

### ✅ Correct Order

```php
// Specific routes first
Route::get('/courses/browse', [CourseController::class, 'browse']);
Route::get('/courses/create', [CourseController::class, 'create']);

// Parameterized routes last
Route::get('/courses/{course}', [CourseController::class, 'show']);
Route::get('/courses/{course}/edit', [CourseController::class, 'edit']);
```

### Same Rule for Nested Routes

```php
Route::prefix('courses/{course}')->group(function () {
    // Specific routes first
    Route::get('/lessons/create', [LessonController::class, 'create']);
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit']);

    // Parameterized routes last
    Route::get('/lessons/{lesson}', [LessonController::class, 'show']);
});
```

### Best Practice

Always define routes in this order:

1. Most specific routes (e.g., `/courses/browse`, `/courses/create`)
2. Routes with multiple parameters (e.g., `/courses/{course}/lessons/{lesson}/edit`)
3. Routes with single parameter (e.g., `/courses/{course}/edit`)
4. Most general parameterized route (e.g., `/courses/{course}`)

---

## Best Practices

### 1. **Start with the View**

Always create the Blade template first, even with fake data. This helps you:

-   See what you're building
-   Understand what data you need
-   Test styling immediately

### 2. **Keep Controllers Thin**

Controllers should only:

-   Get data (from models)
-   Pass data to views
-   Handle simple validation
-   Check authorization

**Bad:**

```php
public function index()
{
    // Too much logic in controller!
    $courses = Course::all();
    foreach($courses as $course) {
        $course->formatted_date = date('Y-m-d', strtotime($course->created_at));
        $course->is_new = $course->created_at > now()->subDays(7);
    }
    return view('courses.index', compact('courses'));
}
```

**Good:**

```php
public function index()
{
    $courses = Course::where('instructor_id', auth()->id())
        ->withCount('lessons')
        ->latest()
        ->get();
    return view('courses.index', compact('courses'));
}
```

### 3. **Use Route Model Binding**

Instead of manually finding models, use route model binding:

**Bad:**

```php
public function show($id)
{
    $course = Course::findOrFail($id);
    return view('courses.show', compact('course'));
}
```

**Good:**

```php
public function show(Course $course)
{
    return view('courses.show', compact('course'));
}
```

### 4. **Apply Middleware in Routes**

In Laravel 11+, apply middleware in routes, not controllers:

```php
Route::middleware('auth')->group(function () {
    Route::middleware('instructor')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create']);
    });
});
```

### 5. **Follow Naming Conventions**

-   Controllers: `PascalCaseController`
-   Models: `PascalCase` (singular)
-   Views: `kebab-case`
-   Routes: `kebab-case` URLs

### 6. **Organize Views by Feature**

```
resources/views/
├── courses/
│   ├── index.blade.php      (list all courses)
│   ├── show.blade.php        (show one course)
│   ├── create.blade.php      (create new course)
│   └── edit.blade.php         (edit course)
├── lessons/
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
└── layouts/
    └── app.blade.php
```

### 7. **Use Our Color Scheme**

Always use the defined color variables from `app.css`:

```blade
{{-- Good: Using our color scheme --}}
<div class="bg-primary-600 text-white">
    <h1 class="text-neutral-900">Title</h1>
</div>

{{-- Bad: Hardcoded colors --}}
<div class="bg-green-600 text-white">
    <h1 class="text-gray-900">Title</h1>
</div>
```

### 8. **Handle Authorization Properly**

Always check if user has permission:

```php
public function edit(Course $course)
{
    // Check authorization
    if (auth()->id() !== $course->instructor_id) {
        abort(403);
    }

    return view('courses.edit', compact('course'));
}
```

---

## Common Patterns

### Pattern 1: List Page (Index)

**Route (Public Browse):**

```php
// Public route - anyone can browse published courses
Route::get('/courses/browse', [CourseController::class, 'browse'])->name('courses.browse');
```

**Controller:**

```php
public function browse()
{
    $courses = Course::where('is_published', true)
        ->with(['instructor', 'lessons'])
        ->withCount('lessons')
        ->latest()
        ->get();
    return view('courses.browse', compact('courses'));
}
```

**Route (Instructor's Courses):**

```php
// Protected route - only instructors can see their own courses
Route::middleware(['auth', 'instructor'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
});
```

**Controller:**

```php
public function index()
{
    $courses = Course::where('instructor_id', auth()->id())
        ->withCount('lessons')
        ->latest()
        ->get();
    return view('courses.index', compact('courses'));
}
```

**View:**

```blade
@foreach($courses as $course)
    <div>{{ $course->title }}</div>
@endforeach
```

### Pattern 2: Detail Page (Show)

**Route (Public - No Auth Required):**

```php
// Public route - guests can view course details
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
```

**Controller:**

```php
public function show(Course $course)
{
    $course->load(['instructor', 'lessons']);

    // Check enrollment only if user is authenticated
    $isEnrolled = false;
    if (Auth::check() && Auth::user()->role === 'student') {
        $isEnrolled = $course->enrollments()
            ->where('student_id', Auth::id())
            ->exists();
    }

    return view('courses.show', compact('course', 'isEnrolled'));
}
```

**View:**

```blade
<h1>{{ $course->title }}</h1>
<p>{{ $course->description }}</p>

@auth
    @if(auth()->user()->role === 'student' && !$isEnrolled)
        <form method="POST" action="{{ route('enrollments.store', $course) }}">
            @csrf
            <button type="submit">Enroll Now</button>
        </form>
    @endif
@else
    <a href="{{ route('register') }}">Sign up to enroll</a>
@endauth
```

### Pattern 3: Create Form

**Route:**

```php
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
```

**Controller:**

```php
public function create()
{
    return view('courses.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $course = Course::create([
        'instructor_id' => auth()->id(),
        'title' => $validated['title'],
        'description' => $validated['description'],
    ]);

    return redirect()->route('courses.show', $course)
        ->with('success', 'Course created successfully!');
}
```

**View:**

```blade
<form method="POST" action="{{ route('courses.store') }}">
    @csrf
    <input type="text" name="title" required>
    <textarea name="description"></textarea>
    <button type="submit">Create Course</button>
</form>
```

### Pattern 4: Nested Resources

**Route:**

```php
Route::prefix('courses/{course}')->group(function () {
    Route::get('/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
});
```

**Controller:**

```php
public function create(Course $course)
{
    if (auth()->id() !== $course->instructor_id) {
        abort(403);
    }
    return view('lessons.create', compact('course'));
}

public function store(Request $request, Course $course)
{
    if (auth()->id() !== $course->instructor_id) {
        abort(403);
    }

    $course->lessons()->create($request->validated());
    return redirect()->route('courses.show', $course);
}
```

---

## Quick Decision Tree

**"I want to add a new page. Where do I start?"**

1. ✅ **Create the view first** (`resources/views/your-page.blade.php`)
2. ✅ **Add a route** (`routes/web.php` - remember route order!)
3. ✅ **Test it works** (visit the URL)
4. ✅ **Add styling** (Tailwind CSS)
5. ✅ **Create controller** (if you need logic)
6. ✅ **Add model** (if you need database)
7. ✅ **Create migration** (if you need new table)
8. ✅ **Add middleware** (if route needs protection)

**"I need to show data from the database. How?"**

1. ✅ **Create/use model** (`app/Models/YourModel.php`)
2. ✅ **In controller**: `$items = YourModel::all();`
3. ✅ **Pass to view**: `return view('page', compact('items'));`
4. ✅ **In view**: `@foreach($items as $item) ... @endforeach`

**"I need to save data. How?"**

1. ✅ **Create form in view** (`<form method="POST">`)
2. ✅ **Add route for POST** (`Route::post('/path', [Controller::class, 'store']);`)
3. ✅ **In controller**: `YourModel::create([...]);`
4. ✅ **Redirect**: `return redirect('/path');`

**"I'm getting a 404 error on a route. Why?"**

1. ✅ **Check route order** - specific routes must come before parameterized routes
2. ✅ **Check route name** - make sure it matches
3. ✅ **Check middleware** - make sure you're authenticated/authorized (if required)
4. ✅ **Check if route is public** - some routes like `/courses/browse` are public, others require auth
5. ✅ **Run `php artisan route:list`** - see all registered routes

**"Can guests view courses?"**

Yes! The following routes are public (no login required):

-   `/courses/browse` - Browse all published courses
-   `/courses/{course}` - View course details

However, to enroll in a course or view lessons, users must be logged in as a student.

---

## Remember

1. **Frontend First**: Always start with the view
2. **See it work**: Test in browser as soon as possible
3. **Route order matters**: Specific routes before parameterized routes
4. **No middleware in constructors**: Apply middleware in routes (Laravel 11+)
5. **Iterate**: Build, test, refine, repeat
6. **Keep it simple**: Don't overcomplicate things
7. **Use our colors**: Stick to the defined color scheme
8. **Follow conventions**: Naming, structure, patterns

---

## Need Help?

-   Check Laravel docs: https://laravel.com/docs
-   Check Tailwind docs: https://tailwindcss.com/docs
-   Review this architecture guide
-   Look at existing code for examples
-   Check `php artisan route:list` to see all routes

**Happy coding! 🌱**
