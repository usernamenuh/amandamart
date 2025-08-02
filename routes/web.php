<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {

     Route::get('/debug/dashboard-data', function() {
        try {
            // Simulate what happens in dashboard controller
            $totalBarang = App\Models\Barang::count();
            $totalTransaksi = App\Models\Transaksi::count();
            
            $totalRevenue = App\Models\Transaksi::sum('total') ?? 0;
            if ($totalRevenue == 0) {
                $totalRevenue = App\Models\Barang::where('qty', '>', 0)
                    ->where('unit_price', '>', 0)
                    ->get()
                    ->sum(function($item) {
                        return floor($item->qty * 0.1) * $item->unit_price;
                    });
            }
            
            $totalInventoryValue = App\Models\Barang::where('qty', '>', 0)
                ->where('cost_price', '>', 0)
                ->get()
                ->sum(function($item) {
                    return $item->total_cost ?? ($item->qty * $item->cost_price);
                });
            
            $stokMenipis = App\Models\Barang::where('qty', '<', 10)
                ->where('qty', '>', 0)
                ->count();
            
            // Test view data passing
            $viewData = [
                'totalBarang' => $totalBarang,
                'totalTransaksi' => $totalTransaksi,
                'totalRevenue' => $totalRevenue,
                'totalInventoryValue' => $totalInventoryValue,
                'stokMenipis' => $stokMenipis,
            ];
            
            return response()->json([
                'calculated_data' => $viewData,
                'data_types' => [
                    'totalBarang' => gettype($totalBarang),
                    'totalTransaksi' => gettype($totalTransaksi),
                    'totalRevenue' => gettype($totalRevenue),
                    'totalInventoryValue' => gettype($totalInventoryValue),
                    'stokMenipis' => gettype($stokMenipis),
                ],
                'sample_calculations' => [
                    'first_5_barangs' => App\Models\Barang::where('qty', '>', 0)
                        ->where('cost_price', '>', 0)
                        ->limit(5)
                        ->get()
                        ->map(function($item) {
                            return [
                                'nama_item' => $item->nama_item,
                                'qty' => $item->qty,
                                'cost_price' => $item->cost_price,
                                'total_cost' => $item->total_cost,
                                'calculated_value' => $item->total_cost ?? ($item->qty * $item->cost_price)
                            ];
                        }),
                ],
                'message' => 'Data calculation test completed'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    });
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Barang Routes
    Route::resource('barang', BarangController::class);
    Route::get('/barang/import/form', [BarangController::class, 'importForm'])->name('barang.import.form');
    Route::post('/barang/import', [BarangController::class, 'import'])->name('barang.import');
    Route::get('/barang/template', [BarangController::class, 'downloadTemplate'])->name('barang.template');

    // Transaksi Routes
    Route::resource('transaksi', TransaksiController::class);
    Route::controller(TransaksiController::class)->group(function () {
        Route::middleware('role:admin_gudang')->group(function () {
            Route::get('/transaksi/import', 'showImportForm')->name('transaksi.import.form');
        });

        Route::post('/transaksi/import', 'import')->name('transaksi.import');
        Route::get('/transaksi/import/template', 'downloadTemplate')->name('transaksi.import.template');
        Route::get('/transaksi/clear', 'clearData')->name('transaksi.clear');
        Route::get('/transaksi/test', 'testData')->name('transaksi.test');
    });

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
    // Routes yang sudah ada...
    Route::get('/pareto', [LaporanController::class, 'analisisPareto'])->name('pareto');
    Route::get('/pareto/export', [LaporanController::class, 'exportPareto'])->name('pareto.export');
    
    // Routes baru untuk PDF dan Print
    Route::get('/pareto/export-pdf', [LaporanController::class, 'exportPdf'])->name('pareto.export.pdf');
    Route::get('/pareto/preview-pdf', [LaporanController::class, 'previewPdf'])->name('pareto.preview.pdf');
    Route::get('/pareto/print', [LaporanController::class, 'printPage'])->name('pareto.print');
});
});
