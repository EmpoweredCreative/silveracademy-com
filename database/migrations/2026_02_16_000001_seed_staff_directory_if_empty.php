<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Seed staff_directory if empty. Runs as part of migrate so Forge only needs "migrate".
     */
    public function up(): void
    {
        if (! Schema::hasTable('staff_directory') || DB::table('staff_directory')->count() > 0) {
            return;
        }

        $rows = [
            ['Gila Ogle', 'Head of School', 'Leadership & Administration'],
            ['Allie Roberson', 'Administrative Assistant', 'Leadership & Administration'],
            ['Patty Metcalf', 'Operations & Facilities Manager', 'Leadership & Administration'],
            ['Niema Schertz', 'Special Events Coordinator', 'Leadership & Administration'],
            ['Brandy Hurley', 'MTSS Coordinator', 'Leadership & Administration'],
            ['Shiran Goldstein', 'Family & Student Engagement Specialist', 'Leadership & Administration'],
            ['Rabbi Moshe Yosef Gewirtz', 'Judaics Coordinator', 'Leadership & Administration'],
            ['Maggie Manofsky', 'General Studies Teacher', 'Kindergarten Team'],
            ['Litaf Elias', 'Hebrew & Judaics Teacher', 'Kindergarten Team'],
            ['Alla Fligelman', '1st & 2nd Grade Hebrew & Judaics Teacher', 'Lower School'],
            ['Jodi Pagni', '1st & 2nd Grade General Studies Teacher', 'Lower School'],
            ['Amy Cober', '3rd Grade General Studies & Upper School Math Teacher', 'Lower School & Upper School'],
            ['Menashe Elias', '3rd Grade & Upper School Hebrew & Judaics Teacher', 'Lower School & Upper School'],
            ['Susan Gaughan', 'K - 8 Science Teacher', 'Lower School & Upper School'],
            ['Rebecca Cortes', 'Humanities Teacher', 'Upper School'],
            ['Aviva Woodland', 'Hebrew & Judaics Interventionist', 'Student Support & Enrichment'],
            ['Bella Altman', 'ESL Teacher', 'Student Support & Enrichment'],
            ['Marina Cherepinsky', 'Director of Performing Arts & Interdisciplinary Curriculum', 'Student Support & Enrichment'],
            ['Tamara Reid', 'Ganeinu & Art Teacher', 'Student Support & Enrichment'],
            ['Rebecca Raffensperger', 'Jewish Nature & Environmental Teacher', 'Student Support & Enrichment'],
            ['Hannah Bailey', 'Physical Education Teacher', 'Student Support & Enrichment'],
            ['Alexander Presser', 'Computer Teacher', 'Student Support & Enrichment'],
            ['Jeff Hughes', 'School Security', 'Student Support & Enrichment'],
        ];

        $now = now();
        foreach ($rows as $i => $row) {
            DB::table('staff_directory')->insert([
                'name' => $row[0],
                'title' => $row[1],
                'department' => $row[2],
                'photo' => null,
                'sort_order' => $i,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('staff_directory')->truncate();
    }
};
