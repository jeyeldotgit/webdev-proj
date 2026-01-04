<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function view(User $user, Course $course): bool
    {
        // Anyone can view published courses, or instructors can view their own
        return $course->is_published || $user->id === $course->instructor_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'instructor';
    }

    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->instructor_id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->id === $course->instructor_id;
    }
}
