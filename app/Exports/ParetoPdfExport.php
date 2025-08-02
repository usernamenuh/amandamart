<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
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
        
        $pdf = Pdf::loadView('laporan.pareto_pdf', [
            'analisis' => $this->analisis,
            'periode' => $this->periode,
            'periodeInfo' => $this->periodeInfo,
            'sortBy' => $this->sortBy,
            'stats' => $this->stats,
            'totalSumOfBasis' => $this->totalSumOfBasis,
            // Hapus $exportDate karena kita pakai now() langsung di view
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true
        ]);

        return $pdf->download($filename);
    }

    public function stream($filename = null)
    {
        $filename = $filename ?: $this->generateFilename();
        
        $pdf = Pdf::loadView('laporan.pareto_pdf', [
            'analisis' => $this->analisis,
            'periode' => $this->periode,
            'periodeInfo' => $this->periodeInfo,
            'sortBy' => $this->sortBy,
            'stats' => $this->stats,
            'totalSumOfBasis' => $this->totalSumOfBasis,
            // Hapus $exportDate karena kita pakai now() langsung di view
        ]);

        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true
        ]);

        return $pdf->stream($filename);
    }

    private function generateFilename()
    {
        $basisText = $this->sortBy === 'quantity' ? 'Kuantitas' : 'Nilai';
        $periodeText = $this->periodeInfo ? $this->periodeInfo['nama_bulan'] : 'Semua_Periode';
        $timestamp = now()->format('Y-m-d_H-i-s');
        
        return "Analisis_ABC_Pareto_{$basisText}_{$periodeText}_{$timestamp}.pdf";
    }
}