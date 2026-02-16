<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Set photo paths for staff_directory by matching names to files in public/img/graphics/staff/.
     */
    public function up(): void
    {
        $dir = public_path('img/graphics/staff');
        if (! File::isDirectory($dir)) {
            return;
        }

        $photoByBasename = [];
        foreach (File::files($dir) as $file) {
            $filename = $file->getFilename();
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $basename = pathinfo($filename, PATHINFO_FILENAME);
                $photoByBasename[$basename] = '/img/graphics/staff/'.$filename;
            }
        }

        if (empty($photoByBasename)) {
            return;
        }

        $rows = DB::table('staff_directory')->get();
        foreach ($rows as $row) {
            $photo = $this->matchPhoto($row->name, $photoByBasename);
            if ($photo !== null) {
                DB::table('staff_directory')->where('id', $row->id)->update(['photo' => $photo]);
            }
        }
    }

    public function down(): void
    {
        DB::table('staff_directory')->update(['photo' => null]);
    }

    private function normalize(string $s): string
    {
        return preg_replace('/\s+/', ' ', strtolower(trim($s)));
    }

    private function matchPhoto(string $name, array $photoByBasename): ?string
    {
        $n = $this->normalize($name);
        foreach ($photoByBasename as $basename => $path) {
            if ($this->normalize($basename) === $n) {
                return $path;
            }
        }
        foreach ($photoByBasename as $basename => $path) {
            $bn = $this->normalize($basename);
            if (str_contains($n, $bn) || str_contains($bn, $n)) {
                return $path;
            }
        }
        $words = array_filter(explode(' ', $n));
        foreach ($photoByBasename as $basename => $path) {
            $bn = $this->normalize($basename);
            $bw = array_filter(explode(' ', $bn));
            if (count(array_intersect($words, $bw)) >= min(2, count($words), count($bw))) {
                return $path;
            }
        }
        return null;
    }
};
