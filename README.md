# SproutLMS

A lightweight, open-source Learning Management System built with Laravel. Designed for individual creators and small teams who need a "no-fuss" platform to host video or text-based lessons, track student enrollment, and monitor basic completion progress.

**Philosophy**: Start small, grow big. Fundamental Learning.

## Features

-   🎓 **Course Management** - Create and manage courses with text or video lessons
-   👥 **Student Enrollment** - Easy enrollment system for students
-   📊 **Progress Tracking** - Monitor student completion automatically
-   🎨 **Modern UI** - Clean, responsive design with Tailwind CSS
-   🔐 **Role-Based Access** - Separate dashboards for instructors and students
-   🌐 **Public Browsing** - Guests can browse published courses without login

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

## Initial Setup

If you are setting up the project for the first time, run the following commands in order.

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

Open the `.env` file and update your database credentials if necessary.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Compile Frontend Assets

```bash
npm run dev
```

## Git Workflow

**Important:** Never push directly to the main branch. To avoid breaking the project, always use the workflow below.

### 1. Update Your Local Main Branch

Before starting any new work, make sure your local code is up to date:

```bash
git checkout main
git pull origin main
```

### 2. Create a Feature Branch

Create a branch named after the task you are working on:

```bash
git checkout -b feature/your-task-name
```

### 3. Work and Commit

Save your progress with clear and descriptive commit messages:

```bash
git add .
git commit -m "Add: brief description of what you did"
```

### 4. Push and Create a Pull Request

Upload your branch to GitHub:

```bash
git push origin feature/your-task-name
```

Then:

1. Go to GitHub.com
2. Click "Compare & pull request"
3. Wait for the project lead to review and merge your code

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

| Command                     | Description                           |
| --------------------------- | ------------------------------------- |
| `php artisan serve`         | Start the local development server    |
| `npm run dev`               | Watch and compile frontend assets     |
| `npm run build`             | Build frontend assets for production  |
| `php artisan route:list`    | View all available routes             |
| `php artisan migrate`       | Run database migrations               |
| `php artisan migrate:fresh` | Drop all tables and re-run migrations |

## Application Routes

### Public Routes (No Login Required)

-   `/` - Landing page
-   `/login` - Login page
-   `/register` - Registration page
-   `/courses/browse` - Browse published courses
-   `/courses/{course}` - View course details

### Protected Routes (Login Required)

-   `/dashboard` - User dashboard (role-specific)
-   `/courses` - Instructor's course list (instructor only)
-   `/courses/create` - Create new course (instructor only)
-   `/enrollments` - Student's enrolled courses (student only)

## User Roles

-   **Student** - Can browse courses, enroll, and track progress
-   **Instructor** - Can create courses, manage lessons, and view enrolled students

## Documentation

-   [Architecture Guide](docs/architecture.md) - Development approach and patterns
-   [API Documentation](docs/api.md) - API structure (for future implementation)
-   [Git Rules](docs/gitrules.md) - Git workflow and conventions
