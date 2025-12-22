<?php

namespace App\Exports;

use App\Models\LunchMenu;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LunchMenuExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected ?int $year = null;
    protected ?int $month = null;

    public function __construct(?int $year = null, ?int $month = null)
    {
        $this->year = $year ?? now()->year;
        $this->month = $month ?? now()->month;
    }

    public function collection()
    {
        return LunchMenu::forMonth($this->year, $this->month)->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Day of Week',
            'Menu Content',
        ];
    }

    public function map($menu): array
    {
        return [
            $menu->menu_date->format('m/d/Y'),
            $menu->menu_date->format('l'),
            $menu->content,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

