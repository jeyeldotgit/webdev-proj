# Architecture Guide – SproutLMS

This document defines the architecture and development approach for SproutLMS. It's designed to guide developers working inside the Laravel application whose flow is driven by routes, middleware, controllers, and Blade views rather than designer-first prototypes.

---

## Table of Contents

1. [Philosophy: Backend-First Flow](#philosophy-backend-first-flow)
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

## Philosophy: Backend-First Flow

### What Does "Backend First" Mean?

**Backend First** means we solve for business logic, routing, and permissions before polishing the UI. The rank-and-file of this codebase is the route → middleware → controller → model pipeline, and the Blade view is the final touch that mirrors the data the backend already prepares.

### Why This Approach?

1. **Consistency with Laravel**: Routes define behavior, middleware enforces permissions, and controllers orchestrate models that power the views.
2. **Predictable Access Control**: Backing each route with middleware (like `auth`, `instructor`, `student`) keeps authorization centralized.
3. **Real Data Early**: Controllers query real models before views render them, so Blade templates stay simple and reusable.
4. **Easier Maintenanc**: Changes are tracked through explicit routes and controllers rather than ad-hoc UI-first tweaks.

### The Mental Model

```
Incoming request → Matched route → Middleware guards → Controller handles logic → Model retrieves data → Blade renders response
```

### Example Flow

1. **Define the route** in `routes/web.php` (mind the order: specific routes, then parameterized routes, then fallbacks).
2. **Attach middleware** as needed (`auth`, `instructor`, `student`) so only authorized users can hit it.
3. **Implement controller logic** that queries/updates models (`CourseController`, `LessonController`, etc.).
4. **Build or evolve the view** to reflect the backend data, using Blade components/layouts after real data is flowing.
5. **Add migrations/models** only when new data structures are required.
6. **Polish front-end polish** (Tailwind, layout sections) at the end, since the data and routes define what's possible.

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

### The Route-Driven Workflow

#### Step 1: Understand the data and permissions

1. Determine which models (e.g., `Course`, `Lesson`, `Enrollment`) will participate.
2. Sketch the relationships and any additional fields, then update migrations/models or policies as needed.
3. Decide which roles (student, instructor) should see or mutate the data so we can apply the right middleware later.

#### Step 2: Define the route

1. Add the route to `routes/web.php`, grouping related endpoints under the same middleware (`auth`, `instructor`, `student`).
2. Respect route ordering: specific paths before parameterized ones, nested route groups last.
3. Give it a named route so Blade and redirects can reference it (`route('courses.create')`, etc.).

#### Step 3: Implement controller logic

1. Create/update the appropriate controller (`CourseController`, `LessonController`, etc.).
2. Load or persist models, run validation, and prepare the data needed by the view.
3. Keep controllers lean with helpers or query scopes and, where necessary, use `withCount`, eager loading, or policies.

#### Step 4: Build the Blade view

1. Render the real data the controller passes in, using partials/layouts for repeated patterns.
2. Convert the earlier sketches into Tailwind-based UI while reusing components (`layouts.app`, `components.cards`).
3. Include conditional UI based on roles/state (e.g., `@auth`, `@if(auth()->user()->role === 'instructor')`).

#### Step 5: Test and polish

1. Visit the route in the browser to verify data shows correctly and permissions behave as expected.
2. Add styling improvements, responsive tweaks, and error messaging.
3. Run `php artisan route:list` and `php artisan test` (if applicable) to double-check everything works.

---

## Building a Feature (Step-by-Step)

### Checklist for Every Feature

-   [ ] **1. Define the route and middleware** (add path to `routes/web.php`, keep specific routes first)
-   [ ] **2. Implement controller logic** (load/persist models, validate, authorize)
-   [ ] **3. Update or create models/migrations** (ensure database matches data needs)
-   [ ] **4. Build the Blade view** (render real data using layout/components)
-   [ ] **5. Style with Tailwind** (use shared design tokens and components)
-   [ ] **6. Apply policies or middleware** (protect instructor/student routes)
-   [ ] **7. Test via browser/API** (verify permissions, redirects, errors)

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

1. ✅ **Plan the route and permissions** (`routes/web.php`, group it under `auth`, `instructor`, or `student` as needed)
2. ✅ **Implement the controller** (load the models, validate input, guard actions)
3. ✅ **Build the Blade view** (use the data the controller returns, reuse layouts/components)
4. ✅ **Style and test in the browser** (Tailwind + responsive tweaks)
5. ✅ **Add migrations/models** only when new persistence is required

**"I need to show data from the database. How?"**

1. ✅ **Use the model** (`app/Models/YourModel.php`)
2. ✅ **Controller**: `$items = YourModel::all();` or add scopes/with() as needed
3. ✅ **Pass to view**: `return view('page', compact('items'));`
4. ✅ **Render in Blade**: `@foreach($items as $item) ... @endforeach`

**"I need to save data. How?"**

1. ✅ **Create a POST route** with middleware (e.g., `Route::post('/courses', ...)`)
2. ✅ **Controller store action**: validate then `YourModel::create([...]);`
3. ✅ **Redirect or respond**: `return redirect()->route('courses.show', $course);`
4. ✅ **Show feedback** in the view (session flash, form errors)

**"I'm getting a 404 error on a route. Why?"**

1. ✅ **Check route order** - specific routes must come before parameterized routes
2. ✅ **Check route name** - ensure it matches the named route you're referencing
3. ✅ **Check middleware** - unauthorized users are redirected or receive 403
4. ✅ **Check access level** - some routes require `auth`, `instructor`, or `student`
5. ✅ **Run `php artisan route:list`** - inspect the registered routes

**"Can guests view courses?"**

Yes! The following routes are public (no login required):

-   `/courses/browse` - Browse all published courses
-   `/courses/{course}` - View course details

However, enrolling or managing courses requires authentication with the correct role.

---

## Remember

1. **Define the route before the view**: Route order and middleware come first, then controller/business logic, then Blade.
2. **Test routes with real data**: Visit the URLs and make sure permissions behave as expected.
3. **Controllers stay lean**: Fetch/persist models and pass data to views without embedding styling logic.
4. **Guard access via middleware/policies** (not constructors) so Laravel 11+ route groups handle authorization.
5. **Reuse shared layouts/components** to keep UI consistent and maintainable.
6. **Keep color tokens centralized** in `resources/css/app.css` and use Tailwind variables.
7. **Follow naming conventions**: PascalCase controllers/models, kebab-case views/routes.

---

## Need Help?

-   Check Laravel docs: https://laravel.com/docs
-   Check Tailwind docs: https://tailwindcss.com/docs
-   Review this architecture guide
-   Look at existing code for examples
-   Check `php artisan route:list` to see all routes

**Happy coding! 🌱**
