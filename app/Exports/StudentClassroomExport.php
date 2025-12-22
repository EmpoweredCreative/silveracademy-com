<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentClassroomExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected ?int $gradeId;

    public function __construct(?int $gradeId = null)
    {
        $this->gradeId = $gradeId;
    }

    public function query()
    {
        $query = Student::query()
            ->with(['grade', 'classroom.teacher'])
            ->orderBy('name');

        if ($this->gradeId) {
            $query->where('grade_id', $this->gradeId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Grade',
            'Classroom Name',
            'Teacher Email',
        ];
    }

    public function map($student): array
    {
        return [
            $student->name,
            $student->grade?->name ?? 'Not Assigned',
            $student->classroom?->name ?? 'Not Assigned',
            $student->classroom?->teacher?->email ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A5F']
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 20,
            'D' => 35,
        ];
    }
}
