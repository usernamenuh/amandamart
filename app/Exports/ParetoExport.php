<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParetoExport implements FromView, WithStyles, WithColumnWidths
{
    protected $analisis;
    protected $periode;
    protected $periodeInfo;
    protected $sortBy;
    protected $stats;

    public function __construct($analisis, $periode = null, $periodeInfo = null, $sortBy = 'value', $stats = [])
    {
        $this->analisis = $analisis;
        $this->periode = $periode;
        $this->periodeInfo = $periodeInfo;
        $this->sortBy = $sortBy;
        $this->stats = $stats;
    }

    public function view(): View
    {
        // Increase resource limits untuk Excel export
        ini_set('max_execution_time', 900);
        ini_set('memory_limit', '1024M');

        return view('laporan.pareto_export', [
            'analisis' => $this->analisis,
            'periode' => $this->periode,
            'periodeInfo' => $this->periodeInfo,
            'sortBy' => $this->sortBy,
            'stats' => $this->stats,
            'exportDate' => now()->format('d/m/Y H:i:s')
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '2c3e50']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'E8F4FD']]
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'F2F2F2']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
        ];

        // Styling untuk baris data dengan kategori warna
        $rowCount = count($this->analisis) + 4;
        for ($i = 5; $i <= $rowCount; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
            
            $kategori = $this->analisis[$i - 5]->kategori ?? '';
            $bgColor = 'FFFFFF';

            if ($kategori === 'A') {
                $bgColor = 'FFEBEE';
            } elseif ($kategori === 'B') {
                $bgColor = 'FFF8E1';
            } elseif ($kategori === 'C') {
                $bgColor = 'E8F5E8';
            }

            $styles[$i] = [
                'font' => ['size' => 9],
                'alignment' => ['vertical' => 'center'],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => $bgColor]]
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 40,
            'C' => 12,
            'D' => 15,
            'E' => 10,
            'F' => 10,
            'G' => 8,
            'H' => 10,
            'I' => 18,
            'J' => 12,
        ];
    }
}
