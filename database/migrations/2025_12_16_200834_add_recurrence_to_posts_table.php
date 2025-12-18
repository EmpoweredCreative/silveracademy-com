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
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'biweekly', 'monthly'])
                ->default('none')
                ->after('button_url');
            $table->date('recurrence_end_date')->nullable()->after('recurrence_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['recurrence_type', 'recurrence_end_date']);
        });
    }
};
