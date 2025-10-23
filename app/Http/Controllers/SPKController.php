<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SPKController extends Controller
{
    /**
     * Display SPK dashboard with ABC analysis and recommendations
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode', null);
        $sortBy = $request->get('sort_by', 'value');

        // Get ABC analysis data
        $analisisData = $this->getABCAnalysis($periode);
        
        // Get recommendations for each item
        $recommendations = $this->generateRecommendations($analisisData);
        
        // Get summary statistics
        $summary = $this->getSummaryStatistics($analisisData);

        // Get available periods
        $availablePeriodes = $this->getAvailablePeriodes();

        return view('spk.index', [
            'analisisData' => $analisisData,
            'recommendations' => $recommendations,
            'summary' => $summary,
            'periode' => $periode,
            'sortBy' => $sortBy,
            'availablePeriodes' => $availablePeriodes,
        ]);
    }

    /**
     * Get ABC analysis data using stock-based categorization
     * Changed from value-based to stock-based categorization (A = low stock, C = high stock)
     */
    private function getABCAnalysis($periode)
    {
        // Query barang dengan stok > 0
        $query = Barang::select([
            'id',
            'nama_item',
            'no',
            'qty',
            'cost_price',
            'unit_price',
            'vendor',
            'periode'
        ])->where('qty', '>', 0);

        // Filter berdasarkan periode jika dipilih
        if ($periode && $periode > 0) {
            $query->where('periode', $periode);
        }

        $barangs = $query->get();

        // Hitung nilai total untuk setiap barang
        $analisis = $barangs->map(function ($barang) {
            $harga = $barang->unit_price > 0 ? $barang->unit_price : $barang->cost_price;
            $nilai_total = $barang->qty * $harga;

            return (object) [
                'barang_id' => $barang->id,
                'kode' => $barang->no ?? '-',
                'nama' => $barang->nama_item,
                'stok' => $barang->qty,
                'harga_satuan' => $harga,
                'nilai_total' => $nilai_total,
                'vendor' => $barang->vendor,
                'periode' => $barang->periode,
            ];
        });

        // Filter barang yang memiliki nilai > 0
        $analisis = $analisis->filter(function ($item) {
            return $item->nilai_total > 0;
        });

        // A: 10-30 pcs, B: 30-70 pcs, C: 70+ pcs
        $analisis = $analisis->map(function ($item) {
            if ($item->stok >= 10 && $item->stok <= 30) {
                $item->kategori = 'A'; // Stok sedikit, laku cepat
            } elseif ($item->stok > 30 && $item->stok <= 70) {
                $item->kategori = 'B'; // Stok sedang, penjualan stabil
            } elseif ($item->stok > 70) {
                $item->kategori = 'C'; // Stok banyak, penjualan lambat
            } else {
                // Items dengan stok < 10 dikategorikan sebagai A (kritis)
                $item->kategori = 'A';
            }
            return $item;
        });

        return $analisis->sortBy('stok')->values();
    }

    /**
     * Get ABC analysis data for export with balanced distribution (100 items total)
     * New method to get 100 items with ~33-34 items from each category (A, B, C)
     */
    private function getABCAnalysisForExport($periode)
    {
        // Get all ABC analysis data first
        $allAnalisis = $this->getABCAnalysis($periode);

        // Separate by category
        $kategoriA = $allAnalisis->filter(fn($item) => $item->kategori === 'A')->values();
        $kategoriB = $allAnalisis->filter(fn($item) => $item->kategori === 'B')->values();
        $kategoriC = $allAnalisis->filter(fn($item) => $item->kategori === 'C')->values();

        // Calculate items per category (100 items total, distributed evenly)
        $itemsPerCategory = intdiv(100, 3); // 33 items per category
        $remainder = 100 % 3; // 1 remaining item

        // Take items from each category
        $exportData = collect();
        
        // Add items from category A
        $exportData = $exportData->merge($kategoriA->take($itemsPerCategory + ($remainder > 0 ? 1 : 0)));
        
        // Add items from category B
        $exportData = $exportData->merge($kategoriB->take($itemsPerCategory + ($remainder > 1 ? 1 : 0)));
        
        // Add items from category C
        $exportData = $exportData->merge($kategoriC->take($itemsPerCategory));

        // Sort by stock level
        return $exportData->sortBy('stok')->values();
    }

    /**
     * Generate recommendations based on ABC analysis and stock level
     * Updated to work with stock-based categorization
     */
    private function generateRecommendations($analisisData)
    {
        $recommendations = [];

        foreach ($analisisData as $item) {
            $kategori = $item->kategori;
            $stok = $item->stok;

            // Calculate average monthly usage from transactions
            $avgUsage = $this->calculateAverageUsage($item->barang_id);

            // Calculate safety stock and reorder point
            $safetyStock = $this->calculateSafetyStock($kategori, $avgUsage);
            $reorderPoint = $this->calculateReorderPoint($kategori, $avgUsage);

            // Generate recommendation based on category and stock level
            $rekomendasi = $this->getRecommendationText($kategori, $stok, $safetyStock, $reorderPoint);

            $recommendations[$item->barang_id] = [
                'kategori' => $kategori,
                'rekomendasi' => $rekomendasi,
                'stok_saat_ini' => $stok,
                'rata_rata_pemakaian' => round($avgUsage, 2),
                'safety_stock' => $safetyStock,
                'reorder_point' => $reorderPoint,
                'status' => $this->getStockStatus($stok, $safetyStock, $reorderPoint),
            ];
        }

        return $recommendations;
    }

    /**
     * Calculate average monthly usage from transactions
     */
    private function calculateAverageUsage($barangId)
    {
        // Get transactions for last 3 months
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        $totalQty = Transaksi::where('barang_id', $barangId)
            ->where('jenis_transaksi', 'keluar')
            ->where('tanggal_transaksi', '>=', $threeMonthsAgo)
            ->sum('qty');

        // Average per month
        return $totalQty > 0 ? round($totalQty / 3, 2) : 0;
    }

    /**
     * Get recommendation text based on stock range
     * Updated to use specific stock ranges instead of calculated safety stock
     */
    private function getRecommendationText($kategori, $stok, $safetyStock, $reorderPoint)
    {
        if ($stok >= 1 && $stok <= 15) {
            return 'Pengadaan Segera';
        } elseif ($stok > 15 && $stok <= 30) {
            return 'Pertahankan Stok';
        } elseif ($stok > 30 && $stok <= 70) {
            return 'Pantau Penjualan';
        } elseif ($stok > 70) {
            return 'Pengadaan Bulan Cukup';
        }
        
        return 'Pantau Penjualan';
    }

    /**
     * Get stock status indicator
     */
    private function getStockStatus($stok, $safetyStock, $reorderPoint)
    {
        if ($stok < $safetyStock) {
            return 'critical'; // Kritis
        } elseif ($stok < $reorderPoint) {
            return 'warning'; // Peringatan
        } else {
            return 'safe'; // Aman
        }
    }

    /**
     * Calculate safety stock based on category
     */
    private function calculateSafetyStock($kategori, $avgUsage)
    {
        $multiplier = match($kategori) {
            'A' => 2.0,  // 2 months for category A
            'B' => 1.5,  // 1.5 months for category B
            'C' => 1.0,  // 1 month for category C
            default => 1.0,
        };

        return (int) round($avgUsage * $multiplier);
    }

    /**
     * Calculate reorder point based on category
     */
    private function calculateReorderPoint($kategori, $avgUsage)
    {
        $multiplier = match($kategori) {
            'A' => 1.5,  // 1.5 months for category A
            'B' => 1.0,  // 1 month for category B
            'C' => 0.5,  // 0.5 months for category C
            default => 0.5,
        };

        return (int) round($avgUsage * $multiplier);
    }

    /**
     * Get summary statistics
     */
    /**
     * Get summary statistics
     */
    /**
     * Get summary statistics
     */
    /**
     * Get summary statistics
     */
    private function getSummaryStatistics($analisisData)
    {
        if (empty($analisisData) || count($analisisData) === 0) {
            return [
                'total_items' => 0,
                'kategori_a' => 0,
                'kategori_b' => 0,
                'kategori_c' => 0,
                'total_value' => 0,
                'total_quantity' => 0,
                'critical_items' => 0,
                'warning_items' => 0,
                'is_empty' => true, // Flag untuk view
            ];
        }

        $summary = [
            'total_items' => count($analisisData),
            'kategori_a' => 0,
            'kategori_b' => 0,
            'kategori_c' => 0,
            'total_value' => 0,
            'total_quantity' => 0,
            'critical_items' => 0,
            'warning_items' => 0,
            'is_empty' => false,
        ];

        foreach ($analisisData as $item) {
            $summary['total_value'] += $item->nilai_total;
            $summary['total_quantity'] += $item->stok;
            
            if ($item->kategori === 'A') {
                $summary['kategori_a']++;
            } elseif ($item->kategori === 'B') {
                $summary['kategori_b']++;
            } else {
                $summary['kategori_c']++;
            }
        }

        return $summary;
    }



    /**
     * Get available periods
     */
    private function getAvailablePeriodes()
    {
        return Barang::select('periode')
            ->whereNotNull('periode')
            ->where('periode', '>', 0)
            ->where('qty', '>', 0)
            ->distinct()
            ->orderBy('periode')
            ->pluck('periode')
            ->map(function ($p) {
                $bulanNames = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                return [
                    'value' => $p,
                    'name' => $bulanNames[$p] ?? 'Periode ' . $p
                ];
            });
    }

    /**
     * Export SPK to PDF with balanced distribution of 100 items
     * Modified to export only 100 items with balanced distribution from categories A, B, and C
     */
    public function exportPdf(Request $request)
    {
        $periode = $request->get('periode', null);
        
        // Get ABC analysis data for export (100 items with balanced distribution)
        $analisisData = $this->getABCAnalysisForExport($periode);
        $recommendations = $this->generateRecommendations($analisisData);
        $summary = $this->getSummaryStatistics($analisisData);

        $pdf = Pdf::loadView('spk.pdf', [
            'analisisData' => $analisisData,
            'recommendations' => $recommendations,
            'summary' => $summary,
            'periode' => $periode,
        ]);

        $filename = 'SPK_' . ($periode ? 'Periode_' . $periode : 'Semua_Periode') . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($filename);
    }
}
