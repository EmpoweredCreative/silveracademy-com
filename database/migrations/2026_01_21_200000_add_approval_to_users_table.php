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
        // Add approval columns if they don't exist
        if (!Schema::hasColumn('users', 'is_approved')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_approved')->default(true)->after('role');
            });
        }

        if (!Schema::hasColumn('users', 'approved_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('is_approved');
            });
        }

        if (!Schema::hasColumn('users', 'approved_by')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            });
            
            // Add foreign key separately to avoid issues
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
                });
            } catch (\Exception $e) {
                // Foreign key might already exist or fail - that's okay
            }
        }

        // Set existing users as approved
        DB::table('users')->where('is_approved', false)->orWhereNull('is_approved')->update(['is_approved' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'approved_by')) {
                try {
                    $table->dropForeign(['approved_by']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('users', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('users', 'is_approved')) {
                $table->dropColumn('is_approved');
            }
        });
    }
};
