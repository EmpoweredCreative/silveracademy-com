<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentClassroomImport;
use App\Exports\StudentClassroomTemplateExport;
use App\Exports\StudentClassroomExport;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class StudentImportController extends Controller
{
    /**
     * Show the import page.
     */
    public function showImport(): Response
    {
        $grades = Grade::orderBy('sort_order')->get(['id', 'name']);

        return Inertia::render('Portal/Admin/Grades/Import', [
            'grades' => $grades,
        ]);
    }

    /**
     * Download the Excel template.
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new StudentClassroomTemplateExport(),
            'student-classroom-template.xlsx'
        );
    }

    /**
     * Export current student assignments.
     */
    public function export(Request $request)
    {
        $gradeId = $request->get('grade_id');
        $filename = $gradeId 
            ? 'students-grade-' . $gradeId . '-' . now()->format('Y-m-d') . '.xlsx'
            : 'all-students-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new StudentClassroomExport($gradeId),
            $filename
        );
    }

    /**
     * Import students from Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $import = new StudentClassroomImport();
            
            Excel::import($import, $request->file('file'));
            
            $stats = $import->getStats();
            $errors = $import->getErrors();
            
            $message = "Import completed: {$stats['created']} students created, {$stats['updated']} updated";
            if ($stats['skipped'] > 0) {
                $message .= ", {$stats['skipped']} skipped";
            }

            return redirect()->route('admin.grades.index')
                ->with('success', $message)
                ->with('importErrors', $errors);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}

