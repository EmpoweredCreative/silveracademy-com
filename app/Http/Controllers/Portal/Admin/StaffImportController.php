<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StaffImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StaffImportController extends Controller
{
    /**
     * Display the staff import form.
     */
    public function showImport(): Response
    {
        return Inertia::render('Portal/Admin/Staff/Import');
    }

    /**
     * Process the staff import.
     * Note: Passwords are NOT generated here - they are generated when welcome emails are sent.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240',
        ]);

        try {
            $import = new StaffImport();
            Excel::import($import, $request->file('file'));

            $results = $import->getResults();

            if (count($results['errors']) > 0) {
                return redirect()->route('admin.staff.index')->with([
                    'warning' => "Import completed with some issues. {$results['imported']} staff imported, {$results['skipped']} skipped.",
                    'import_errors' => $results['errors'],
                ]);
            }

            return redirect()->route('admin.staff.index')->with(
                'success',
                "{$results['imported']} staff member(s) imported successfully. Send welcome emails to provide login credentials."
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download the import template.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $headers = ['name', 'email', 'role'];
        $example = ['John Smith', 'jsmith@silveracademypa.org', 'teacher'];

        $csv = implode(',', $headers) . "\n" . implode(',', $example);

        $filename = 'staff_import_template.csv';
        $path = storage_path('app/' . $filename);
        file_put_contents($path, $csv);

        return response()->download($path, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }
}
