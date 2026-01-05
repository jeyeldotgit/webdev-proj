<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('landing');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Public course browsing (accessible to everyone)
Route::get('/courses/browse', [CourseController::class, 'browse'])->name('courses.browse');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/students', [\App\Http\Controllers\AdminController::class, 'students'])->name('students.index');
        Route::get('/students/create', [\App\Http\Controllers\AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students', [\App\Http\Controllers\AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{student}', [\App\Http\Controllers\AdminController::class, 'showStudent'])->name('students.show');
        Route::get('/students/{student}/edit', [\App\Http\Controllers\AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{student}', [\App\Http\Controllers\AdminController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}', [\App\Http\Controllers\AdminController::class, 'destroyStudent'])->name('students.destroy');
        Route::post('/students/{student}/enroll', [\App\Http\Controllers\AdminController::class, 'enrollStudent'])->name('students.enroll');
        Route::delete('/students/{student}/courses/{course}/unenroll', [\App\Http\Controllers\AdminController::class, 'unenrollStudent'])->name('students.unenroll');
    });
    
    // Course routes - Admin and Instructor can access (must be before /courses/{course})
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Lesson routes (nested under courses)
    Route::prefix('courses/{course}')->group(function () {
        // Instructor-only lesson management routes (must be before /lessons/{lesson})
        Route::middleware('instructor')->group(function () {
            Route::get('/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
            Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');
            Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
            Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
            Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
        });
        
        // View lesson (accessible to enrolled students and instructors) - must be after /lessons/create
        Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');

        // Assignment routes (nested under courses)
        // Instructor-only assignment management routes (must be before /assignments/{assignment})
        Route::middleware('instructor')->group(function () {
            Route::get('/assignments', [\App\Http\Controllers\AssignmentController::class, 'index'])->name('assignments.index');
            Route::get('/assignments/create', [\App\Http\Controllers\AssignmentController::class, 'create'])->name('assignments.create');
            Route::post('/assignments', [\App\Http\Controllers\AssignmentController::class, 'store'])->name('assignments.store');
            Route::get('/assignments/{assignment}/edit', [\App\Http\Controllers\AssignmentController::class, 'edit'])->name('assignments.edit');
            Route::put('/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'update'])->name('assignments.update');
            Route::delete('/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'destroy'])->name('assignments.destroy');
        });

        // View assignment (accessible to enrolled students and instructors) - must be after /assignments/create
        Route::get('/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'show'])->name('assignments.show');

        // Assignment submission routes
        Route::middleware('student')->group(function () {
            Route::post('/assignments/{assignment}/submit', [\App\Http\Controllers\AssignmentSubmissionController::class, 'store'])->name('assignment-submissions.store');
        });

        // Grade assignment (instructor only)
        Route::middleware('instructor')->group(function () {
            Route::put('/assignments/{assignment}/submissions/{submission}', [\App\Http\Controllers\AssignmentSubmissionController::class, 'update'])->name('assignment-submissions.update');
        });
    });

    // Student-only enrollment routes
    Route::middleware('student')->group(function () {
        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');
        Route::delete('/courses/{course}/enroll', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    });
});

// Public course detail route (must be after all specific /courses/* routes)
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
