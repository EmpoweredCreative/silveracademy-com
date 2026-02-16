<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Normalize a name for matching: trim, lowercase, single spaces.
     */
    private function normalizeName(string $name): string
    {
        return preg_replace('/\s+/', ' ', strtolower(trim($name)));
    }

    /**
     * Find the best matching staff photo filename for a given CSV name.
     * Tries exact match, then normalized match, then partial/word-based match.
     */
    private function matchStaffPhoto(string $csvName, array $photoBasenames): ?string
    {
        $normalized = $this->normalizeName($csvName);

        foreach ($photoBasenames as $basename => $filename) {
            $fileNormalized = $this->normalizeName($basename);
            if ($fileNormalized === $normalized) {
                return $filename;
            }
        }

        foreach ($photoBasenames as $basename => $filename) {
            $fileNormalized = $this->normalizeName($basename);
            if (str_contains($normalized, $fileNormalized) || str_contains($fileNormalized, $normalized)) {
                return $filename;
            }
        }

        $csvWords = array_filter(explode(' ', $normalized));
        foreach ($photoBasenames as $basename => $filename) {
            $fileNormalized = $this->normalizeName($basename);
            $fileWords = array_filter(explode(' ', $fileNormalized));
            $matches = array_intersect($csvWords, $fileWords);
            if (count($matches) >= min(2, count($csvWords), count($fileWords))) {
                return $filename;
            }
        }

        return null;
    }

    /**
     * Load staff directory from CSV and match photos from img/graphics/staff.
     */
    private function loadStaffDirectory(): array
    {
        $path = storage_path('app/staff_directory.csv');
        if (! File::exists($path)) {
            return [];
        }

        $photoDir = public_path('img/graphics/staff');
        $photoBasenames = [];
        if (File::isDirectory($photoDir)) {
            foreach (File::files($photoDir) as $file) {
                $filename = $file->getFilename();
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                    $basename = pathinfo($filename, PATHINFO_FILENAME);
                    $photoBasenames[$basename] = 'img/graphics/staff/'.$filename;
                }
            }
        }

        $rows = [];
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return [];
        }

        $nameIdx = array_search('Name', $header);
        $titleIdx = array_search('Title', $header);
        $deptIdx = array_search('Department', $header);

        if ($nameIdx === false || $titleIdx === false || $deptIdx === false) {
            fclose($handle);
            return [];
        }

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim($row[$nameIdx] ?? '');
            if ($name === '') {
                continue;
            }
            $title = trim($row[$titleIdx] ?? '');
            $department = trim($row[$deptIdx] ?? '');
            $photoFile = $this->matchStaffPhoto($name, $photoBasenames);
            $rows[] = [
                'name' => $name,
                'title' => $title,
                'department' => $department,
                'photo' => $photoFile ? '/'.$photoFile : null,
            ];
        }
        fclose($handle);

        return $rows;
    }
    public function index(): Response
    {
        return Inertia::render('Marketing/Home');
    }

    public function about(): Response
    {
        return Inertia::render('Marketing/About');
    }

    public function whySilverAcademy(): Response
    {
        return Inertia::render('Marketing/WhySilverAcademy');
    }

    public function ourCommunity(): Response
    {
        return Inertia::render('Marketing/OurCommunity');
    }

    public function admissions(): Response
    {
        return Inertia::render('Marketing/Admissions');
    }

    public function services(): Response
    {
        return Inertia::render('Marketing/Services');
    }

    public function contact(): Response
    {
        return Inertia::render('Marketing/Contact');
    }

    public function ganeinu(): Response
    {
        return Inertia::render('Marketing/Programs/Ganeinu');
    }

    public function kindergarten(): Response
    {
        return Inertia::render('Marketing/Programs/Kindergarten');
    }

    public function lowerSchool(): Response
    {
        return Inertia::render('Marketing/Programs/LowerSchool');
    }

    public function upperSchool(): Response
    {
        return Inertia::render('Marketing/Programs/UpperSchool');
    }

    public function afterSchool(): Response
    {
        return Inertia::render('Marketing/Programs/AfterSchool');
    }

    public function parentCircle(): Response
    {
        return Inertia::render('Marketing/Programs/ParentCircle');
    }

    public function getInvolved(): Response
    {
        return Inertia::render('Marketing/GetInvolved/Index');
    }

    public function donate(): Response
    {
        return Inertia::render('Marketing/GetInvolved/Donate');
    }

    public function eitcCorporate(): Response
    {
        return Inertia::render('Marketing/GetInvolved/EITCCorporate');
    }

    public function eitcIndividual(): Response
    {
        return Inertia::render('Marketing/GetInvolved/EITCIndividual');
    }

    public function lifeAndLegacy(): Response
    {
        return Inertia::render('Marketing/GetInvolved/LifeAndLegacy');
    }

    public function fundraisers(): Response
    {
        return Inertia::render('Marketing/GetInvolved/Fundraisers');
    }

    public function staff(): Response
    {
        $staff = $this->loadStaffDirectory();

        return Inertia::render('Marketing/Staff', [
            'staff' => $staff,
        ]);
    }
}

