<?php

namespace App\Console\Commands;

use App\Models\StaffDirectory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportStaffDirectoryFromCsv extends Command
{
    protected $signature = 'staff-directory:import-from-csv
                            {path? : Path to CSV file (default: storage/app/staff_directory.csv)}
                            {--force : Replace existing entries}';

    protected $description = 'Import staff directory from CSV (one-time or with --force). Columns: Name, Title, Department.';

    public function handle(): int
    {
        $path = $this->argument('path') ?? storage_path('app/staff_directory.csv');

        if (! File::exists($path)) {
            $this->error("CSV not found: {$path}");
            return self::FAILURE;
        }

        $photoBasenames = $this->getPhotoBasenames();

        if ($this->option('force')) {
            StaffDirectory::query()->delete();
            $this->info('Cleared existing staff directory.');
        } elseif (StaffDirectory::exists()) {
            $this->warn('Staff directory already has entries. Use --force to replace, or edit in admin.');
            return self::SUCCESS;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error('Could not open CSV.');
            return self::FAILURE;
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            $this->error('Empty or invalid CSV.');
            return self::FAILURE;
        }

        $nameIdx = array_search('Name', $header);
        $titleIdx = array_search('Title', $header);
        $deptIdx = array_search('Department', $header);
        if ($nameIdx === false || $titleIdx === false || $deptIdx === false) {
            fclose($handle);
            $this->error('CSV must have columns: Name, Title, Department');
            return self::FAILURE;
        }

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $name = trim($row[$nameIdx] ?? '');
            if ($name === '') {
                continue;
            }
            $title = trim($row[$titleIdx] ?? '');
            $department = trim($row[$deptIdx] ?? '');
            $photoPath = $this->matchPhoto($name, $photoBasenames);

            StaffDirectory::create([
                'name' => $name,
                'title' => $title,
                'department' => $department,
                'photo' => $photoPath,
                'sort_order' => $imported,
            ]);
            $imported++;
        }
        fclose($handle);

        $this->info("Imported {$imported} staff directory entries.");
        return self::SUCCESS;
    }

    private function getPhotoBasenames(): array
    {
        $dir = public_path('img/graphics/staff');
        $basenames = [];
        if (! File::isDirectory($dir)) {
            return $basenames;
        }
        foreach (File::files($dir) as $file) {
            $filename = $file->getFilename();
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $basename = pathinfo($filename, PATHINFO_FILENAME);
                $basenames[$basename] = 'img/graphics/staff/'.$filename;
            }
        }
        return $basenames;
    }

    private function normalizeName(string $name): string
    {
        return preg_replace('/\s+/', ' ', strtolower(trim($name)));
    }

    private function matchPhoto(string $csvName, array $photoBasenames): ?string
    {
        $normalized = $this->normalizeName($csvName);
        foreach ($photoBasenames as $basename => $path) {
            if ($this->normalizeName($basename) === $normalized) {
                return '/'.$path;
            }
        }
        foreach ($photoBasenames as $basename => $path) {
            $fileNorm = $this->normalizeName($basename);
            if (str_contains($normalized, $fileNorm) || str_contains($fileNorm, $normalized)) {
                return '/'.$path;
            }
        }
        $csvWords = array_filter(explode(' ', $normalized));
        foreach ($photoBasenames as $basename => $path) {
            $fileNorm = $this->normalizeName($basename);
            $fileWords = array_filter(explode(' ', $fileNorm));
            $matches = array_intersect($csvWords, $fileWords);
            if (count($matches) >= min(2, count($csvWords), count($fileWords))) {
                return '/'.$path;
            }
        }
        return null;
    }
}
