<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum column
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'instructor', 'admin') DEFAULT 'student'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove admin role, but first update any admin users to instructor
        DB::table('users')->where('role', 'admin')->update(['role' => 'instructor']);
        
        // Revert enum back to original
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'instructor') DEFAULT 'student'");
    }
};
