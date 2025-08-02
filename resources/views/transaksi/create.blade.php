@extends('layouts.dashboard')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Tambah Transaksi" 
        subtitle="Tambahkan transaksi masuk atau keluar barang"
        :showTabs="true"
        activeTab="transaksi"
        :showBanner="true"
    />

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('transaksi.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Data Transaksi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500">Tambah Transaksi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Form Tambah Transaksi</h3>
                            <p class="text-sm text-gray-600 mt-1">Diskon dan PPN menggunakan data dari master barang</p>
                        </div>
                    </div>
                    <a href="{{ route('transaksi.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal Transaksi -->
                        <div class="space-y-2">
                            <label for="tanggal_transaksi" class="block text-sm font-semibold text-gray-700">Tanggal Transaksi <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 @error('tanggal_transaksi') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                            @error('tanggal_transaksi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Jenis Transaksi -->
                        <div class="space-y-2">
                            <label for="jenis_transaksi" class="block text-sm font-semibold text-gray-700">Jenis Transaksi <span class="text-red-500">*</span></label>
                            <select name="jenis_transaksi" id="jenis_transaksi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 @error('jenis_transaksi') border-red-500 ring-2 ring-red-200 @enderror" required>
                                <option value="">Pilih Jenis Transaksi</option>
                                <option value="masuk" {{ old('jenis_transaksi') === 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                                <option value="keluar" {{ old('jenis_transaksi') === 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                            </select>
                            @error('jenis_transaksi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Barang Selection -->
                    <div class="space-y-2">
                        <label for="barang_id" class="block text-sm font-semibold text-gray-700">Pilih Barang <span class="text-red-500">*</span></label>
                        <select name="barang_id" id="barang_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 @error('barang_id') border-red-500 ring-2 ring-red-200 @enderror" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}
                                        data-qty="{{ $barang->qty }}" 
                                        data-cost="{{ $barang->cost_price }}" 
                                        data-unit="{{ $barang->unit_price }}"
                                        data-disc="{{ $barang->disc_amt }}"
                                        data-vat="{{ $barang->sales_vat }}"
                                        data-vendor="{{ $barang->vendor }}">
                                    {{ $barang->nama_item }} 
                                    @if($barang->itemid) - ID: {{ $barang->itemid }} @endif
                                    (Stok: {{ $barang->qty }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Barang Info Display -->
                    <div id="barang-info" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3">Informasi Barang:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700 font-medium">Stok Tersedia:</span>
                                <div id="current-stock" class="text-blue-900 font-semibold"></div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Harga Beli:</span>
                                <div id="cost-price" class="text-blue-900 font-semibold"></div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Diskon per Unit:</span>
                                <div id="disc-amount" class="text-blue-900 font-semibold"></div>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Vendor:</span>
                                <div id="vendor-name" class="text-blue-900 font-semibold"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Quantity -->
                        <div class="space-y-2">
                            <label for="qty" class="block text-sm font-semibold text-gray-700">Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="qty" id="qty" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 @error('qty') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('qty', 1) }}" min="1" placeholder="1" required>
                            @error('qty')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            <div id="stock-warning" class="hidden text-sm text-red-600 font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Quantity melebihi stok yang tersedia!
                            </div>
                        </div>

                        <!-- Harga Satuan -->
                        <div class="space-y-2">
                            <label for="harga_satuan" class="block text-sm font-semibold text-gray-700">Harga Satuan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                <input type="text" name="harga_satuan_display" id="harga_satuan_display" 
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 @error('harga_satuan') border-red-500 ring-2 ring-red-200 @enderror" 
                                       value="{{ old('harga_satuan') ? number_format(old('harga_satuan'), 0, ',', '.') : '' }}" placeholder="0">
                                <input type="hidden" name="harga_satuan" id="harga_satuan" value="{{ old('harga_satuan', 0) }}">
                            </div>
                            @error('harga_satuan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Calculation Preview -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Preview Perhitungan</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Subtotal (Qty × Harga):</span>
                                <span id="subtotal" class="font-semibold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Diskon Total:</span>
                                <span id="total-discount" class="font-semibold text-orange-600">- Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Subtotal Setelah Diskon:</span>
                                <span id="subtotal-after-disc" class="font-semibold text-blue-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">PPN (11%):</span>
                                <span id="ppn-amount" class="font-semibold text-purple-600">+ Rp 0</span>
                            </div>
                            <hr class="border-gray-300">
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-bold text-gray-800">TOTAL AKHIR:</span>
                                <span id="total-harga" class="font-bold text-green-600">Rp 0</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Perhitungan menggunakan diskon dan PPN dari data master barang
                        </p>
                    </div>

                    <!-- Additional Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- No Referensi -->
                        <div class="space-y-2">
                            <label for="no_referensi" class="block text-sm font-semibold text-gray-700">No. Referensi</label>
                            <input type="text" name="no_referensi" id="no_referensi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 @error('no_referensi') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('no_referensi') }}" placeholder="Masukkan nomor referensi">
                            @error('no_referensi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 @error('keterangan') border-red-500 ring-2 ring-red-200 @enderror"
                                      placeholder="Masukkan keterangan transaksi">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                        <a href="{{ route('transaksi.index') }}" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 font-medium">
                            Batal
                        </a>
                        <button type="submit" id="submit-btn"
                                class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Transaksi
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

// Setup price input formatting
function setupPriceInput(displayInput, hiddenInput) {
    displayInput.addEventListener('input', function(e) {
        let value = e.target.value;
        value = value.replace(/[^\d]/g, '');
        hiddenInput.value = value;
        
        if (value) {
            e.target.value = formatNumber(value);
        } else {
            e.target.value = '';
        }
        
        calculateAll();
    });

    displayInput.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            e.preventDefault();
        }
    });
}

// Calculate all amounts using barang data
function calculateAll() {
    const qty = parseInt(document.getElementById('qty').value) || 0;
    const harga = parseInt(document.getElementById('harga_satuan').value) || 0;
    const barangSelect = document.getElementById('barang_id');
    
    if (!barangSelect.value) {
        // Reset all displays if no barang selected
        document.getElementById('subtotal').textContent = 'Rp 0';
        document.getElementById('total-discount').textContent = '- Rp 0';
        document.getElementById('subtotal-after-disc').textContent = 'Rp 0';
        document.getElementById('ppn-amount').textContent = '+ Rp 0';
        document.getElementById('total-harga').textContent = 'Rp 0';
        return;
    }
    
    const selectedOption = barangSelect.options[barangSelect.selectedIndex];
    const discPerUnit = parseFloat(selectedOption.dataset.disc) || 0;
    
    // Calculate subtotal
    const subtotal = qty * harga;
    document.getElementById('subtotal').textContent = 'Rp ' + formatNumber(subtotal);
    
    // Calculate total discount (discount per unit * qty)
    const totalDiscount = discPerUnit * qty;
    document.getElementById('total-discount').textContent = '- Rp ' + formatNumber(Math.round(totalDiscount));
    
    // Calculate subtotal after discount
    const subtotalAfterDisc = subtotal - totalDiscount;
    document.getElementById('subtotal-after-disc').textContent = 'Rp ' + formatNumber(Math.max(0, subtotalAfterDisc));
    
    // Calculate PPN (11% from subtotal after discount)
    const ppnAmount = (Math.max(0, subtotalAfterDisc) * 11) / 100;
    document.getElementById('ppn-amount').textContent = '+ Rp ' + formatNumber(Math.round(ppnAmount));
    
    // Calculate final total
    const totalHarga = Math.max(0, subtotalAfterDisc) + ppnAmount;
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

// Show barang information
function showBarangInfo() {
    const barangSelect = document.getElementById('barang_id');
    const barangInfo = document.getElementById('barang-info');
    
    if (barangSelect.value) {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        const stock = selectedOption.dataset.qty || '0';
        const costPrice = selectedOption.dataset.cost || '0';
        const discAmount = selectedOption.dataset.disc || '0';
        const vendor = selectedOption.dataset.vendor || 'N/A';
        
        document.getElementById('current-stock').textContent = formatNumber(stock);
        document.getElementById('cost-price').textContent = 'Rp ' + formatNumber(costPrice);
        document.getElementById('disc-amount').textContent = 'Rp ' + formatNumber(discAmount);
        document.getElementById('vendor-name').textContent = vendor;
        
        // Auto-fill harga satuan with cost price for 'masuk' or unit price for 'keluar'
        const jenisTransaksi = document.getElementById('jenis_transaksi').value;
        const hargaSatuanDisplay = document.getElementById('harga_satuan_display');
        const hargaSatuanHidden = document.getElementById('harga_satuan');
        
        if (jenisTransaksi === 'masuk') {
            hargaSatuanDisplay.value = formatNumber(costPrice);
            hargaSatuanHidden.value = costPrice;
        } else if (jenisTransaksi === 'keluar') {
            const unitPrice = selectedOption.dataset.unit || costPrice;
            hargaSatuanDisplay.value = formatNumber(unitPrice);
            hargaSatuanHidden.value = unitPrice;
        }
        
        barangInfo.classList.remove('hidden');
        calculateAll();
    } else {
        barangInfo.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Setup price input
    const hargaDisplay = document.getElementById('harga_satuan_display');
    const hargaHidden = document.getElementById('harga_satuan');
    setupPriceInput(hargaDisplay, hargaHidden);
    
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
