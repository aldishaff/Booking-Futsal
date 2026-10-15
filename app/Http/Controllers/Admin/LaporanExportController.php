<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;

class LaporanExportController extends Controller
{
    private function filteredBookings(Request $request)
    {
        $query = Booking::with(['user', 'lapangan']);

        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL
        |--------------------------------------------------------------------------
        */
        if ($request->start_date && $request->end_date) {

            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */
        if ($request->status) {

            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER LAPANGAN
        |--------------------------------------------------------------------------
        */
        if ($request->lapangan_id) {

            $query->where('lapangan_id', $request->lapangan_id);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------------------------
    */
    public function exportPdf(Request $request)
    {
        $bookings = $this->filteredBookings($request)
        ->latest()
        ->get();

        $totalIncome = $bookings
            ->where('status', 'confirmed')
            ->sum('total_harga');

        $pdf = Pdf::loadView(
            'admin.laporan.pdf',
            compact('bookings', 'totalIncome')
        );

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-booking.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */
    public function exportExcel(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */
        $sheet->mergeCells('A1:F1');

        $sheet->setCellValue(
            'A1',
            'LAPORAN BOOKING FUTSAL'
        );

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Customer');
        $sheet->setCellValue('C3', 'Lapangan');
        $sheet->setCellValue('D3', 'Tanggal');
        $sheet->setCellValue('E3', 'Status');
        $sheet->setCellValue('F3', 'Total');

        /*
        |--------------------------------------------------------------------------
        | STYLE HEADER
        |--------------------------------------------------------------------------
        */
        $sheet->getStyle('A3:F3')
            ->getFont()
            ->setBold(true);

        $bookings = $this->filteredBookings($request)
            ->latest()
            ->get();

        $row = 4;
        $no = 1;

        foreach ($bookings as $booking) {

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue(
                'B' . $row,
                $booking->user->username ?? '-'
            );

            $sheet->setCellValue(
                'C' . $row,
                $booking->lapangan->nama ?? '-'
            );

            $sheet->setCellValue(
                'D' . $row,
                $booking->tanggal
            );

            $sheet->setCellValue(
                'E' . $row,
                strtoupper($booking->status)
            );

            $sheet->setCellValue(
                'F' . $row,
                $booking->total_harga
            );

            $row++;
        }

        /*
        |--------------------------------------------------------------------------
        | AUTO SIZE
        |--------------------------------------------------------------------------
        */
        foreach (range('A', 'F') as $column) {

            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'laporan-booking.xlsx';

        $temp_file = tempnam(
            sys_get_temp_dir(),
            $fileName
        );

        $writer->save($temp_file);

        return response()->download(
            $temp_file,
            $fileName
        )->deleteFileAfterSend(true);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT CSV
    |--------------------------------------------------------------------------
    */
    public function exportCsv(Request $request)
    {
        $fileName = 'laporan-booking.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
                "attachment; filename={$fileName}",
        ];

        $callback = function () use ($request) {

            $file = fopen('php://output', 'w');

            /*
            |--------------------------------------------------------------------------
            | HEADER CSV
            |--------------------------------------------------------------------------
            */
            fputcsv($file, [
                'No',
                'Customer',
                'Lapangan',
                'Tanggal',
                'Status',
                'Total'
            ]);

            $bookings = $this->filteredBookings($request)
                ->latest()
                ->get();

            $no = 1;

            foreach ($bookings as $booking) {

                fputcsv($file, [

                    $no++,

                    $booking->user->username ?? '-',

                    $booking->lapangan->nama ?? '-',

                    $booking->tanggal,

                    strtoupper($booking->status),

                    $booking->total_harga
                ]);
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            200,
            $headers
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT WORD
    |--------------------------------------------------------------------------
    */
    public function exportWord(Request $request)
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */
        $section->addText(
            'Laporan Booking Futsal',
            ['bold' => true, 'size' => 16]
        );

        $section->addTextBreak(1);

        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */
        $table = $section->addTable();

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */
        $table->addRow();

        $table->addCell(1000)->addText('No');
        $table->addCell(3000)->addText('Customer');
        $table->addCell(3000)->addText('Lapangan');
        $table->addCell(2500)->addText('Tanggal');
        $table->addCell(2000)->addText('Status');
        $table->addCell(2500)->addText('Total');

        $bookings = $this->filteredBookings($request)
            ->latest()
            ->get();

        $no = 1;

        foreach ($bookings as $booking) {

            $table->addRow();

            $table->addCell(1000)->addText($no++);

            $table->addCell(3000)->addText(
                $booking->user->username ?? '-'
            );

            $table->addCell(3000)->addText(
                $booking->lapangan->nama ?? '-'
            );

            $table->addCell(2500)->addText(
                $booking->tanggal
            );

            $table->addCell(2000)->addText(
                strtoupper($booking->status)
            );

            $table->addCell(2500)->addText(
                'Rp ' . number_format($booking->total_harga)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE FILE
        |--------------------------------------------------------------------------
        */
        $fileName = 'laporan-booking.docx';

        $tempFile = tempnam(
            sys_get_temp_dir(),
            $fileName
        );

        $phpWord->save($tempFile, 'Word2007');

        return response()->download(
            $tempFile,
            $fileName
        )->deleteFileAfterSend(true);
    }
}
