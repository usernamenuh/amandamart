<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Transaksi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * For backward compatibility, redirect to dashboard
     */
    public function index()
    {
        // Redirect ke dashboard baru
        return redirect()->route('dashboard');
    }

    /**
     * Get statistics data for API calls
     */
    public function getStats()
    {
        try {
            // Get basic stats dengan kolom yang benar
            $stats = [
                'totalBarang' => $this->safeCount('barangs'),
                'totalTransaksi' => $this->safeCount('transaksis'),
                'totalRevenue' => $this->safeSumColumn('transaksis', 'total'),
                'totalInventoryValue' => $this->calculateInventoryValue(),
                'stokMenipis' => $this->safeCountWhere('barangs', 'qty', '<', 10), // Gunakan qty
                'user' => Auth::user()
            ];
            
            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('HomeController getStats error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Unable to fetch stats',
                'totalBarang' => 0,
                'totalTransaksi' => 0,
                'totalRevenue' => 0,
                'totalInventoryValue' => 0,
                'stokMenipis' => 0,
            ]);
        }
    }

    /**
     * Calculate total inventory value dengan struktur baru
     */
    private function calculateInventoryValue()
    {
        try {
            return Barang::where('qty', '>', 0)
                ->where('cost_price', '>', 0)
                ->get()
                ->sum(function($item) {
                    // Gunakan total_cost jika ada, atau hitung dari qty * cost_price
                    return $item->total_cost ?? ($item->qty * $item->cost_price);
                });
        } catch (\Exception $e) {
            \Log::error('calculateInventoryValue error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe count method with error handling
     */
    private function safeCount($table)
    {
        try {
            return DB::table($table)->count();
        } catch (\Exception $e) {
            \Log::error("SafeCount error for table {$table}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe sum method with error handling
     */
    private function safeSumColumn($table, $column)
    {
        try {
            return DB::table($table)->sum($column) ?? 0;
        } catch (\Exception $e) {
            \Log::error("SafeSum error for {$table}.{$column}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe count with where condition
     */
    private function safeCountWhere($table, $column, $operator, $value)
    {
        try {
            return DB::table($table)->where($column, $operator, $value)->count();
        } catch (\Exception $e) {
            \Log::error("SafeCountWhere error for {$table}.{$column}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Debug method untuk cek data dengan struktur baru
     */
    public function debugData()
    {
        try {
            $barangCount = Barang::count();
            $barangWithStock = Barang::where('qty', '>', 0)->count();
            $barangWithPrice = Barang::where('cost_price', '>', 0)->count();
            $barangWithBoth = Barang::where('qty', '>', 0)->where('cost_price', '>', 0)->count();
            
            $sampleBarangs = Barang::select('nama_item', 'qty', 'cost_price', 'total_cost', 'unit_price')
                ->where('qty', '>', 0)
                ->where('cost_price', '>', 0)
                ->limit(5)
                ->get();

            return response()->json([
                'total_barang' => $barangCount,
                'barang_with_stock' => $barangWithStock,
                'barang_with_price' => $barangWithPrice,
                'barang_with_both' => $barangWithBoth,
                'sample_barangs' => $sampleBarangs,
                'transaksi_count' => Transaksi::count(),
                'database_tables' => DB::select('SHOW TABLES'),
                'barangs_table_structure' => DB::select('DESCRIBE barangs'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}