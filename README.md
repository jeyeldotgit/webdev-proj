# SproutLMS

A lightweight, open-source Learning Management System built with Laravel. Designed for individual creators and small teams who need a "no-fuss" platform to host video or text-based lessons, track student enrollment, and monitor basic completion progress.

**Philosophy**: Start small, grow big. Fundamental Learning.

## Features

-   🎓 **Course Management** - Create and manage courses with text or video lessons
-   👥 **Student Enrollment** - Easy enrollment system for students
-   📊 **Progress Tracking** - Monitor student completion automatically
-   📝 **Assignments** - Instructors can create assignments, students can submit, and instructors can grade
-   👨‍💼 **Admin Panel** - Comprehensive admin dashboard for managing students, courses, and enrollments
-   🎨 **Modern UI** - Clean, responsive design with Tailwind CSS
-   🔐 **Role-Based Access** - Separate dashboards for admins, instructors, and students
-   🌐 **Public Browsing** - Guests can browse published courses without login
-   🚀 **Laravel Breeze** - Modern authentication system with rate limiting and security features

## Quick Start

Welcome to the team! This repository is our monorepo for a collaborative Laravel project.

Please follow the instructions below to ensure our development process stays smooth and conflict-free.

## Prerequisites

Before starting, ensure you have the following installed:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   Git

*   if you have laragon just use the laragon terminal.

## Local Development Setup

Follow these steps to get SproutLMS running on your local machine.

### 1. Clone the Repository

```bash
git clone [INSERT_REPO_URL_HERE]
cd [PROJECT_FOLDER_NAME]
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Prepare the Environment File

```bash
cp .env.example .env
```

Open the `.env` file and update your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_app
DB_USERNAME=root
DB_PASSWORD=
```

**Note**: If you're using Laragon, the database credentials are typically already configured.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

This will create all necessary tables: `users`, `courses`, `lessons`, `enrollments`, `progress`, `assignments`, and `assignment_submissions`.

### 6. Create an Admin User

**Important**: The admin role cannot be registered through the public registration form for security reasons. You must create an admin user manually using Laravel Tinker.

#### What is Laravel Tinker?

Laravel Tinker is Laravel's REPL (Read-Eval-Print Loop) - an interactive shell that allows you to execute PHP code directly against your Laravel application. It's the recommended way to create admin users securely.

#### Steps to Create Admin User:

1. **Open Laravel Tinker**:

```bash
php artisan tinker
```

2. **In the Tinker console, run the following commands**:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
]);
```

3. **Exit Tinker**:

```bash
exit
```

**Default Admin Credentials** (change immediately in production):

-   **Email**: `admin@example.com`
-   **Password**: `password`

⚠️ **Security Note**: Always change the default password after first login in production environments!

### 7. Compile Frontend Assets

In a separate terminal window, run:

```bash
npm run dev
```

This will watch for changes and compile your CSS and JavaScript assets.

### 8. Start the Development Server

If you're using Laragon, the server is typically already running. Otherwise, start Laravel's development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000` or your Laragon domain (e.g., `http://lms-app.test`).

### 9. Access the Application

1. **Login as Admin**: Go to `/login` and use the admin credentials you created
2. **Create Test Users**: You can register student and instructor accounts through `/register`
3. **Admin Dashboard**: After logging in as admin, you'll be redirected to `/admin`

## Why Can't Admins Sign Up?

The admin role is restricted from public registration for security reasons:

1. **Prevents Unauthorized Access**: Admin accounts have full system access, including the ability to manage all users, courses, and system settings.
2. **Controlled Creation**: By requiring manual creation via Tinker or seeders, only authorized developers/system administrators can create admin accounts.
3. **Audit Trail**: Manual creation ensures there's a clear record of who created admin accounts and when.

**Alternative Methods** (if you prefer not to use Tinker):

-   Use database seeders: `php artisan make:seeder AdminSeeder`
-   Direct database insertion (not recommended for production)

## Frontend Guidelines (Blade)

### Components

If a UI element (such as a button or card) is used more than once, create a Blade component in:

```
resources/views/components/
```

### Layouts

Use the main layout for all pages to maintain consistency:

```blade
@extends('layouts.app')
```

### Assets

Use the `asset()` helper for images and static files:

```blade
<img src="{{ asset('path/to/file') }}" alt="Image">
```

Use Vite for styles and scripts:

```blade
{{ vite(['resources/css/app.css', 'resources/js/app.js']) }}
```

## Common Commands

| Command                     | Description                                    |
| --------------------------- | ---------------------------------------------- |
| `php artisan serve`         | Start the local development server             |
| `npm run dev`               | Watch and compile frontend assets              |
| `npm run build`             | Build frontend assets for production           |
| `php artisan route:list`    | View all available routes                      |
| `php artisan migrate`       | Run database migrations                        |
| `php artisan migrate:fresh` | Drop all tables and re-run migrations          |
| `php artisan tinker`        | Open Laravel Tinker (for creating admin users) |
| `php artisan db:seed`       | Run database seeders                           |

## Application Routes

### Public Routes (No Login Required)

-   `/` - Landing page
-   `/login` - Login page
-   `/register` - Registration page (student/instructor only)
-   `/courses/browse` - Browse published courses
-   `/courses/{course}` - View course details

### Protected Routes (Login Required)

#### Admin Routes

-   `/admin` - Admin dashboard
-   `/admin/students` - Manage all students
-   `/admin/students/create` - Create new student account
-   `/admin/students/{student}` - View/edit student details

#### Instructor Routes

-   `/dashboard` - Instructor dashboard (redirects to `/admin` for admins)
-   `/courses` - Course list (instructor's own courses, or all courses for admins)
-   `/courses/create` - Create new course
-   `/courses/{course}/lessons` - Manage lessons
-   `/courses/{course}/assignments` - Manage assignments
-   `/courses/{course}/assignments/{assignment}` - View/grade submissions

#### Student Routes

-   `/dashboard` - Student dashboard
-   `/enrollments` - Enrolled courses
-   `/courses/{course}/enroll` - Enroll in a course
-   `/courses/{course}/assignments/{assignment}` - View and submit assignments

## User Roles

-   **Admin** - Can manage all courses, students, and assign instructors to courses. Admins cannot be registered via public registration.
-   **Instructor** - Can create courses, manage lessons, create assignments, and grade student submissions
-   **Student** - Can browse courses, enroll, submit assignments, and track lesson progress

## Quick Reference

### Creating Users

**Admin** (via Tinker):

```bash
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'role' => 'admin']);
```

**Student/Instructor**: Register via `/register` page

### Testing the Application

1. **As Admin**: Login with admin credentials → Access `/admin` dashboard
2. **As Instructor**: Register as instructor → Create courses → Add lessons → Create assignments
3. **As Student**: Register as student → Browse courses → Enroll → Submit assignments

## Documentation

-   [Architecture Guide](docs/architecture.md) - Development approach, patterns, and database schema
-   [API Documentation](docs/api.md) - API structure and patterns (for future implementation)
-   [Git Rules](docs/gitrules.md) - Git workflow and conventions

## Support

For issues, questions, or contributions, please refer to the project's issue tracker or contact the development team.
