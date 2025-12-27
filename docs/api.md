# API Documentation – SproutLMS

This document outlines the API structure for SproutLMS. Currently, the application uses traditional web routes with Blade templates. This document serves as a reference for future API development.

---

## Table of Contents

1. [Current State](#current-state)
2. [Future API Structure](#future-api-structure)
3. [Authentication](#authentication)
4. [API Endpoints (Planned)](#api-endpoints-planned)
5. [Response Format](#response-format)
6. [Error Handling](#error-handling)
7. [Rate Limiting](#rate-limiting)
8. [API Versioning](#api-versioning)

---

## Current State

**Status**: Currently, SproutLMS is a traditional web application using:

-   Blade templates for frontend
-   Session-based authentication
-   Web routes (`routes/web.php`)

**Future**: API endpoints can be added for:

-   Mobile app integration
-   Third-party integrations
-   SPA (Single Page Application) frontend
-   Webhook support

---

## Future API Structure

### Proposed API Routes

When implementing API endpoints, create a separate routes file:

```php
// routes/api.php
Route::prefix('api/v1')->group(function () {
    // API routes here
});
```

### API Base URL

```
Production: https://yourdomain.com/api/v1
Development: http://localhost:8000/api/v1
```

---

## Authentication

### Current: Session-Based (Web)

The current implementation uses Laravel's session-based authentication for web routes.

### Future: API Authentication

For API endpoints, implement one of these methods:

#### Option 1: Laravel Sanctum (Recommended)

-   Token-based authentication
-   Supports SPA and mobile apps
-   Easy to implement

#### Option 2: Laravel Passport

-   OAuth2 server
-   More complex, better for third-party integrations

#### Option 3: API Tokens

-   Simple token authentication
-   Good for server-to-server communication

---

## API Endpoints (Planned)

### Authentication Endpoints

#### Register

```
POST /api/v1/register
```

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "student"
}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "student"
        },
        "token": "1|abc123..."
    }
}
```

#### Login

```
POST /api/v1/login
```

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "student"
        },
        "token": "1|abc123..."
    }
}
```

#### Logout

```
POST /api/v1/logout
```

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

### Course Endpoints

#### List Courses (Browse)

```
GET /api/v1/courses
```

**Query Parameters:**

-   `published` (boolean) - Filter by published status
-   `instructor_id` (integer) - Filter by instructor
-   `page` (integer) - Page number for pagination
-   `per_page` (integer) - Items per page

**Response:**

```json
{
    "success": true,
    "data": {
        "courses": [
            {
                "id": 1,
                "title": "Introduction to PHP",
                "description": "Learn PHP from scratch",
                "instructor": {
                    "id": 2,
                    "name": "Jane Instructor"
                },
                "lessons_count": 12,
                "is_published": true,
                "created_at": "2024-01-15T10:00:00Z"
            }
        ],
        "meta": {
            "current_page": 1,
            "total": 10,
            "per_page": 15
        }
    }
}
```

#### Get Course

```
GET /api/v1/courses/{id}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Introduction to PHP",
        "description": "Learn PHP from scratch",
        "instructor": {
            "id": 2,
            "name": "Jane Instructor"
        },
        "lessons": [
            {
                "id": 1,
                "title": "PHP Basics",
                "type": "text",
                "order": 1
            }
        ],
        "is_published": true,
        "created_at": "2024-01-15T10:00:00Z"
    }
}
```

#### Create Course (Instructor Only)

```
POST /api/v1/courses
```

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "title": "New Course",
    "description": "Course description",
    "thumbnail": "https://example.com/image.jpg"
}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 3,
        "title": "New Course",
        "description": "Course description",
        "is_published": false,
        "created_at": "2024-01-15T10:00:00Z"
    }
}
```

#### Update Course (Instructor Only)

```
PUT /api/v1/courses/{id}
PATCH /api/v1/courses/{id}
```

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "title": "Updated Course Title",
    "description": "Updated description",
    "is_published": true
}
```

#### Delete Course (Instructor Only)

```
DELETE /api/v1/courses/{id}
```

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "message": "Course deleted successfully"
}
```

---

### Lesson Endpoints

#### List Lessons

```
GET /api/v1/courses/{course_id}/lessons
```

**Response:**

```json
{
    "success": true,
    "data": {
        "lessons": [
            {
                "id": 1,
                "title": "PHP Basics",
                "type": "text",
                "content": "Lesson content...",
                "order": 1,
                "created_at": "2024-01-15T10:00:00Z"
            }
        ]
    }
}
```

#### Get Lesson

```
GET /api/v1/courses/{course_id}/lessons/{id}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "PHP Basics",
        "type": "text",
        "content": "Lesson content...",
        "video_url": null,
        "order": 1,
        "course": {
            "id": 1,
            "title": "Introduction to PHP"
        },
        "created_at": "2024-01-15T10:00:00Z"
    }
}
```

#### Create Lesson (Instructor Only)

```
POST /api/v1/courses/{course_id}/lessons
```

**Request Body:**

```json
{
    "title": "New Lesson",
    "type": "text",
    "content": "Lesson content...",
    "order": 1
}
```

#### Update Lesson (Instructor Only)

```
PUT /api/v1/courses/{course_id}/lessons/{id}
```

#### Delete Lesson (Instructor Only)

```
DELETE /api/v1/courses/{course_id}/lessons/{id}
```

---

### Enrollment Endpoints

#### Enroll in Course (Student Only)

```
POST /api/v1/courses/{course_id}/enroll
```

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "message": "Successfully enrolled in the course",
    "data": {
        "enrollment": {
            "id": 1,
            "student_id": 1,
            "course_id": 1,
            "enrolled_at": "2024-01-15T10:00:00Z"
        }
    }
}
```

