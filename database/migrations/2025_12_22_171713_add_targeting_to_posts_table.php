<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Add target grade for grade-specific staff announcements
            $table->foreignId('target_grade_id')->nullable()->after('audience')
                ->constrained('grades')->onDelete('set null');
            
            // Add target teacher for individual teacher announcements
            $table->foreignId('target_teacher_id')->nullable()->after('target_grade_id')
                ->constrained('users')->onDelete('set null');
        });

        // Update the audience enum to include new options
        // SQLite doesn't support ALTER COLUMN, so we handle this differently
        // For MySQL/PostgreSQL, we'd use:
        // DB::statement("ALTER TABLE posts MODIFY COLUMN audience ENUM('all', 'teachers_only', 'grade_teachers', 'specific_teacher') DEFAULT 'all'");
        
        // For SQLite (local dev), the enum is stored as text, so no change needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['target_grade_id']);
            $table->dropForeign(['target_teacher_id']);
            $table->dropColumn(['target_grade_id', 'target_teacher_id']);
        });
    }
};
