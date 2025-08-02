<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParetoExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Mapping bulan untuk display
     */
    private function getBulanName($periode)
    {
        $bulanNames = [
            1 => 'Januari',
            2 => 'Februari', 
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        return $bulanNames[$periode] ?? 'Tidak Diketahui';
    }

    /**
     * Ambil data analisis Pareto berdasarkan kolom PERIODE di tabel barangs
     */
    private function getParetoData(Request $request)
    {
        $sortBy = $request->query('sort_by', 'value');
        $periode = $request->query('periode', null);
        
        // Query barang berdasarkan periode
        $query = Barang::select([
                'id',
                'nama_item',
                'no',
                'qty',
                'cost_price',
                'unit_price',
                'total_inc_ppn',
                'vendor',
                'periode'
            ])
            ->where('qty', '>', 0);

        // Filter berdasarkan periode jika dipilih
        if ($periode && $periode > 0) {
            $query->where('periode', $periode);
        }

        $barangs = $query->get();

        // Hitung nilai total untuk setiap barang dan buat collection untuk analisis
        $analisis = $barangs->map(function ($barang) {
            // Tentukan harga yang akan digunakan untuk perhitungan nilai
            $harga = $barang->unit_price > 0 ? $barang->unit_price : $barang->cost_price;
            $nilai_total = $barang->qty * $harga;
            
            return (object) [
                'barang_id' => $barang->id,
                'nama_barang' => $barang->nama_item,
                'no_barang' => $barang->no,
                'total_qty' => $barang->qty,
                'harga_satuan' => $harga,
                'total_nilai' => $nilai_total,
                'vendor' => $barang->vendor,
                'cost_price' => $barang->cost_price,
                'unit_price' => $barang->unit_price,
                'total_inc_ppn' => $barang->total_inc_ppn,
                'stok_saat_ini' => $barang->qty,
                'periode' => $barang->periode,
                'periode_name' => $this->getBulanName($barang->periode)
            ];
        });

        // Filter barang yang memiliki nilai > 0
        $analisis = $analisis->filter(function ($item) {
            return $item->total_nilai > 0;
        });

        // Urutkan berdasarkan kriteria yang dipilih
        if ($sortBy === 'quantity') {
            $analisis = $analisis->sortByDesc('total_qty');
        } else {
            $analisis = $analisis->sortByDesc('total_nilai');
        }

        // Reset keys setelah sorting
        $analisis = $analisis->values();

        // Hitung total untuk persentase
        $totalSumOfBasis = $sortBy === 'quantity' 
            ? $analisis->sum('total_qty') 
            : $analisis->sum('total_nilai');

        // Hitung persentase dan kategori ABC
        $akumulasi = 0;
        foreach ($analisis as $item) {
            // Ambil nilai yang sesuai dengan basis sorting
            $itemBasis = $sortBy === 'quantity' ? $item->total_qty : $item->total_nilai;
            
            // Hitung persentase individual dengan validasi
            $persentase = $totalSumOfBasis > 0 ? ($itemBasis / $totalSumOfBasis) * 100 : 0;
            $akumulasi += $persentase;

            // Klasifikasi ABC berdasarkan akumulasi persentase
            if ($akumulasi <= 80) {
                $kategori = 'A';
            } elseif ($akumulasi <= 95) {
                $kategori = 'B';
            } else {
                $kategori = 'C';
            }

            $item->persentase = round($persentase, 2);
            $item->akumulasi_persentase = round($akumulasi, 2);
            $item->kategori = $kategori;
        }

        return [$analisis, $totalSumOfBasis, $sortBy];
    }

    /**
     * Hitung statistik lengkap untuk analisis
     */
    private function calculateStats($analisis, $totalSumOfBasis, $sortBy)
    {
        $stats = [
            'total_barang' => $analisis->count(),
            'total_nilai_inventori' => $analisis->sum('total_nilai'),
            'total_qty_inventori' => $analisis->sum('total_qty'),
            'kategori_a_count' => $analisis->where('kategori', 'A')->count(),
            'kategori_b_count' => $analisis->where('kategori', 'B')->count(),
            'kategori_c_count' => $analisis->where('kategori', 'C')->count(),
        ];

        // Hitung nilai dan kontribusi per kategori berdasarkan basis yang dipilih
        if ($sortBy === 'quantity') {
            $stats['kategori_a_basis'] = $analisis->where('kategori', 'A')->sum('total_qty');
            $stats['kategori_b_basis'] = $analisis->where('kategori', 'B')->sum('total_qty');
            $stats['kategori_c_basis'] = $analisis->where('kategori', 'C')->sum('total_qty');
        } else {
            $stats['kategori_a_basis'] = $analisis->where('kategori', 'A')->sum('total_nilai');
            $stats['kategori_b_basis'] = $analisis->where('kategori', 'B')->sum('total_nilai');
            $stats['kategori_c_basis'] = $analisis->where('kategori', 'C')->sum('total_nilai');
        }

        // Hitung persentase kontribusi yang benar
        $stats['kontribusi_a'] = $totalSumOfBasis > 0 ? round(($stats['kategori_a_basis'] / $totalSumOfBasis) * 100, 1) : 0;
        $stats['kontribusi_b'] = $totalSumOfBasis > 0 ? round(($stats['kategori_b_basis'] / $totalSumOfBasis) * 100, 1) : 0;
        $stats['kontribusi_c'] = $totalSumOfBasis > 0 ? round(($stats['kategori_c_basis'] / $totalSumOfBasis) * 100, 1) : 0;

        // Nilai untuk display (selalu dalam rupiah)
        $stats['nilai_kategori_a'] = $analisis->where('kategori', 'A')->sum('total_nilai');
        $stats['nilai_kategori_b'] = $analisis->where('kategori', 'B')->sum('total_nilai');
        $stats['nilai_kategori_c'] = $analisis->where('kategori', 'C')->sum('total_nilai');

        return $stats;
    }

    /**
     * Generate info periode
     */
    private function getPeriodeInfo($periode)
    {
        if ($periode && $periode > 0) {
            return [
                'periode' => $periode,
                'nama_bulan' => $this->getBulanName($periode),
                'label' => 'Periode ' . $this->getBulanName($periode)
            ];
        }
        return null;
    }

    /**
     * Ambil daftar periode yang tersedia
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
            ->map(function($p) {
                return [
                    'value' => $p,
                    'name' => $this->getBulanName($p)
                ];
            });
    }

    /**
     * Tampilkan analisis Pareto di view
     */
    public function analisisPareto(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'sort_by' => 'nullable|in:value,quantity',
                'periode' => 'nullable|integer|min:1|max:12'
            ]);

            [$analisis, $totalSumOfBasis, $sortBy] = $this->getParetoData($request);
            $periode = $request->query('periode', null);
            
            // Hitung statistik
            $stats = $this->calculateStats($analisis, $totalSumOfBasis, $sortBy);
            
            // Info periode
            $periodeInfo = $this->getPeriodeInfo($periode);
            
            // Ambil daftar periode yang tersedia untuk dropdown
            $availablePeriodes = $this->getAvailablePeriodes();

            return view('laporan.pareto', compact(
                'analisis', 
                'totalSumOfBasis', 
                'stats', 
                'periode', 
                'periodeInfo', 
                'availablePeriodes', 
                'sortBy'
            ));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat analisis: ' . $e->getMessage());
        }
    }

    /**
     * Export analisis Pareto ke Excel dengan style yang enhanced
     */
    public function exportPareto(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'sort_by' => 'nullable|in:value,quantity',
                'periode' => 'nullable|integer|min:1|max:12'
            ]);

            [$analisis, $totalSumOfBasis, $sortBy] = $this->getParetoData($request);
            $periode = $request->query('periode', null);
            
            // Hitung statistik untuk summary
            $stats = $this->calculateStats($analisis, $totalSumOfBasis, $sortBy);
            
            // Info periode
            $periodeInfo = $this->getPeriodeInfo($periode);

            // Generate filename yang descriptive
            $basisText = $sortBy === 'quantity' ? 'Kuantitas' : 'Nilai';
            $periodeText = $periodeInfo ? $periodeInfo['nama_bulan'] : 'Semua_Periode';
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "Analisis_ABC_Pareto_{$basisText}_{$periodeText}_{$timestamp}.xlsx";

            // Export dengan data lengkap
            return Excel::download(
                new ParetoExport($analisis, $periode, $periodeInfo, $sortBy, $stats), 
                $filename
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    /**
     * Get ABC analysis summary for API/AJAX
     */
    public function getAbcSummary(Request $request)
    {
        try {
            [$analisis, $totalSumOfBasis, $sortBy] = $this->getParetoData($request);
            $stats = $this->calculateStats($analisis, $totalSumOfBasis, $sortBy);
            $periode = $request->query('periode', null);
            $periodeInfo = $this->getPeriodeInfo($periode);

            $summary = [
                'success' => true,
                'data' => [
                    'total_items' => $stats['total_barang'],
                    'total_value' => $stats['total_nilai_inventori'],
                    'total_quantity' => $stats['total_qty_inventori'],
                    'sort_by' => $sortBy,
                    'periode_info' => $periodeInfo,
                    'categories' => [
                        'A' => [
                            'count' => $stats['kategori_a_count'],
                            'value' => $stats['nilai_kategori_a'],
                            'contribution' => $stats['kontribusi_a'],
                            'basis_value' => $stats['kategori_a_basis']
                        ],
                        'B' => [
                            'count' => $stats['kategori_b_count'],
                            'value' => $stats['nilai_kategori_b'],
                            'contribution' => $stats['kontribusi_b'],
                            'basis_value' => $stats['kategori_b_basis']
                        ],
                        'C' => [
                            'count' => $stats['kategori_c_count'],
                            'value' => $stats['nilai_kategori_c'],
                            'contribution' => $stats['kontribusi_c'],
                            'basis_value' => $stats['kategori_c_basis']
                        ]
                    ]
                ]
            ];

            return response()->json($summary);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed analysis data for specific category
     */
    public function getCategoryDetails(Request $request, $category)
    {
        try {
            $request->validate([
                'sort_by' => 'nullable|in:value,quantity',
                'periode' => 'nullable|integer|min:1|max:12'
            ]);

            if (!in_array($category, ['A', 'B', 'C'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid'
                ], 400);
            }

            [$analisis, $totalSumOfBasis, $sortBy] = $this->getParetoData($request);
            
            $categoryItems = $analisis->where('kategori', $category)->values();
            $stats = $this->calculateStats($analisis, $totalSumOfBasis, $sortBy);
            $periodeInfo = $this->getPeriodeInfo($request->query('periode'));

            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $category,
                    'items' => $categoryItems,
                    'summary' => [
                        'count' => $categoryItems->count(),
                        'total_value' => $categoryItems->sum('total_nilai'),
                        'total_quantity' => $categoryItems->sum('total_qty'),
                        'contribution' => $stats['kontribusi_' . strtolower($category)]
                    ],
                    'periode_info' => $periodeInfo,
                    'sort_by' => $sortBy
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard summary untuk widget
     */
    public function dashboardSummary()
    {
        try {
            // Ambil data untuk semua periode dengan basis nilai
            $request = new Request(['sort_by' => 'value']);
            [$analisis, $totalSumOfBasis, $sortBy] = $this->getParetoData($request);
            $stats = $this->calculateStats($analisis, $totalSumOfBasis, $sortBy);

            // Summary per periode
            $periodeStats = [];
            for ($i = 1; $i <= 12; $i++) {
                $periodeRequest = new Request(['sort_by' => 'value', 'periode' => $i]);
                [$periodeAnalisis, $periodeTotalBasis, $periodeSortBy] = $this->getParetoData($periodeRequest);
                
                if ($periodeAnalisis->count() > 0) {
                    $periodeStats[$i] = [
                        'nama_bulan' => $this->getBulanName($i),
                        'total_items' => $periodeAnalisis->count(),
                        'total_value' => $periodeAnalisis->sum('total_nilai'),
                        'total_quantity' => $periodeAnalisis->sum('total_qty'),
                        'category_a_count' => $periodeAnalisis->where('kategori', 'A')->count(),
                        'category_b_count' => $periodeAnalisis->where('kategori', 'B')->count(),
                        'category_c_count' => $periodeAnalisis->where('kategori', 'C')->count(),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'overall' => $stats,
                    'by_periode' => $periodeStats,
                    'last_updated' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh cache atau data (jika diperlukan)
     */
    public function refreshData()
    {
        try {
            // Clear any cache if needed
            // Cache::forget('abc_analysis');
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil direfresh',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal refresh data: ' . $e->getMessage()
            ], 500);
        }
    }
}