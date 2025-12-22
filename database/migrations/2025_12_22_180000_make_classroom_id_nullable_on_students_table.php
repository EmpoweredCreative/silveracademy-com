<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['classroom_id']);
            
            // Make the column nullable
            $table->foreignId('classroom_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullOnDelete
            $table->foreign('classroom_id')
                ->references('id')
                ->on('classrooms')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['classroom_id']);
            
            // Make the column required again
            $table->foreignId('classroom_id')->nullable(false)->change();
            
            // Re-add the original foreign key constraint
            $table->foreign('classroom_id')
                ->references('id')
                ->on('classrooms')
                ->cascadeOnDelete();
        });
    }
};

