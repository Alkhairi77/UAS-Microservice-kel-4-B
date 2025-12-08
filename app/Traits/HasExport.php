<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

trait HasExport
{
    /**
     * Export data to CSV
     */
    protected function exportToCsv($data, $headers, $filename)
    {
        $csvHeaders = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data, $headers) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($file, $headers);
            
            // Write data
            foreach ($data as $row) {
                fputcsv($file, $this->formatRowForCsv($row));
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $csvHeaders);
    }

    /**
     * Export data to PDF
     */
    protected function exportToPdf($data, $viewName, $filename, $additionalData = [])
    {
        $pdf = Pdf::loadView($viewName, array_merge([
            'data' => $data,
            'generated_at' => now(),
            'user' => auth()->user()
        ], $additionalData));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Format row for CSV - to be implemented in each controller
     */
    abstract protected function formatRowForCsv($row);
}