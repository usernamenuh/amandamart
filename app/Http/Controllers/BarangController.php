<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        // If it's an AJAX request for data, return JSON
        if ($request->ajax()) {
            return $this->getBarangData($request);
        }

        // Cache statistics for better performance (cache for 5 minutes)
        $stats = Cache::remember('barang_stats', 300, function () {
            return [
                'total_count' => Barang::count(),
                'low_stock_count' => Barang::where('qty', '<', 10)->count(),
                'total_inventory_value' => Barang::sum('total_cost'),
                'vendor_count' => Barang::whereNotNull('vendor')->distinct('vendor')->count(),
                'vendors' => Barang::whereNotNull('vendor')->distinct()->pluck('vendor')->filter()->sort()->values()
            ];
        });

        return view('barang.index', compact('stats'));
    }

    public function getBarangData(Request $request)
    {
        $search = $request->get('search');
        $vendor = $request->get('vendor');
        $periode = $request->get('periode');
        $stock = $request->get('stock'); // New stock filter
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 100);

        // Build query with filters
        $query = Barang::select([
            'id', 'no', 'nama_item', 'qty', 'cost_price', 'vendor', 
            'description', 'periode', 'created_at'
        ]);

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_item', 'LIKE', "%{$search}%")
                  ->orWhere('no', 'LIKE', "%{$search}%")
                  ->orWhere('itemid', 'LIKE', "%{$search}%")
                  ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        }

        // Apply vendor filter
        if ($vendor) {
            $query->where('vendor', $vendor);
        }

        // Apply periode filter
        if ($periode) {
            $query->where('periode', $periode);
        }

        // Apply stock filter
        if ($stock) {
            switch ($stock) {
                case 'low':
                    $query->where('qty', '<', 10);
                    break;
                case 'medium':
                    $query->whereBetween('qty', [10, 50]);
                    break;
                case 'high':
                    $query->where('qty', '>', 50);
                    break;
            }
        }

        // Get total count for filtered results
        $totalCount = $query->count();

        // Get paginated results
        $barangs = $query->latest()
                        ->offset($offset)
                        ->limit($limit)
                        ->get();

        return response()->json([
            'data' => $barangs,
            'total' => $totalCount,
            'hasMore' => ($offset + $limit) < $totalCount
        ]);
    }

    // Rest of the methods remain the same as in the previous controller...
    public function create()
    {
        $users = User::all();
        return view('barang.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'itemid' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'no' => 'nullable|string|max:255',
            'unitid' => 'nullable|string|max:255',
            'qty' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'disc_amt' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'vendor_id' => 'nullable|string|max:255',
            'vend_name' => 'nullable|string|max:255',
            'dept_id' => 'nullable|string|max:255',
            'dept_description' => 'nullable|string|max:255',
            'ctgry_id' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'periode' => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'site' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
            'consignment' => 'nullable|string|max:255',
        ], [
            'nama_item.required' => 'Nama item wajib diisi',
            'qty.required' => 'Quantity wajib diisi',
            'cost_price.required' => 'Harga beli wajib diisi',
        ]);

        // Get input data
        $data = $request->all();
        
        // Set default values for calculations
        $qty = $data['qty'] ?? 0;
        $cost_price = $data['cost_price'] ?? 0;
        $unit_price = $data['unit_price'] ?? 0;
        $disc_amt = $data['disc_amt'] ?? 0;
        
        // Auto-calculations
        $data['total_cost'] = $cost_price * $qty;
        $data['total_inc_ppn'] = $cost_price + ($cost_price * 0.11);
        
        if ($unit_price > 0) {
            $data['gross_amt'] = $unit_price * $qty;
            $data['sales_after_discount'] = $data['gross_amt'] - $disc_amt;
            $data['sales_vat'] = $data['sales_after_discount'] * 0.11;
            $data['net_sales_bef_tax'] = $data['sales_after_discount'] - $data['sales_vat'];
            $data['margin'] = $data['net_sales_bef_tax'] - $data['total_cost'];
            
            if ($data['total_cost'] > 0) {
                $data['margin_percent'] = ($data['margin'] / $data['total_cost']) * 100;
            } else {
                $data['margin_percent'] = 0;
            }
        } else {
            $data['gross_amt'] = 0;
            $data['sales_after_discount'] = 0;
            $data['sales_vat'] = 0;
            $data['net_sales_bef_tax'] = 0;
            $data['margin'] = 0;
            $data['margin_percent'] = 0;
        }

        // Date/time conversion
        if ($request->filled('date')) {
            $date = \DateTime::createFromFormat('Y-m-d', $request->date);
            if ($date) {
                $excel_epoch = \DateTime::createFromFormat('Y-m-d', '1900-01-01');
                $data['date'] = $date->diff($excel_epoch)->days + 1;
            } else {
                $data['date'] = null;
            }
        } else {
            $data['date'] = null;
        }

        if ($request->filled('time')) {
            $time = \DateTime::createFromFormat('H:i', $request->time);
            if ($time) {
                $hours = (int)$time->format('H');
                $minutes = (int)$time->format('i');
                $data['time'] = ($hours + ($minutes / 60)) / 24;
            } else {
                $data['time'] = null;
            }
        } else {
            $data['time'] = null;
        }

        Barang::create($data);
        Cache::forget('barang_stats');

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $users = User::all();
        return view('barang.edit', compact('barang', 'users'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_item' => 'required|string|max:255',
            'itemid' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'no' => 'nullable|string|max:255',
            'unitid' => 'nullable|string|max:255',
            'qty' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'unit_price' => 'nullable|numeric|min:0',
            'disc_amt' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'vendor_id' => 'nullable|string|max:255',
            'vend_name' => 'nullable|string|max:255',
            'dept_id' => 'nullable|string|max:255',
            'dept_description' => 'nullable|string|max:255',
            'ctgry_id' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'periode' => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'site' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
            'consignment' => 'nullable|string|max:255',
        ], [
            'nama_item.required' => 'Nama item wajib diisi',
            'qty.required' => 'Quantity wajib diisi',
            'cost_price.required' => 'Harga beli wajib diisi',
        ]);

        // Get input data
        $data = $request->all();
        
        // Set default values for calculations
        $qty = $data['qty'] ?? 0;
        $cost_price = $data['cost_price'] ?? 0;
        $unit_price = $data['unit_price'] ?? 0;
        $disc_amt = $data['disc_amt'] ?? 0;
        
        // Auto-calculations
        $data['total_cost'] = $cost_price * $qty;
        $data['total_inc_ppn'] = $cost_price + ($cost_price * 0.11);
        
        if ($unit_price > 0) {
            $data['gross_amt'] = $unit_price * $qty;
            $data['sales_after_discount'] = $data['gross_amt'] - $disc_amt;
            $data['sales_vat'] = $data['sales_after_discount'] * 0.11;
            $data['net_sales_bef_tax'] = $data['sales_after_discount'] - $data['sales_vat'];
            $data['margin'] = $data['net_sales_bef_tax'] - $data['total_cost'];
            
            if ($data['total_cost'] > 0) {
                $data['margin_percent'] = ($data['margin'] / $data['total_cost']) * 100;
            } else {
                $data['margin_percent'] = 0;
            }
        } else {
            $data['gross_amt'] = 0;
            $data['sales_after_discount'] = 0;
            $data['sales_vat'] = 0;
            $data['net_sales_bef_tax'] = 0;
            $data['margin'] = 0;
            $data['margin_percent'] = 0;
        }

        // Date/time conversion
        if ($request->filled('date')) {
            $date = \DateTime::createFromFormat('Y-m-d', $request->date);
            if ($date) {
                $excel_epoch = \DateTime::createFromFormat('Y-m-d', '1900-01-01');
                $data['date'] = $date->diff($excel_epoch)->days + 1;
            } else {
                $data['date'] = null;
            }
        } else {
            $data['date'] = null;
        }

        if ($request->filled('time')) {
            $time = \DateTime::createFromFormat('H:i', $request->time);
            if ($time) {
                $hours = (int)$time->format('H');
                $minutes = (int)$time->format('i');
                $data['time'] = ($hours + ($minutes / 60)) / 24;
            } else {
                $data['time'] = null;
            }
        } else {
            $data['time'] = null;
        }

        $barang->update($data);
        Cache::forget('barang_stats');

        return redirect()->route('barang.show', $barang)->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        Cache::forget('barang_stats');
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file.required' => 'File import wajib dipilih',
            'file.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            $import = new BarangImport();
            Excel::import($import, $request->file('file'));
            
            $stats = $import->getImportStats();
            Cache::forget('barang_stats');
            
            $message = "Import selesai! ";
            $message .= "Data baru: {$stats['imported']}, ";
            $message .= "Data diperbarui: {$stats['duplicates']}, ";
            $message .= "Error: {$stats['errors']}";
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'total_data' => $stats['imported'] + $stats['duplicates'],
                    'berhasil' => $stats['imported'],
                    'diperbarui' => $stats['duplicates'],
                    'gagal' => $stats['errors'],
                    'errors' => $stats['error_details']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ], 500);
        }
    }
}