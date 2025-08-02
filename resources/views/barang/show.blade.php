@extends('layouts.dashboard')

@section('title', 'Detail Barang')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Detail Barang" 
        subtitle="Informasi lengkap data barang"
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

        <!-- Detail Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Detail Barang</h3>
                            <p class="text-sm text-gray-600 mt-1">Informasi lengkap data barang</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        @if (!isset(auth()->user()->role) || auth()->user()->role !== 'owner')
                            <a href="{{ route('barang.edit', $barang->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                        @endif
                        <a href="{{ route('barang.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Item ID -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Item ID</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $barang->itemid ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Nama Item -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Nama Item</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $barang->nama_item }}</p>
                    </div>

                    <!-- Barcode -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Barcode</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->barcode ?? 'N/A' }}</p>
                    </div>

                    <!-- Quantity -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Quantity</h3>
                        <div class="flex items-center">
                            @if($barang->qty < 10)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    {{ number_format($barang->qty, 0) }}
                                </span>
                                <span class="ml-3 text-sm text-red-600 font-medium">Stok Menipis</span>
                            @elseif($barang->qty < 50)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ number_format($barang->qty, 0) }}
                                </span>
                                <span class="ml-3 text-sm text-yellow-600 font-medium">Stok Terbatas</span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ number_format($barang->qty, 0) }}
                                </span>
                                <span class="ml-3 text-sm text-green-600 font-medium">Stok Aman</span>
                            @endif
                        </div>
                    </div>

                    <!-- Cost Price -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Harga Beli</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($barang->cost_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Unit Price -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Harga Jual</h3>
                        <div class="flex items-center">
                            @if($barang->unit_price)
                                <span class="text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($barang->unit_price, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-lg text-gray-400">Belum diset</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Vendor -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Vendor</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-truck mr-2"></i>
                                {{ $barang->vendor ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Kategori/Departemen</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->dept_description ?? 'N/A' }}</p>
                    </div>

                    <!-- Site -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Site/Lokasi</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->site ?? 'N/A' }}</p>
                    </div>

                    <!-- Periode -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Periode</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->periode ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Financial Information -->
                @if($barang->total_cost || $barang->gross_amt || $barang->margin)
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Finansial</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Cost -->
                        <div class="text-center">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Total Biaya</h4>
                            <p class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($barang->total_cost ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Gross Amount -->
                        <div class="text-center">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Total Penjualan</h4>
                            <p class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($barang->gross_amt ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Margin -->
                        <div class="text-center">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Margin</h4>
                            <p class="text-xl font-bold {{ ($barang->margin ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($barang->margin ?? 0, 0, ',', '.') }}
                                @if($barang->margin_percent)
                                    <span class="text-sm">({{ number_format($barang->margin_percent, 2) }}%)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Description -->
                @if($barang->description)
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Deskripsi</h3>
                    <p class="text-gray-900 leading-relaxed">{{ $barang->description }}</p>
                </div>
                @endif

                <!-- Metadata -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Dibuat: {{ $barang->created_at ? $barang->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Diupdate: {{ $barang->updated_at ? $barang->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
