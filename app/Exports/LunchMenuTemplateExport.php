<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LunchMenuTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Lunch Menus' => new LunchMenuMainSheet(),
            'Instructions' => new LunchMenuInstructionsSheet(),
        ];
    }
}

class LunchMenuMainSheet implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'Date',
            'Day of Week',
            'Menu Content',
        ];
    }

    public function array(): array
    {
        // Generate sample data for the next week
        $data = [];
        $monday = Carbon::now()->startOfWeek();
        
        $sampleMenus = [
            'Pizza Day! Cheese or pepperoni pizza with salad bar and fresh fruit.',
            'Grilled chicken sandwich with sweet potato fries and steamed vegetables.',
            'Spaghetti with meat sauce, garlic bread, and Caesar salad.',
            'Turkey and cheese wrap with soup of the day and carrot sticks.',
            'Fish sticks with mac and cheese, green beans, and applesauce.',
        ];
        
        for ($i = 0; $i < 5; $i++) {
            $date = $monday->copy()->addDays($i);
            $data[] = [
                $date->format('m/d/Y'),
                $date->format('l'),
                $sampleMenus[$i],
            ];
        }
        
        return $data;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class LunchMenuInstructionsSheet implements FromArray, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['Lunch Menu Import Instructions'],
            [''],
            ['Column Descriptions:'],
            ['Date', 'Required. The date for the lunch menu. Accepts various formats:'],
            ['', '  - MM/DD/YYYY (e.g., 01/15/2025)'],
            ['', '  - YYYY-MM-DD (e.g., 2025-01-15)'],
            ['', '  - M/D/YYYY (e.g., 1/15/2025)'],
            [''],
            ['Day of Week', 'Optional. Will be calculated automatically if not provided.'],
            [''],
            ['Menu Content', 'Required. The menu description for that day.'],
            ['', 'Can include multiple items separated by periods or commas.'],
            [''],
            ['Notes:'],
            ['- Weekend dates will be automatically skipped.'],
            ['- If a menu already exists for a date, it will be updated.'],
            ['- Empty rows will be skipped.'],
            ['- You can import a full month at once.'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]],
            14 => ['font' => ['bold' => true]],
        ];
    }
}

