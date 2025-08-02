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
        // Judul utama (baris 1)
        $styles = [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']], 
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '653361']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Info periode dan basis (baris 2)
            2 => [
                'font' => ['bold' => true, 'size' => 12], 
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'E8F4FD']]
            ],
            // Header tabel (baris 4)
            4 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'F2F2F2']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Kolom No (A) - center alignment
            'A' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            // Kolom Kategori (G) - center alignment  
            'G' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
            // Kolom Periode (H) - center alignment
            'H' => ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
        ];

        // Styling untuk baris data (mulai baris 5)
        $rowCount = count($this->analisis) + 4; // 4 baris header
        for ($i = 5; $i <= $rowCount; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(25);
            
            // Styling berdasarkan kategori
            $kategori = $this->analisis[$i-5]->kategori ?? '';
            $bgColor = 'FFFFFF'; // default white
            
            if ($kategori === 'A') {
                $bgColor = 'FFEBEE'; // Light red
            } elseif ($kategori === 'B') {
                $bgColor = 'FFF8E1'; // Light yellow
            } elseif ($kategori === 'C') {
                $bgColor = 'E8F5E8'; // Light green
            }
            
            $styles[$i] = [
                'font' => ['bold' => false, 'size' => 10],
                'alignment' => ['vertical' => 'center'],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => $bgColor]]
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 45,  // Nama Barang
            'C' => 15,  // Total Qty
            'D' => 18,  // Total Nilai
            'E' => 12,  // Persentase
            'F' => 12,  // Akumulasi
            'G' => 10,  // Kategori
            'H' => 12,  // Periode
            'I' => 20,  // Vendor
            'J' => 15,  // Harga Satuan
        ];
    }
}