<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Converts lunch_menus from weekly (week_start) to daily (menu_date).
     */
    public function up(): void
    {
        // Check if week_start column exists (it might already be renamed)
        $columns = Schema::getColumnListing('lunch_menus');
        
        if (in_array('week_start', $columns)) {
            // Drop unique constraint on week_start if it exists
            try {
                Schema::table('lunch_menus', function (Blueprint $table) {
                    $table->dropUnique(['week_start']);
                });
            } catch (\Exception $e) {
                // Index might not exist, ignore
            }

            // Rename column
            Schema::table('lunch_menus', function (Blueprint $table) {
                $table->renameColumn('week_start', 'menu_date');
            });
        }

        // Add unique constraint on menu_date if it doesn't exist
        try {
            Schema::table('lunch_menus', function (Blueprint $table) {
                $table->unique('menu_date');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = Schema::getColumnListing('lunch_menus');
        
        if (in_array('menu_date', $columns)) {
            try {
                Schema::table('lunch_menus', function (Blueprint $table) {
                    $table->dropUnique(['menu_date']);
                });
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }

            Schema::table('lunch_menus', function (Blueprint $table) {
                $table->renameColumn('menu_date', 'week_start');
            });

            Schema::table('lunch_menus', function (Blueprint $table) {
                $table->unique('week_start');
            });
        }
    }
};