#### Unenroll from Course (Student Only)

```
DELETE /api/v1/courses/{course_id}/enroll
```

#### Get Student Enrollments

```
GET /api/v1/enrollments
```

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "enrollments": [
            {
                "id": 1,
                "course": {
                    "id": 1,
                    "title": "Introduction to PHP",
                    "lessons_count": 12
                },
                "enrolled_at": "2024-01-15T10:00:00Z",
                "progress": {
                    "completed_lessons": 5,
                    "total_lessons": 12,
                    "percentage": 42
                }
            }
        ]
    }
}
```

---

### Progress Endpoints

#### Mark Lesson as Complete

```
POST /api/v1/lessons/{lesson_id}/complete
```

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "progress": {
            "id": 1,
            "student_id": 1,
            "lesson_id": 1,
            "is_completed": true,
            "completed_at": "2024-01-15T10:00:00Z"
        }
    }
}
```

#### Get Course Progress

```
GET /api/v1/courses/{course_id}/progress
```

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "course_id": 1,
        "total_lessons": 12,
        "completed_lessons": 5,
        "percentage": 42,
        "lessons": [
            {
                "id": 1,
                "title": "PHP Basics",
                "is_completed": true,
                "completed_at": "2024-01-15T10:00:00Z"
            }
        ]
    }
}
```

---

### Dashboard Endpoints

#### Get Dashboard Data

```
GET /api/v1/dashboard
```

**Headers:**

```
Authorization: Bearer {token}
```

**Response (Instructor):**

```json
{
    "success": true,
    "data": {
        "role": "instructor",
        "stats": {
            "total_courses": 5,
            "published_courses": 3,
            "total_students": 25
        },
        "courses": [...]
    }
}
```

**Response (Student):**

```json
{
    "success": true,
    "data": {
        "role": "student",
        "stats": {
            "enrolled_courses": 3,
            "completed_lessons": 15
        },
        "enrollments": [...]
    }
}
```

---

## Response Format

### Success Response

All successful API responses follow this format:

```json
{
    "success": true,
    "data": {
        // Response data here
    },
    "message": "Optional success message"
}
```

### Paginated Response

For paginated endpoints:

```json
{
    "success": true,
    "data": {
        "items": [...],
        "meta": {
            "current_page": 1,
            "total": 100,
            "per_page": 15,
            "last_page": 7
        },
        "links": {
            "first": "http://api.example.com/api/v1/courses?page=1",
            "last": "http://api.example.com/api/v1/courses?page=7",
            "prev": null,
            "next": "http://api.example.com/api/v1/courses?page=2"
        }
    }
}
```

---

## Error Handling

### Error Response Format

All error responses follow this format:

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### HTTP Status Codes

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthorized
-   `403` - Forbidden
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Server Error

### Common Error Responses

#### 401 Unauthorized

```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

#### 403 Forbidden

```json
{
    "success": false,
    "message": "Only instructors can access this resource"
}
```

#### 404 Not Found

```json
{
    "success": false,
    "message": "Course not found"
}
```

#### 422 Validation Error

```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "title": ["The title field is required."],
        "email": ["The email must be a valid email address."]
    }
}
```

---

## Rate Limiting

### Default Limits

-   **Unauthenticated**: 60 requests per minute
-   **Authenticated**: 120 requests per minute

### Rate Limit Headers

```
X-RateLimit-Limit: 120
X-RateLimit-Remaining: 115
X-RateLimit-Reset: 1640995200
```

### Rate Limit Exceeded Response

```json
{
    "success": false,
    "message": "Too Many Attempts."
}
```

HTTP Status: `429 Too Many Requests`

---

## API Versioning

### Version in URL

```
/api/v1/courses
/api/v2/courses
```

### Version in Headers (Alternative)

```
Accept: application/vnd.sproutlms.v1+json
```

### Current Version

-   **v1** - Initial API version (when implemented)

---

## Implementation Guide

### Step 1: Install Laravel Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### Step 2: Configure Sanctum

```php
// config/sanctum.php
'guard' => ['web'],
```

### Step 3: Add API Routes

```php
// routes/api.php
use App\Http\Controllers\Api\CourseController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/courses', [CourseController::class, 'index']);
        // ... other protected routes
    });
});
```

### Step 4: Create API Controllers

```php
// app/Http/Controllers/Api/CourseController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Course::where('is_published', true)->get();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
}
```

### Step 5: Update User Model

```php
// app/Models/User.php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // ...
}
```

---

## Testing API Endpoints

### Using cURL

```bash
# Login
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get Courses (with token)
curl -X GET http://localhost:8000/api/v1/courses \
  -H "Authorization: Bearer {token}"
```

### Using Postman

1. Create a new request
2. Set method (GET, POST, etc.)
3. Add URL: `http://localhost:8000/api/v1/endpoint`
4. For authenticated requests, add header:
    - Key: `Authorization`
    - Value: `Bearer {token}`

---

## Notes

-   This API documentation is a **reference for future implementation**
-   Current application uses web routes with Blade templates
-   API endpoints can be added incrementally as needed
-   Consider using Laravel Sanctum for API authentication
-   Follow RESTful conventions for endpoint naming
-   Always validate and sanitize input
-   Use proper HTTP status codes
-   Implement rate limiting
-   Document all endpoints

---

**Last Updated**: 2024-12-27
