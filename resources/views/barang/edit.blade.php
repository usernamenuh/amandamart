@extends('layouts.dashboard')

@section('title', 'Edit Barang')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Edit Barang" 
        subtitle="Perbarui informasi data barang"
        :showTabs="true"
        activeTab="barang"
        :showBanner="true"
    />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('barang.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Data Barang
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500">{{ Str::limit($barang->nama_item, 40) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Form Edit Barang</h3>
                            <p class="text-sm text-gray-600 mt-1">Perbarui informasi barang: {{ $barang->nama_item }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('barang.show', $barang->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Detail
                        </a>
                        <a href="{{ route('barang.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-8">
    @csrf
    @method('PUT')
    
    <!-- Basic Information Section -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Item ID -->
            <div class="space-y-2">
                <label for="itemid" class="block text-sm font-semibold text-gray-700">Item ID</label>
                <input type="text" name="itemid" id="itemid" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('itemid') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('itemid', $barang->itemid) }}" placeholder="Masukkan Item ID">
                @error('itemid')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Nama Item -->
            <div class="space-y-2">
                <label for="nama_item" class="block text-sm font-semibold text-gray-700">Nama Item <span class="text-red-500">*</span></label>
                <input type="text" name="nama_item" id="nama_item" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('nama_item') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('nama_item', $barang->nama_item) }}" placeholder="Masukkan nama item" required>
                @error('nama_item')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Barcode -->
            <div class="space-y-2">
                <label for="barcode" class="block text-sm font-semibold text-gray-700">Barcode</label>
                <input type="text" name="barcode" id="barcode" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('barcode') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('barcode', $barang->barcode) }}" placeholder="Masukkan barcode">
                @error('barcode')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- No -->
            <div class="space-y-2">
                <label for="no" class="block text-sm font-semibold text-gray-700">No</label>
                <input type="text" name="no" id="no" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('no') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('no', $barang->no) }}" placeholder="Masukkan nomor">
                @error('no')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Unit ID -->
            <div class="space-y-2">
                <label for="unitid" class="block text-sm font-semibold text-gray-700">Unit ID</label>
                <input type="text" name="unitid" id="unitid" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('unitid') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('unitid', $barang->unitid) }}" placeholder="Masukkan unit ID">
                @error('unitid')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Quantity -->
            <div class="space-y-2">
                <label for="qty" class="block text-sm font-semibold text-gray-700">Quantity <span class="text-red-500">*</span></label>
                <input type="number" name="qty" id="qty" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('qty') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('qty', $barang->qty) }}" min="0" placeholder="0" required>
                @error('qty')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <!-- Vendor & Category Section -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Vendor & Kategori</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Vendor -->
            <div class="space-y-2">
                <label for="vendor" class="block text-sm font-semibold text-gray-700">Vendor</label>
                <input type="text" name="vendor" id="vendor" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('vendor') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('vendor', $barang->vendor) }}" placeholder="Masukkan nama vendor">
                @error('vendor')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Vendor ID -->
            <div class="space-y-2">
                <label for="vendor_id" class="block text-sm font-semibold text-gray-700">Vendor ID</label>
                <input type="text" name="vendor_id" id="vendor_id" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('vendor_id') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('vendor_id', $barang->vendor_id) }}" placeholder="Masukkan vendor ID">
                @error('vendor_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Vendor Name -->
            <div class="space-y-2">
                <label for="vend_name" class="block text-sm font-semibold text-gray-700">Vendor Name</label>
                <input type="text" name="vend_name" id="vend_name" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('vend_name') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('vend_name', $barang->vend_name) }}" placeholder="Masukkan nama vendor lengkap">
                @error('vend_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Department ID -->
            <div class="space-y-2">
                <label for="dept_id" class="block text-sm font-semibold text-gray-700">Department ID</label>
                <input type="text" name="dept_id" id="dept_id" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('dept_id') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('dept_id', $barang->dept_id) }}" placeholder="Masukkan department ID">
                @error('dept_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Department Description -->
            <div class="space-y-2">
                <label for="dept_description" class="block text-sm font-semibold text-gray-700">Kategori/Departemen</label>
                <input type="text" name="dept_description" id="dept_description" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('dept_description') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('dept_description', $barang->dept_description) }}" placeholder="Masukkan kategori barang">
                @error('dept_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Category ID -->
            <div class="space-y-2">
                <label for="ctgry_id" class="block text-sm font-semibold text-gray-700">Category ID</label>
                <input type="text" name="ctgry_id" id="ctgry_id" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('ctgry_id') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('ctgry_id', $barang->ctgry_id) }}" placeholder="Masukkan category ID">
                @error('ctgry_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <!-- Pricing Section - SIMPLIFIED -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            Informasi Harga 
            <span class="text-sm font-normal text-gray-600">(Field lain akan dihitung otomatis)</span>
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Cost Price -->
            <div class="space-y-2">
                <label for="cost_price" class="block text-sm font-semibold text-gray-700">Harga Beli <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                    <input type="text" name="cost_price_display" id="cost_price_display" 
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('cost_price') border-red-500 ring-2 ring-red-200 @enderror" 
                           value="{{ old('cost_price', $barang->cost_price) ? number_format(old('cost_price', $barang->cost_price), 0, ',', '.') : '' }}" placeholder="0">
                <input type="hidden" name="cost_price" id="cost_price" value="{{ old('cost_price', $barang->cost_price) }}">
            </div>
            @error('cost_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Unit Price -->
        <div class="space-y-2">
            <label for="unit_price" class="block text-sm font-semibold text-gray-700">Harga Jual</label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                <input type="text" name="unit_price_display" id="unit_price_display" 
                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('unit_price') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('unit_price', $barang->unit_price) ? number_format(old('unit_price', $barang->unit_price), 0, ',', '.') : '' }}" placeholder="0">
                <input type="hidden" name="unit_price" id="unit_price" value="{{ old('unit_price', $barang->unit_price) }}">
            </div>
            @error('unit_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Discount Amount -->
        <div class="space-y-2">
            <label for="disc_amt" class="block text-sm font-semibold text-gray-700">Discount Amount</label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                <input type="text" name="disc_amt_display" id="disc_amt_display" 
                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('disc_amt') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('disc_amt', $barang->disc_amt) ? number_format(old('disc_amt', $barang->disc_amt), 0, ',', '.') : '' }}" placeholder="0">
                <input type="hidden" name="disc_amt" id="disc_amt" value="{{ old('disc_amt', $barang->disc_amt) }}">
            </div>
            @error('disc_amt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <!-- Auto-calculated fields info -->
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h5 class="text-sm font-semibold text-blue-900 mb-1">Field yang Dihitung Otomatis:</h5>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• <strong>Total Cost:</strong> Harga Beli × Quantity</li>
                    <li>• <strong>Total Inc PPN:</strong> Harga Beli + (Harga Beli × 11%)</li>
                    <li>• <strong>Gross Amount:</strong> Harga Jual × Quantity</li>
                    <li>• <strong>Sales After Discount:</strong> Gross Amount - Discount</li>
                    <li>• <strong>Sales VAT:</strong> Sales After Discount × 11%</li>
                    <li>• <strong>Net Sales Before Tax:</strong> Sales After Discount - Sales VAT</li>
                    <li>• <strong>Margin:</strong> Net Sales Before Tax - Total Cost</li>
                    <li>• <strong>Margin Percent:</strong> (Margin ÷ Total Cost) × 100</li>
                </ul>
            </div>
        </div>
    </div>
</div>

    <!-- Location & Time Section -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Lokasi & Waktu</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Site -->
            <div class="space-y-2">
                <label for="site" class="block text-sm font-semibold text-gray-700">Site/Lokasi</label>
                <input type="text" name="site" id="site" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('site') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('site', $barang->site) }}" placeholder="Masukkan lokasi/site">
                @error('site')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Periode -->
            <div class="space-y-2">
                <label for="periode" class="block text-sm font-semibold text-gray-700">Periode</label>
                <select name="periode" id="periode" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('periode') border-red-500 ring-2 ring-red-200 @enderror">
                    <option value="">Pilih Periode</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('periode', $barang->periode) == $i ? 'selected' : '' }}>
                            {{ $i }} - {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
                @error('periode')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Date -->
            <div class="space-y-2">
                <label for="date" class="block text-sm font-semibold text-gray-700">Date</label>
                <input type="date" name="date" id="date" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('date') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('date', $barang->date ? \DateTime::createFromFormat('Y-m-d', '1900-01-01')->add(new \DateInterval('P' . ($barang->date - 1) . 'D'))->format('Y-m-d') : '') }}">
                @error('date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Time -->
            <div class="space-y-2">
                <label for="time" class="block text-sm font-semibold text-gray-700">Time</label>
                <input type="time" name="time" id="time" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('time') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('time', $barang->time ? sprintf('%02d:%02d', floor($barang->time * 24), ($barang->time * 24 * 60) % 60) : '') }}">
                @error('time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Consignment -->
            <div class="space-y-2">
                <label for="consignment" class="block text-sm font-semibold text-gray-700">Consignment</label>
                <input type="text" name="consignment" id="consignment" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('consignment') border-red-500 ring-2 ring-red-200 @enderror" 
                       value="{{ old('consignment', $barang->consignment) }}" placeholder="Masukkan consignment">
                @error('consignment')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label for="description" class="block text-sm font-semibold text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-all duration-200 @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                          placeholder="Masukkan deskripsi barang">{{ old('description', $barang->description) }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
        <a href="{{ route('barang.show', $barang->id) }}" 
           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 font-medium">
            Batal
        </a>
        <button type="submit" 
                class="px-8 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Update Barang
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
    });

    displayInput.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            e.preventDefault();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Setup price inputs (only the ones user can input)
    const priceFields = ['cost_price', 'unit_price', 'disc_amt'];
    
    priceFields.forEach(field => {
        const displayInput = document.getElementById(field + '_display');
        const hiddenInput = document.getElementById(field);
        if (displayInput && hiddenInput) {
            setupPriceInput(displayInput, hiddenInput);
        }
    });
});
</script>
@endsection
