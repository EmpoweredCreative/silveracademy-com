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
        Schema::table('student_access_codes', function (Blueprint $table) {
            $table->text('plain_code_encrypted')->nullable()->after('code_last4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_access_codes', function (Blueprint $table) {
            $table->dropColumn('plain_code_encrypted');
        });
    }
};
