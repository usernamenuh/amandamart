@extends('layouts.dashboard')

@section('title', 'Edit Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Edit Transaksi" 
        subtitle="Perbarui informasi transaksi barang"
        :showTabs="true"
        activeTab="transaksi"
        :showBanner="true"
    />

    <!-- Main Content -->
     <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('transaksi.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Data Transaksi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500">Edit Transaksi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-edit text-yellow-600"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Form Edit Transaksi</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Harga otomatis dari master barang (Masuk: Harga Beli, Keluar: Harga Jual)
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('transaksi.show', $transaksi->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        <a href="{{ route('transaksi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Current Transaction Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Informasi Transaksi Saat Ini
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700 font-medium">Barang:</span>
                                <div class="text-blue-900 font-semibold">{{ $transaksi->barang->nama_item }}</div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Jenis:</span>
                                <div class="text-blue-900 font-semibold">{{ ucfirst($transaksi->jenis_transaksi) }}</div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Quantity:</span>
                                <div class="text-blue-900 font-semibold">{{ number_format($transaksi->qty, 0) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal Transaksi -->
                        <div class="space-y-2">
                            <label for="tanggal_transaksi" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar mr-1"></i>
                                Tanggal Transaksi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition-all duration-200 @error('tanggal_transaksi') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi->format('Y-m-d')) }}" required>
                            @error('tanggal_transaksi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Jenis Transaksi -->
                        <div class="space-y-2">
                            <label for="jenis_transaksi" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-tags mr-1"></i>
                                Jenis Transaksi <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_transaksi" id="jenis_transaksi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition-all duration-200 @error('jenis_transaksi') border-red-500 ring-2 ring-red-200 @enderror" required>
                                <option value="">Pilih Jenis Transaksi</option>
                                <option value="masuk" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) === 'masuk' ? 'selected' : '' }}>
                                    Barang Masuk (Pembelian)
                                </option>
                                <option value="keluar" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) === 'keluar' ? 'selected' : '' }}>
                                    Barang Keluar (Penjualan)
                                </option>
                            </select>
                            @error('jenis_transaksi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Barang Selection -->
                    <div class="space-y-2">
                        <label for="barang_id" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-box mr-1"></i>
                            Pilih Barang <span class="text-red-500">*</span>
                        </label>
                        <select name="barang_id" id="barang_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition-all duration-200 @error('barang_id') border-red-500 ring-2 ring-red-200 @enderror" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ old('barang_id', $transaksi->barang_id) == $barang->id ? 'selected' : '' }}
                                        data-qty="{{ $barang->qty }}" 
                                        data-cost="{{ $barang->cost_price }}" 
                                        data-unit="{{ $barang->unit_price }}"
                                        data-disc="{{ $barang->disc_amt }}"
                                        data-vendor="{{ $barang->vendor }}">
                                    {{ $barang->nama_item }} 
                                    @if($barang->no) - No: {{ $barang->no }} @endif
                                    (Stok: {{ number_format($barang->qty, 0) }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Barang Info Display -->
                    <div id="barang-info" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Informasi Barang:
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700 font-medium">
                                    <i class="fas fa-warehouse mr-1"></i>
                                    Stok Tersedia:
                                </span>
                                <div id="current-stock" class="text-blue-900 font-semibold"></div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">
                                    <i class="fas fa-money-bill mr-1"></i>
                                    Harga Beli:
                                </span>
                                <div id="cost-price" class="text-blue-900 font-semibold"></div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">
                                    <i class="fas fa-tag mr-1"></i>
                                    Harga Jual:
                                </span>
                                <div id="unit-price" class="text-blue-900 font-semibold"></div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">
                                    <i class="fas fa-truck mr-1"></i>
                                    Vendor:
                                </span>
                                <div id="vendor-name" class="text-blue-900 font-semibold"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Quantity -->
                        <div class="space-y-2">
                            <label for="qty" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-cubes mr-1"></i>
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="qty" id="qty" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition-all duration-200 @error('qty') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('qty', $transaksi->qty) }}" min="1" placeholder="1" required>
                            @error('qty')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            <div id="stock-warning" class="hidden text-sm text-red-600 font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Quantity melebihi stok yang tersedia!
                            </div>
                        </div>

                        <!-- Harga Satuan (Display Only) -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-rupiah-sign mr-1"></i>
                                Harga Satuan (Otomatis)
                            </label>
                            <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">
                                <span id="harga-display">Pilih barang dan jenis transaksi</span>
                            </div>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Masuk: Harga Beli | Keluar: Harga Jual
                            </p>
                        </div>
                    </div>

                    <!-- Calculation Preview -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-calculator mr-2"></i>
                            Preview Perhitungan
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">
                                    <i class="fas fa-equals mr-1"></i>
                                    Subtotal (Qty × Harga):
                                </span>
                                <span id="subtotal" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">
                                    <i class="fas fa-minus mr-1"></i>
                                    Diskon Total:
                                </span>
                                <span id="total-discount" class="font-semibold text-orange-600">- Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">
                                    <i class="fas fa-check mr-1"></i>
                                    Subtotal Setelah Diskon:
                                </span>
                                <span id="subtotal-after-disc" class="font-semibold text-blue-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">
                                    <i class="fas fa-plus mr-1"></i>
                                    PPN (11%) - Hanya Penjualan:
                                </span>
                                <span id="ppn-amount" class="font-semibold text-purple-600">+ Rp 0</span>
                            </div>
                            <hr class="border-gray-300">
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-bold text-gray-800">
                                    <i class="fas fa-coins mr-1"></i>
                                    TOTAL AKHIR:
                                </span>
                                <span id="total-harga" class="font-bold text-green-600">Rp 0</span>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-lightbulb mr-1"></i>
                                <strong>Logika Perhitungan:</strong>
                            </p>
                            <ul class="text-xs text-yellow-700 mt-1 space-y-1">
                                <li>• <strong>Barang Masuk:</strong> Harga Beli × Qty (tanpa PPN)</li>
                                <li>• <strong>Barang Keluar:</strong> (Harga Jual × Qty) - Diskon + PPN 11%</li>
                                <li>• Diskon dan PPN diambil dari data master barang</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- No Referensi -->
                        <div class="space-y-2">
                            <label for="no_referensi" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-hashtag mr-1"></i>
                                No. Referensi
                            </label>
                            <input type="text" name="no_referensi" id="no_referensi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition-all duration-200 @error('no_referensi') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('no_referensi', $transaksi->no_referensi) }}" placeholder="Masukkan nomor referensi">
                            @error('no_referensi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-comment mr-1"></i>
                                Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none transition-all duration-200 @error('keterangan') border-red-500 ring-2 ring-red-200 @enderror"
                                      placeholder="Masukkan keterangan transaksi">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                            @error('keterangan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                        <a href="{{ route('transaksi.show', $transaksi->id) }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" id="submit-btn"
                                class="px-8 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i>
                            Update Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Format number with dots as thousand separators
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Calculate all amounts using barang data
function calculateAll() {
    const qty = parseInt(document.getElementById('qty').value) || 0;
    const barangSelect = document.getElementById('barang_id');
    const jenisSelect = document.getElementById('jenis_transaksi');
    
    if (!barangSelect.value || !jenisSelect.value) {
        // Reset all displays if no barang or jenis selected
        document.getElementById('subtotal').textContent = 'Rp 0';
        document.getElementById('total-discount').textContent = '- Rp 0';
        document.getElementById('subtotal-after-disc').textContent = 'Rp 0';
        document.getElementById('ppn-amount').textContent = '+ Rp 0';
        document.getElementById('total-harga').textContent = 'Rp 0';
        return;
    }
    
    const selectedOption = barangSelect.options[barangSelect.selectedIndex];
    const costPrice = parseFloat(selectedOption.dataset.cost) || 0;
    const unitPrice = parseFloat(selectedOption.dataset.unit) || costPrice;
    const discPerUnit = parseFloat(selectedOption.dataset.disc) || 0;
    
    // Determine harga satuan based on transaction type
    let hargaSatuan = 0;
    if (jenisSelect.value === 'masuk') {
        hargaSatuan = costPrice;
    } else if (jenisSelect.value === 'keluar') {
        hargaSatuan = unitPrice;
    }
    
    // Calculate subtotal
    const subtotal = qty * hargaSatuan;
    document.getElementById('subtotal').textContent = 'Rp ' + formatNumber(subtotal);
    
    // Calculate total discount (discount per unit * qty)
    const totalDiscount = discPerUnit * qty;
    document.getElementById('total-discount').textContent = '- Rp ' + formatNumber(Math.round(totalDiscount));
    
    // Calculate subtotal after discount
    const subtotalAfterDisc = Math.max(0, subtotal - totalDiscount);
    document.getElementById('subtotal-after-disc').textContent = 'Rp ' + formatNumber(Math.round(subtotalAfterDisc));
    
    // Calculate PPN (11% only for keluar transactions)
    let ppnAmount = 0;
    if (jenisSelect.value === 'keluar') {
        ppnAmount = subtotalAfterDisc * 0.11;
    }
    document.getElementById('ppn-amount').textContent = '+ Rp ' + formatNumber(Math.round(ppnAmount));
    
    // Calculate final total
    const totalHarga = subtotalAfterDisc + ppnAmount;
    document.getElementById('total-harga').textContent = 'Rp ' + formatNumber(Math.round(totalHarga));
}

// Check stock availability
function checkStock() {
    const barangSelect = document.getElementById('barang_id');
    const qtyInput = document.getElementById('qty');
    const jenisSelect = document.getElementById('jenis_transaksi');
    const stockWarning = document.getElementById('stock-warning');
    const submitBtn = document.getElementById('submit-btn');
    
    if (barangSelect.value && jenisSelect.value === 'keluar') {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        const availableStock = parseInt(selectedOption.dataset.qty) || 0;
        const requestedQty = parseInt(qtyInput.value) || 0;
        
        if (requestedQty > availableStock) {
            stockWarning.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            stockWarning.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    } else {
        stockWarning.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Show barang information and update harga display
function showBarangInfo() {
    const barangSelect = document.getElementById('barang_id');
    const jenisSelect = document.getElementById('jenis_transaksi');
    const barangInfo = document.getElementById('barang-info');
    const hargaDisplay = document.getElementById('harga-display');
    
    if (barangSelect.value) {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        const stock = selectedOption.dataset.qty || '0';
        const costPrice = selectedOption.dataset.cost || '0';
        const unitPrice = selectedOption.dataset.unit || costPrice;
        const discAmount = selectedOption.dataset.disc || '0';
        const vendor = selectedOption.dataset.vendor || 'N/A';
        
        document.getElementById('current-stock').textContent = formatNumber(stock);
        document.getElementById('cost-price').textContent = 'Rp ' + formatNumber(costPrice);
        document.getElementById('unit-price').textContent = 'Rp ' + formatNumber(unitPrice);
        document.getElementById('vendor-name').textContent = vendor;
        
        // Update harga display based on transaction type
        if (jenisSelect.value === 'masuk') {
            hargaDisplay.textContent = 'Rp ' + formatNumber(costPrice) + ' (Harga Beli)';
        } else if (jenisSelect.value === 'keluar') {
            hargaDisplay.textContent = 'Rp ' + formatNumber(unitPrice) + ' (Harga Jual)';
        } else {
            hargaDisplay.textContent = 'Pilih jenis transaksi';
        }
        
        barangInfo.classList.remove('hidden');
        calculateAll();
    } else {
        barangInfo.classList.add('hidden');
        hargaDisplay.textContent = 'Pilih barang dan jenis transaksi';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Event listeners
    document.getElementById('barang_id').addEventListener('change', function() {
        showBarangInfo();
        checkStock();
    });
    
    document.getElementById('jenis_transaksi').addEventListener('change', function() {
        showBarangInfo();
        checkStock();
    });
    
    document.getElementById('qty').addEventListener('input', function() {
        calculateAll();
        checkStock();
    });
    
    // Initial calculations
    if (document.getElementById('barang_id').value) {
        showBarangInfo();
    }
    calculateAll();
});
</script>
@endsection