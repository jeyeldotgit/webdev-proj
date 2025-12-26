# Architecture Guide – SproutLMS

This document defines the architecture and development approach for SproutLMS. It's designed to guide developers, especially those new to Laravel, through building features using a **Frontend First** mental model.

---

## Table of Contents

1. [Philosophy: Frontend First](#philosophy-frontend-first)
2. [Understanding Laravel (Simple Terms)](#understanding-laravel-simple-terms)
3. [Project Structure](#project-structure)
4. [Development Workflow](#development-workflow)
5. [Building a Feature (Step-by-Step)](#building-a-feature-step-by-step)
6. [Key Concepts](#key-concepts)
7. [Best Practices](#best-practices)
8. [Common Patterns](#common-patterns)

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
- **What it is**: A map that says "when someone visits this URL, do this"
- **Example**: `Route::get('/courses', ...)` means "when someone goes to `/courses`, show them something"

#### 2. **Views** (`resources/views/`)
- **What it is**: The HTML templates (what users see)
- **File type**: `.blade.php` files (Blade is Laravel's templating engine)
- **Example**: `landing.blade.php` is the landing page HTML

#### 3. **Controllers** (`app/Http/Controllers/`)
- **What it is**: PHP classes that handle the logic
- **Purpose**: Get data, process it, send it to the view
- **Example**: `CourseController` handles everything related to courses

#### 4. **Models** (`app/Models/`)
- **What it is**: PHP classes that represent database tables
- **Purpose**: Easy way to work with database data
- **Example**: `Course` model represents the `courses` table

#### 5. **Migrations** (`database/migrations/`)
- **What it is**: Files that create/modify database tables
- **Purpose**: Version control for your database structure
- **Example**: `create_courses_table.php` creates the courses table

### How They Work Together

```
User visits URL
    ↓
Route receives request
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
│   │   └── Controllers/         # Controllers (business logic)
│   └── Models/                   # Models (database representation)
│
├── resources/
│   ├── views/                    # Frontend templates (Blade files)
│   │   ├── components/           # Reusable UI components
│   │   └── layouts/             # Page layouts
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

- **Controllers**: `PascalCase` + `Controller` suffix
  - Example: `CourseController.php`, `LessonController.php`
- **Models**: `PascalCase`, singular
  - Example: `Course.php`, `Lesson.php`, `User.php`
- **Views**: `kebab-case` (lowercase with hyphens)
  - Example: `course-list.blade.php`, `lesson-show.blade.php`
- **Migrations**: `snake_case` with timestamp
  - Example: `2024_01_15_120000_create_courses_table.php`

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
3. Test the URL works

#### Step 3: Add Controller Logic
1. Create controller
2. Move logic from route to controller
3. Update route to use controller

#### Step 4: Connect to Database
1. Create migration (database table)
2. Create model
3. Update controller to use model
4. Replace fake data with real data

### Example: Building a Course List Page

#### Step 1: Create the View (Frontend First!)

```blade
{{-- resources/views/courses/index.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Courses - SproutLMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>My Courses</h1>
    
    {{-- Fake data to see how it looks --}}
    <div class="course-card">
        <h2>Introduction to PHP</h2>
        <p>Learn PHP from scratch</p>
        <span>12 lessons</span>
    </div>
    
    <div class="course-card">
        <h2>Laravel Basics</h2>
        <p>Master Laravel framework</p>
        <span>20 lessons</span>
    </div>
</body>
</html>
```

**Test it**: Create a route to see this view immediately!

#### Step 2: Create Route

```php
// routes/web.php
Route::get('/courses', function () {
    return view('courses.index');
});
```

**Test it**: Visit `http://localhost:8000/courses` - you should see your page!

#### Step 3: Add Controller

```php
// app/Http/Controllers/CourseController.php
<?php

namespace App\Http\Controllers;

class CourseController extends Controller
{
    public function index()
    {
        // For now, use fake data
        $courses = [
            ['title' => 'Introduction to PHP', 'description' => 'Learn PHP from scratch', 'lessons' => 12],
            ['title' => 'Laravel Basics', 'description' => 'Master Laravel framework', 'lessons' => 20],
        ];
        
        return view('courses.index', ['courses' => $courses]);
    }
}
```

Update the route:
```php
// routes/web.php
use App\Http\Controllers\CourseController;

Route::get('/courses', [CourseController::class, 'index']);
```

Update the view to use data:
```blade
{{-- resources/views/courses/index.blade.php --}}
@foreach($courses as $course)
    <div class="course-card">
        <h2>{{ $course['title'] }}</h2>
        <p>{{ $course['description'] }}</p>
        <span>{{ $course['lessons'] }} lessons</span>
    </div>
@endforeach
```

#### Step 4: Connect to Database

Create migration:
```bash
php artisan make:migration create_courses_table
```

Edit migration:
```php
// database/migrations/xxxx_create_courses_table.php
public function up()
{
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->integer('lessons_count')->default(0);
        $table->timestamps();
    });
}
```

Run migration:
```bash
php artisan migrate
```

Create model:
```php
// app/Models/Course.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'lessons_count'];
}
```

Update controller:
```php
// app/Http/Controllers/CourseController.php
use App\Models\Course;

public function index()
{
    $courses = Course::all(); // Get from database
    return view('courses.index', ['courses' => $courses]);
}
```

Update view:
```blade
@foreach($courses as $course)
    <div class="course-card">
        <h2>{{ $course->title }}</h2>
        <p>{{ $course->description }}</p>
        <span>{{ $course->lessons_count }} lessons</span>
    </div>
@endforeach
```

**Done!** You've built a feature Frontend First!

---

## Building a Feature (Step-by-Step)

### Checklist for Every Feature

- [ ] **1. Design the UI** (create Blade view with mock data)
- [ ] **2. Test the view** (create temporary route)
- [ ] **3. Style it** (add Tailwind classes)
- [ ] **4. Create proper route** (in `routes/web.php`)
- [ ] **5. Create controller** (move logic from route)
- [ ] **6. Create model** (if you need database)
- [ ] **7. Create migration** (database structure)
- [ ] **8. Connect everything** (controller → model → view)
- [ ] **9. Test with real data**
- [ ] **10. Refine and polish**

### Quick Reference: Laravel Commands

```bash
# Create a controller
php artisan make:controller CourseController

# Create a model
php artisan make:model Course

# Create a migration
php artisan make:migration create_courses_table

# Create model + migration together
php artisan make:model Course -m

# Run migrations
php artisan migrate

# View all routes
php artisan route:list

# Start development server
php artisan serve

# Watch and compile frontend assets
npm run dev
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
```

#### Loops
```blade
@foreach($items as $item)
    <p>{{ $item->name }}</p>
@endforeach
```

#### Including Components
```blade
<x-button text="Click me" />
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
Route::get('/courses/{id}', [CourseController::class, 'show']);
```

**Resource Routes (CRUD):**
```php
Route::resource('courses', CourseController::class);
// Creates: GET /courses, GET /courses/create, POST /courses, etc.
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
    
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('courses.show', compact('course'));
    }
}
```

### 5. Models

**Basic Model:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Laravel automatically knows this model uses the 'courses' table
    
    protected $fillable = [
        'title',
        'description',
        'lessons_count'
    ];
}
```

**Using Models:**
```php
// Get all
$courses = Course::all();

// Get one
$course = Course::find(1);

// Create
Course::create(['title' => 'New Course']);

// Update
$course->update(['title' => 'Updated Title']);
```

---

## Best Practices

### 1. **Start with the View**
Always create the Blade template first, even with fake data. This helps you:
- See what you're building
- Understand what data you need
- Test styling immediately

### 2. **Keep Controllers Thin**
Controllers should only:
- Get data (from models)
- Pass data to views
- Handle simple validation

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
    $courses = Course::all();
    return view('courses.index', compact('courses'));
}
```

Move logic to the model or view where appropriate.

### 3. **Use Components for Reusable UI**
If you use the same UI element more than once, create a component:

```blade
{{-- resources/views/components/course-card.blade.php --}}
<div class="course-card">
    <h2>{{ $title }}</h2>
    <p>{{ $description }}</p>
</div>
```

Use it:
```blade
<x-course-card title="PHP Course" description="Learn PHP" />
```

### 4. **Follow Naming Conventions**
- Controllers: `PascalCaseController`
- Models: `PascalCase` (singular)
- Views: `kebab-case`
- Routes: `kebab-case` URLs

### 5. **Organize Views by Feature**
```
resources/views/
├── courses/
│   ├── index.blade.php      (list all courses)
│   ├── show.blade.php        (show one course)
│   └── create.blade.php      (create new course)
├── lessons/
│   ├── index.blade.php
│   └── show.blade.php
└── components/
    └── course-card.blade.php
```

### 6. **Use Our Color Scheme**
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

---

## Common Patterns

### Pattern 1: List Page (Index)

**Route:**
```php
Route::get('/courses', [CourseController::class, 'index']);
```

**Controller:**
```php
public function index()
{
    $courses = Course::all();
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

**Route:**
```php
Route::get('/courses/{id}', [CourseController::class, 'show']);
```

**Controller:**
```php
public function show($id)
{
    $course = Course::findOrFail($id);
    return view('courses.show', compact('course'));
}
```

**View:**
```blade
<h1>{{ $course->title }}</h1>
<p>{{ $course->description }}</p>
```

### Pattern 3: Create Form

**Route:**
```php
Route::get('/courses/create', [CourseController::class, 'create']);
Route::post('/courses', [CourseController::class, 'store']);
```

**Controller:**
```php
public function create()
{
    return view('courses.create');
}

public function store(Request $request)
{
    Course::create([
        'title' => $request->title,
        'description' => $request->description,
    ]);
    
    return redirect('/courses');
}
```

**View:**
```blade
<form method="POST" action="/courses">
    @csrf
    <input type="text" name="title" required>
    <textarea name="description"></textarea>
    <button type="submit">Create Course</button>
</form>
```

---

## Quick Decision Tree

**"I want to add a new page. Where do I start?"**

1. ✅ **Create the view first** (`resources/views/your-page.blade.php`)
2. ✅ **Add a route** (`routes/web.php`)
3. ✅ **Test it works** (visit the URL)
4. ✅ **Add styling** (Tailwind CSS)
5. ✅ **Create controller** (if you need logic)
6. ✅ **Add model** (if you need database)
7. ✅ **Create migration** (if you need new table)

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

---

## Remember

1. **Frontend First**: Always start with the view
2. **See it work**: Test in browser as soon as possible
3. **Iterate**: Build, test, refine, repeat
4. **Keep it simple**: Don't overcomplicate things
5. **Use our colors**: Stick to the defined color scheme
6. **Follow conventions**: Naming, structure, patterns

---

## Need Help?

- Check Laravel docs: https://laravel.com/docs
- Check Tailwind docs: https://tailwindcss.com/docs
- Review this architecture guide
- Look at existing code for examples

**Happy coding! 🌱**

