<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParetoExport;
use Illuminate\Support\Facades\DB;

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

        // Hitung total untuk persentase - PERBAIKAN UTAMA DI SINI
        $totalSumOfBasis = $sortBy === 'quantity' 
            ? $analisis->sum('total_qty') 
            : $analisis->sum('total_nilai');

        // Hitung persentase dan kategori ABC - PERBAIKAN PERHITUNGAN
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
     * Tampilkan analisis Pareto di view
     */
    public function analisisPareto(Request $request)
    {
        [$analisis, $totalSumOfBasis, $sortBy] = $this->getParetoData($request);
        $periode = $request->query('periode', null);
        
        // Hitung statistik dengan benar berdasarkan basis sorting
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

        // Info periode
        $periodeInfo = null;
        if ($periode && $periode > 0) {
            $periodeInfo = [
                'periode' => $periode,
                'nama_bulan' => $this->getBulanName($periode),
                'label' => 'Periode ' . $this->getBulanName($periode)
            ];
        }

        // Ambil daftar periode yang tersedia untuk dropdown
        $availablePeriodes = Barang::select('periode')
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

        return view('laporan.pareto', compact('analisis', 'totalSumOfBasis', 'stats', 'periode', 'periodeInfo', 'availablePeriodes', 'sortBy'));
    }

    /**
     * Export analisis Pareto ke Excel
     */
    public function exportPareto(Request $request)
    {
        [$analisis, $totalSumOfBasis, $sortBy] = $this->getParetoData($request);
        $periode = $request->query('periode', null);
        
        $exportData = $analisis->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'nama_barang' => $item->nama_barang,
                'no_barang' => $item->no_barang,
                'vendor' => $item->vendor,
                'periode' => $item->periode_name,
                'qty' => $item->total_qty,
                'harga_satuan' => $item->harga_satuan,
                'total_nilai' => $item->total_nilai,
                'persentase' => $item->persentase,
                'akumulasi_persentase' => $item->akumulasi_persentase,
                'kategori' => $item->kategori,
            ];
        })->toArray();

        $filename = 'analisis_pareto_abc_' . 
                   ($periode ? $this->getBulanName($periode) . '_' : 'semua_periode_') . 
                   date('Y-m-d') . '.xlsx';

        return Excel::download(new ParetoExport($exportData), $filename);
    }
}