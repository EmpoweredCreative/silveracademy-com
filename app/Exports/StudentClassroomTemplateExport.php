<?php

namespace App\Exports;

use App\Models\Grade;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentClassroomTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Students' => new StudentTemplateSheet(),
            'Reference - Grades' => new GradeReferenceSheet(),
            'Reference - Teachers' => new TeacherReferenceSheet(),
        ];
    }
}

class StudentTemplateSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Provide sample data rows
        return [
            ['John Smith', 'Kindergarten', 'Room A', 'teacher@silveracademypa.org'],
            ['Jane Doe', 'Kindergarten', 'Room A', 'teacher@silveracademypa.org'],
            ['Bob Johnson', 'Kindergarten', 'Room B', 'teacher2@silveracademypa.org'],
            ['', '', '', ''], // Empty row for user to fill in
        ];
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

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row styling
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A5F']
                ],
            ],
            // Sample data styling (rows 2-4)
            '2:4' => [
                'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // Student Name
            'B' => 25, // Grade
            'C' => 20, // Classroom Name
            'D' => 35, // Teacher Email
        ];
    }
}

class GradeReferenceSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        $grades = Grade::orderBy('sort_order')->get(['name']);
        return $grades->map(fn($grade) => [$grade->name])->toArray();
    }

    public function headings(): array
    {
        return ['Available Grades'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E7D32']
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
        ];
    }
}

class TeacherReferenceSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        $teachers = User::whereIn('role', [User::ROLE_TEACHER, User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])
            ->orderBy('name')
            ->get(['name', 'email']);
        
        return $teachers->map(fn($teacher) => [$teacher->name, $teacher->email])->toArray();
    }

    public function headings(): array
    {
        return ['Teacher Name', 'Teacher Email'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1565C0']
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 35,
        ];
    }
}
