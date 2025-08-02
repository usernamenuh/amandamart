<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('user')->latest()->get();
        return view('barang.index', compact('barangs'));
    }

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
        
        // 1. Auto-calculate Total Cost
        $data['total_cost'] = $cost_price * $qty;
        
        // 2. Auto-calculate Total Inc PPN (Cost Price + 11% PPN)
        $data['total_inc_ppn'] = $cost_price + ($cost_price * 0.11);
        
        // 3. Auto-calculate Gross Amount (if unit_price provided)
        if ($unit_price > 0) {
            $data['gross_amt'] = $unit_price * $qty;
            
            // 4. Auto-calculate Sales After Discount
            $data['sales_after_discount'] = $data['gross_amt'] - $disc_amt;
            
            // 5. Auto-calculate Sales VAT (11% from Sales After Discount)
            $data['sales_vat'] = $data['sales_after_discount'] * 0.11;
            
            // 6. Auto-calculate Net Sales Before Tax
            $data['net_sales_bef_tax'] = $data['sales_after_discount'] - $data['sales_vat'];
            
            // 7. Auto-calculate Margin (Net Sales Before Tax - Total Cost)
            $data['margin'] = $data['net_sales_bef_tax'] - $data['total_cost'];
            
            // 8. Auto-calculate Margin Percent
            if ($data['total_cost'] > 0) {
                $data['margin_percent'] = ($data['margin'] / $data['total_cost']) * 100;
            } else {
                $data['margin_percent'] = 0;
            }
        } else {
            // If no unit price, set sales-related fields to 0
            $data['gross_amt'] = 0;
            $data['sales_after_discount'] = 0;
            $data['sales_vat'] = 0;
            $data['net_sales_bef_tax'] = 0;
            $data['margin'] = 0;
            $data['margin_percent'] = 0;
        }

        // Convert date to integer if provided (Excel format)
        if ($request->filled('date')) {
            $date = \DateTime::createFromFormat('Y-m-d', $request->date);
            if ($date) {
                // Convert to Excel date serial number (days since 1900-01-01)
                $excel_epoch = \DateTime::createFromFormat('Y-m-d', '1900-01-01');
                $data['date'] = $date->diff($excel_epoch)->days + 1;
            } else {
                $data['date'] = null;
            }
        } else {
            $data['date'] = null;
        }

        // Convert time to decimal if provided
        if ($request->filled('time')) {
            $time = \DateTime::createFromFormat('H:i', $request->time);
            if ($time) {
                // Convert to decimal (fraction of day)
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
        
        // 1. Auto-calculate Total Cost
        $data['total_cost'] = $cost_price * $qty;
        
        // 2. Auto-calculate Total Inc PPN (Cost Price + 11% PPN)
        $data['total_inc_ppn'] = $cost_price + ($cost_price * 0.11);
        
        // 3. Auto-calculate Gross Amount (if unit_price provided)
        if ($unit_price > 0) {
            $data['gross_amt'] = $unit_price * $qty;
            
            // 4. Auto-calculate Sales After Discount
            $data['sales_after_discount'] = $data['gross_amt'] - $disc_amt;
            
            // 5. Auto-calculate Sales VAT (11% from Sales After Discount)
            $data['sales_vat'] = $data['sales_after_discount'] * 0.11;
            
            // 6. Auto-calculate Net Sales Before Tax
            $data['net_sales_bef_tax'] = $data['sales_after_discount'] - $data['sales_vat'];
            
            // 7. Auto-calculate Margin (Net Sales Before Tax - Total Cost)
            $data['margin'] = $data['net_sales_bef_tax'] - $data['total_cost'];
            
            // 8. Auto-calculate Margin Percent
            if ($data['total_cost'] > 0) {
                $data['margin_percent'] = ($data['margin'] / $data['total_cost']) * 100;
            } else {
                $data['margin_percent'] = 0;
            }
        } else {
            // If no unit price, set sales-related fields to 0
            $data['gross_amt'] = 0;
            $data['sales_after_discount'] = 0;
            $data['sales_vat'] = 0;
            $data['net_sales_bef_tax'] = 0;
            $data['margin'] = 0;
            $data['margin_percent'] = 0;
        }

        // Convert date to integer if provided (Excel format)
        if ($request->filled('date')) {
            $date = \DateTime::createFromFormat('Y-m-d', $request->date);
            if ($date) {
                // Convert to Excel date serial number (days since 1900-01-01)
                $excel_epoch = \DateTime::createFromFormat('Y-m-d', '1900-01-01');
                $data['date'] = $date->diff($excel_epoch)->days + 1;
            } else {
                $data['date'] = null;
            }
        } else {
            $data['date'] = null;
        }

        // Convert time to decimal if provided
        if ($request->filled('time')) {
            $time = \DateTime::createFromFormat('H:i', $request->time);
            if ($time) {
                // Convert to decimal (fraction of day)
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

        return redirect()->route('barang.show', $barang)->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
    }

    public function importForm()
    {
        return view('barang.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // max 10MB
        ], [
            'file.required' => 'File import wajib dipilih',
            'file.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            $import = new BarangImport();
            Excel::import($import, $request->file('file'));
            
            $stats = $import->getImportStats();
            
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
