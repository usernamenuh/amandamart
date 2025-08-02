<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Optimized query with eager loading
        $query = Transaksi::with(['barang:id,nama_item,no', 'user:id,name'])
                          ->select('id', 'barang_id', 'user_id', 'tanggal_transaksi', 'jenis_transaksi', 'qty', 'harga_satuan', 'total_harga', 'no_referensi', 'keterangan')
                          ->latest('tanggal_transaksi');

        // Simplified filters (removed date range)
        if ($request->filled('jenis')) {
            $query->where('jenis_transaksi', $request->jenis);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        // Paginate with optimized count
        $transaksis = $query->paginate(20);
        
        // Get barangs for filter (only needed fields)
        $barangs = Barang::select('id', 'nama_item')->orderBy('nama_item')->get();

        // Optimized statistics with single queries
        $stats = [
            'total_transaksi' => Transaksi::count(),
            'transaksi_masuk' => Transaksi::where('jenis_transaksi', 'masuk')->count(),
            'transaksi_keluar' => Transaksi::where('jenis_transaksi', 'keluar')->count(),
            'total_nilai' => Transaksi::sum('total_harga'),
        ];

        return view('transaksi.index', compact('transaksis', 'barangs', 'stats'));
    }

    public function create()
    {
        $barangs = Barang::orderBy('nama_item')->get();
        return view('transaksi.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:1000',
            'no_referensi' => 'nullable|string|max:255',
        ], [
            'barang_id.required' => 'Barang wajib dipilih',
            'barang_id.exists' => 'Barang tidak ditemukan',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi',
            'jenis_transaksi.required' => 'Jenis transaksi wajib dipilih',
            'qty.required' => 'Quantity wajib diisi',
            'qty.min' => 'Quantity minimal 1',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Check stock for keluar transaction
        if ($request->jenis_transaksi === 'keluar') {
            if ($barang->qty < $request->qty) {
                return back()->withErrors([
                    'qty' => "Stok tidak mencukupi. Stok tersedia: {$barang->qty}"
                ])->withInput();
            }
        }

        DB::transaction(function () use ($request, $barang) {
            // Auto-determine harga_satuan based on transaction type
            $hargaSatuan = $request->jenis_transaksi === 'masuk' 
                ? $barang->cost_price 
                : ($barang->unit_price ?: $barang->cost_price);

            // Create transaction (calculations will be done automatically in Model)
            $transaksi = Transaksi::create([
                'barang_id' => $request->barang_id,
                'user_id' => Auth::id(),
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'jenis_transaksi' => $request->jenis_transaksi,
                'qty' => $request->qty,
                'harga_satuan' => $hargaSatuan,
                'keterangan' => $request->keterangan,
                'no_referensi' => $request->no_referensi,
            ]);

            // Update barang stock
            if ($request->jenis_transaksi === 'masuk') {
                $barang->increment('qty', $request->qty);
            } else {
                $barang->decrement('qty', $request->qty);
            }
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function show(Transaksi $transaksi)
    {
        // Load relationships
        $transaksi->load(['barang', 'user']);
        
        // Get related statistics for this specific item
        $stats = [
            'total_transaksi_barang' => Transaksi::where('barang_id', $transaksi->barang_id)->count(),
            'transaksi_masuk_barang' => Transaksi::where('barang_id', $transaksi->barang_id)->where('jenis_transaksi', 'masuk')->count(),
            'transaksi_keluar_barang' => Transaksi::where('barang_id', $transaksi->barang_id)->where('jenis_transaksi', 'keluar')->count(),
            'total_nilai_barang' => Transaksi::where('barang_id', $transaksi->barang_id)->sum('total_harga'),
        ];
        
        return view('transaksi.show', compact('transaksi', 'stats'));
    }

    public function edit(Transaksi $transaksi)
    {
        $barangs = Barang::orderBy('nama_item')->get();
        return view('transaksi.edit', compact('transaksi', 'barangs'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_transaksi' => 'required|date',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:1000',
            'no_referensi' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            // Revert old transaction effect on stock
            $oldBarang = Barang::find($transaksi->barang_id);
            if ($oldBarang) {
                if ($transaksi->jenis_transaksi === 'masuk') {
                    $oldBarang->decrement('qty', $transaksi->qty);
                } else {
                    $oldBarang->increment('qty', $transaksi->qty);
                }
            }

            $barang = Barang::findOrFail($request->barang_id);

            // Check stock for new keluar transaction
            if ($request->jenis_transaksi === 'keluar') {
                $currentStock = $barang->qty;
                if ($currentStock < $request->qty) {
                    throw new \Exception("Stok tidak mencukupi. Stok tersedia: {$currentStock}");
                }
            }

            // Auto-determine harga_satuan based on transaction type
            $hargaSatuan = $request->jenis_transaksi === 'masuk' 
                ? $barang->cost_price 
                : ($barang->unit_price ?: $barang->cost_price);

            // Update transaction (calculations will be done automatically in Model)
            $transaksi->update([
                'barang_id' => $request->barang_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'jenis_transaksi' => $request->jenis_transaksi,
                'qty' => $request->qty,
                'harga_satuan' => $hargaSatuan,
                'keterangan' => $request->keterangan,
                'no_referensi' => $request->no_referensi,
            ]);

            // Apply new transaction effect on stock
            if ($request->jenis_transaksi === 'masuk') {
                $barang->increment('qty', $request->qty);
            } else {
                $barang->decrement('qty', $request->qty);
            }
        });

        return redirect()->route('transaksi.show', $transaksi)->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            // Revert transaction effect on stock
            $barang = Barang::find($transaksi->barang_id);
            if ($barang) {
                if ($transaksi->jenis_transaksi === 'masuk') {
                    $barang->decrement('qty', $transaksi->qty);
                } else {
                    $barang->increment('qty', $transaksi->qty);
                }
            }

            // Delete transaction
            $transaksi->delete();
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus!');
    }

    /**
     * Get barang details for AJAX
     */
    public function getBarangDetails($id)
    {
        $barang = Barang::find($id);
        
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $barang->id,
            'nama_item' => $barang->nama_item,
            'qty' => $barang->qty,
            'cost_price' => $barang->cost_price,
            'unit_price' => $barang->unit_price,
            'disc_amt' => $barang->disc_amt,
            'sales_vat' => $barang->sales_vat,
            'total_inc_ppn' => $barang->total_inc_ppn,
            'vendor' => $barang->vendor,
        ]);
    }
}