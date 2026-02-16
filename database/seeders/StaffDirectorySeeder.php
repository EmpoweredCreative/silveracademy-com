<?php

namespace Database\Seeders;

use App\Models\StaffDirectory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StaffDirectorySeeder extends Seeder
{
    /**
     * Seed the staff_directory table. Runs only when the table is empty.
     * Photos are matched from public/img/graphics/staff/ by name.
     */
    public function run(): void
    {
        if (StaffDirectory::exists()) {
            return;
        }

        $photoPaths = $this->getPhotoPathsByName();

        $staff = [
            ['name' => 'Gila Ogle', 'title' => 'Head of School', 'department' => 'Leadership & Administration'],
            ['name' => 'Allie Roberson', 'title' => 'Administrative Assistant', 'department' => 'Leadership & Administration'],
            ['name' => 'Patty Metcalf', 'title' => 'Operations & Facilities Manager', 'department' => 'Leadership & Administration'],
            ['name' => 'Niema Schertz', 'title' => 'Special Events Coordinator', 'department' => 'Leadership & Administration'],
            ['name' => 'Brandy Hurley', 'title' => 'MTSS Coordinator', 'department' => 'Leadership & Administration'],
            ['name' => 'Shiran Goldstein', 'title' => 'Family & Student Engagement Specialist', 'department' => 'Leadership & Administration'],
            ['name' => 'Rabbi Moshe Yosef Gewirtz', 'title' => 'Judaics Coordinator', 'department' => 'Leadership & Administration'],
            ['name' => 'Maggie Manofsky', 'title' => 'General Studies Teacher', 'department' => 'Kindergarten Team'],
            ['name' => 'Litaf Elias', 'title' => 'Hebrew & Judaics Teacher', 'department' => 'Kindergarten Team'],
            ['name' => 'Alla Fligelman', 'title' => '1st & 2nd Grade Hebrew & Judaics Teacher', 'department' => 'Lower School'],
            ['name' => 'Jodi Pagni', 'title' => '1st & 2nd Grade General Studies Teacher', 'department' => 'Lower School'],
            ['name' => 'Amy Cober', 'title' => '3rd Grade General Studies & Upper School Math Teacher', 'department' => 'Lower School & Upper School'],
            ['name' => 'Menashe Elias', 'title' => '3rd Grade & Upper School Hebrew & Judaics Teacher', 'department' => 'Lower School & Upper School'],
            ['name' => 'Susan Gaughan', 'title' => 'K - 8 Science Teacher', 'department' => 'Lower School & Upper School'],
            ['name' => 'Rebecca Cortes', 'title' => 'Humanities Teacher', 'department' => 'Upper School'],
            ['name' => 'Aviva Woodland', 'title' => 'Hebrew & Judaics Interventionist', 'department' => 'Student Support & Enrichment'],
            ['name' => 'Bella Altman', 'title' => 'ESL Teacher', 'department' => 'Student Support & Enrichment'],
            ['name' => 'Marina Cherepinsky', 'title' => 'Director of Performing Arts & Interdisciplinary Curriculum', 'department' => 'Student Support & Enrichment'],
            ['name' => 'Tamara Reid', 'title' => 'Ganeinu & Art Teacher', 'department' => 'Student Support & Enrichment'],
            ['name' => 'Rebecca Raffensperger', 'title' => 'Jewish Nature & Environmental Teacher', 'department' => 'Student Support & Enrichment'],
            ['name' => 'Hannah Bailey', 'title' => 'Physical Education Teacher', 'department' => 'Student Support & Enrichment'],
            ['name' => 'Alexander Presser', 'title' => 'Computer Teacher', 'department' => 'Student Support & Enrichment'],
            ['name' => 'Jeff Hughes', 'title' => 'School Security', 'department' => 'Student Support & Enrichment'],
        ];

        foreach ($staff as $index => $row) {
            StaffDirectory::create([
                'name' => $row['name'],
                'title' => $row['title'],
                'department' => $row['department'],
                'photo' => $this->matchPhoto($row['name'], $photoPaths),
                'sort_order' => $index,
            ]);
        }
    }

    private function getPhotoPathsByName(): array
    {
        $dir = public_path('img/graphics/staff');
        $out = [];
        if (! File::isDirectory($dir)) {
            return $out;
        }
        foreach (File::files($dir) as $file) {
            $filename = $file->getFilename();
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $basename = pathinfo($filename, PATHINFO_FILENAME);
                $out[$basename] = '/img/graphics/staff/'.$filename;
            }
        }
        return $out;
    }

    private function normalize(string $s): string
    {
        return preg_replace('/\s+/', ' ', strtolower(trim($s)));
    }

    private function matchPhoto(string $name, array $photoPaths): ?string
    {
        $n = $this->normalize($name);
        foreach ($photoPaths as $basename => $path) {
            if ($this->normalize($basename) === $n) {
                return $path;
            }
        }
        foreach ($photoPaths as $basename => $path) {
            $bn = $this->normalize($basename);
            if (str_contains($n, $bn) || str_contains($bn, $n)) {
                return $path;
            }
        }
        $words = array_filter(explode(' ', $n));
        foreach ($photoPaths as $basename => $path) {
            $bn = $this->normalize($basename);
            $bw = array_filter(explode(' ', $bn));
            if (count(array_intersect($words, $bw)) >= min(2, count($words), count($bw))) {
                return $path;
            }
        }
        return null;
    }
}
