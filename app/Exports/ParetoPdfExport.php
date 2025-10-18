<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ParetoPdfExport
{
    protected $analisis;
    protected $periode;
    protected $periodeInfo;
    protected $sortBy;
    protected $stats;
    protected $totalSumOfBasis;

    public function __construct($analisis, $periode = null, $periodeInfo = null, $sortBy = 'value', $stats = [], $totalSumOfBasis = 0)
    {
        $this->analisis = $analisis;
        $this->periode = $periode;
        $this->periodeInfo = $periodeInfo;
        $this->sortBy = $sortBy;
        $this->stats = $stats;
        $this->totalSumOfBasis = $totalSumOfBasis;
    }

    public function download($filename = null)
    {
        $filename = $filename ?: $this->generateFilename();
        try {
            $pdf = $this->createPdf();
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF Download Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function stream($filename = null)
    {
        $filename = $filename ?: $this->generateFilename();
        try {
            $pdf = $this->createPdf();
            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('PDF Stream Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createPdf()
    {
        ini_set('max_execution_time', 900);
        ini_set('memory_limit', '1024M');

        try {
            if (empty($this->analisis)) {
                throw new \Exception('Data analisis kosong');
            }

            $stats = array_merge([
                'total_barang' => 0,
                'total_qty_inventori' => 0,
                'total_nilai_inventori' => 0,
                'kategori_a_count' => 0,
                'kategori_b_count' => 0,
                'kategori_c_count' => 0,
                'kontribusi_a' => 0,
                'kontribusi_b' => 0,
                'kontribusi_c' => 0,
                'nilai_kategori_a' => 0,
                'nilai_kategori_b' => 0,
                'nilai_kategori_c' => 0,
            ], $this->stats);

            $pdf = Pdf::loadView('laporan.pareto_pdf', [
                'analisis' => $this->analisis,
                'periode' => $this->periode,
                'periodeInfo' => $this->periodeInfo,
                'sortBy' => $this->sortBy,
                'stats' => $stats,
                'totalSumOfBasis' => $this->totalSumOfBasis,
            ]);

            $pdf->setPaper('A4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'Arial',
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                    'dpi' => 96,
                    'enable_remote' => false,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'margin_left' => 10,
                    'margin_right' => 10,
                ]);

            return $pdf;
        } catch (\Exception $e) {
            Log::error('PDF Creation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function generateFilename()
    {
        $basis = $this->sortBy === 'quantity' ? 'Kuantitas' : 'Nilai';
        $periode = $this->periodeInfo ? $this->periodeInfo['nama_bulan'] : 'Semua';
        $date = now()->format('Y-m-d_H-i');

        return "ABC_Pareto_{$basis}_{$periode}_{$date}.pdf";
    }
}
