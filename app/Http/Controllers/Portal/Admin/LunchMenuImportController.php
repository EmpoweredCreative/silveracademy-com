<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Imports\LunchMenuImport;
use App\Exports\LunchMenuExport;
use App\Exports\LunchMenuTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use Inertia\Response;

class LunchMenuImportController extends Controller
{
    /**
     * Display the import page.
     */
    public function showImport(): Response
    {
        return Inertia::render('Portal/Lunch/Import');
    }

    /**
     * Process the Excel import.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'], // 5MB max
        ]);

        $import = new LunchMenuImport(auth()->id());
        
        try {
            Excel::import($import, $request->file('file'));
            
            $stats = $import->getStats();
            $errors = $import->getErrors();
            
            return back()->with([
                'success' => true,
                'message' => "Import completed! Created: {$stats['created']}, Updated: {$stats['updated']}, Skipped: {$stats['skipped']}.",
                'stats' => $stats,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'errors' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Download the import template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new LunchMenuTemplateExport, 'lunch-menu-template.xlsx');
    }

    /**
     * Export current menus.
     */
    public function export(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $monthName = date('F', mktime(0, 0, 0, $month, 1));
        $filename = "lunch-menus-{$monthName}-{$year}.xlsx";
        
        return Excel::download(new LunchMenuExport($year, $month), $filename);
    }
}

