<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;

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
        $pdf = $this->createPdf();
        return $pdf->download($filename);
    }

    public function stream($filename = null)
    {
        $filename = $filename ?: $this->generateFilename();
        $pdf = $this->createPdf();
        return $pdf->stream($filename);
    }

    private function createPdf()
    {
        $pdf = Pdf::loadView('laporan.pareto_pdf', [
            'analisis' => $this->analisis,
            'periode' => $this->periode,
            'periodeInfo' => $this->periodeInfo,
            'sortBy' => $this->sortBy,
            'stats' => $this->stats,
            'totalSumOfBasis' => $this->totalSumOfBasis,
        ]);

        // Setting sederhana untuk PDF
        $pdf->setPaper('A4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
            ]);

        return $pdf;
    }

    private function generateFilename()
    {
        $basis = $this->sortBy === 'quantity' ? 'Kuantitas' : 'Nilai';
        $periode = $this->periodeInfo ? $this->periodeInfo['nama_bulan'] : 'Semua';
        $date = now()->format('Y-m-d_H-i');
        
        return "ABC_Pareto_{$basis}_{$periode}_{$date}.pdf";
    }
}