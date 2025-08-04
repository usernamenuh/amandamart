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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4 animate-bounce-gentle">
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
                               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                        @endif
                        <a href="{{ route('barang.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md hover:scale-105">
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
                    <!-- No -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.1s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">No</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $barang->no ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Nama Item -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.2s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Nama Item</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $barang->nama_item }}</p>
                    </div>

                    <!-- Barcode -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.3s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Barcode</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->barcode ?? 'N/A' }}</p>
                    </div>

                    <!-- Quantity -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.4s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Quantity</h3>
                        <div class="flex items-center">
                            @if($barang->qty < 10)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 animate-pulse">
                                    <i class="fas fa-exclamation-triangle mr-2 animate-bounce"></i>
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
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.5s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Harga Beli</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-green-600 animate-number-count">
                                Rp {{ number_format($barang->cost_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Unit Price -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.6s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Harga Jual</h3>
                        <div class="flex items-center">
                            @if($barang->unit_price)
                                <span class="text-2xl font-bold text-blue-600 animate-number-count">
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
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.7s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Vendor</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-truck mr-2 animate-wiggle"></i>
                                {{ $barang->vendor ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.8s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Kategori/Departemen</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->dept_description ?? 'N/A' }}</p>
                    </div>

                    <!-- Site -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 0.9s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Site/Lokasi</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->site ?? 'N/A' }}</p>
                    </div>

                    <!-- Periode -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 1s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Periode</h3>
                        <p class="text-lg font-medium text-gray-900">{{ $barang->periode ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 1.1s">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-calculator mr-2 text-blue-600 animate-spin-slow"></i>
                        Informasi Finansial
                    </h3>
                    
                    <!-- Pricing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Total Cost (Before PPN) -->
                        <div class="text-center bg-white rounded-lg p-4 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 1.2s">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 animate-bounce-gentle">
                                <i class="fas fa-shopping-cart text-green-600 text-sm"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Total Biaya</h4>
                            <p class="text-xl font-bold text-green-600 animate-number-count">
                                Rp {{ number_format($barang->total_cost ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Sebelum PPN</p>
                        </div>

                        <!-- Total Including PPN -->
                        <div class="text-center bg-white rounded-lg p-4 shadow-sm border-2 border-blue-200 hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 1.3s">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2 animate-bounce-gentle">
                                <i class="fas fa-receipt text-blue-600 text-sm"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Total + PPN</h4>
                            <p class="text-xl font-bold text-blue-600 animate-number-count">
                                Rp {{ number_format($barang->total_inc_ppn ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Termasuk PPN 11%</p>
                        </div>

                        <!-- PPN Amount -->
                        <div class="text-center bg-white rounded-lg p-4 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 1.4s">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2 animate-bounce-gentle">
                                <i class="fas fa-percentage text-orange-600 text-sm animate-spin-slow"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Nilai PPN</h4>
                            @php
                                $ppnAmount = ($barang->total_inc_ppn ?? 0) - ($barang->total_cost ?? 0);
                            @endphp
                            <p class="text-xl font-bold text-orange-600 animate-number-count">
                                Rp {{ number_format($ppnAmount, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">11% dari total biaya</p>
                        </div>

                        <!-- Gross Amount -->
                        <div class="text-center bg-white rounded-lg p-4 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 1.5s">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2 animate-bounce-gentle">
                                <i class="fas fa-chart-line text-purple-600 text-sm"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Total Penjualan</h4>
                            <p class="text-xl font-bold text-purple-600 animate-number-count">
                                Rp {{ number_format($barang->gross_amt ?? 0, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Gross amount</p>
                        </div>
                    </div>

                    <!-- Sales Information with Enhanced Styling -->
                    @if($barang->sales_after_discount || $barang->sales_vat || $barang->net_sales_bef_tax)
                    <div class="relative animate-fade-in-up" style="animation-delay: 1.6s">
                        <!-- Decorative Divider -->
                        <div class="flex items-center my-8">
                            <div class="flex-grow border-t border-gray-300"></div>
                            <div class="flex-shrink-0 px-4">
                                <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-200 animate-pulse-gentle">
                                    <i class="fas fa-chart-bar text-indigo-600 text-sm animate-bounce-gentle"></i>
                                    <span class="text-sm font-semibold text-gray-700">Detail Penjualan</span>
                                </div>
                            </div>
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>

                        <!-- Sales Detail Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Sales After Discount -->
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 1.7s">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center animate-bounce-gentle">
                                        <i class="fas fa-tags text-green-600 text-sm"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Setelah Diskon</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-bold text-gray-900 animate-number-count">
                                        Rp {{ number_format($barang->sales_after_discount ?? 0, 0, ',', '.') }}
                                    </p>
                                    @if($barang->disc_amt)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                                        <p class="text-sm text-red-600 font-medium">
                                            Diskon: Rp {{ number_format($barang->disc_amt, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Sales VAT -->
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 1.8s">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center animate-bounce-gentle">
                                        <i class="fas fa-file-invoice text-blue-600 text-sm"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">PPN Penjualan</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-bold text-gray-900 animate-number-count">
                                        Rp {{ number_format($barang->sales_vat ?? 0, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                        <p class="text-sm text-blue-600 font-medium">11% dari penjualan</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Net Sales Before Tax -->
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 1.9s">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center animate-bounce-gentle">
                                        <i class="fas fa-calculator text-purple-600 text-sm"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Net Sales</p>
                                        <p class="text-xs text-gray-400">(Sebelum Pajak)</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-2xl font-bold text-gray-900 animate-number-count">
                                        Rp {{ number_format($barang->net_sales_bef_tax ?? 0, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                                        <p class="text-sm text-purple-600 font-medium">Penjualan bersih</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Margin Information with Enhanced Styling -->
                    @if($barang->margin || $barang->margin_percent)
                    <div class="relative mt-8 animate-fade-in-up" style="animation-delay: 2s">
                        <!-- Decorative Divider -->
                        <div class="flex items-center my-8">
                            <div class="flex-grow border-t border-gray-300"></div>
                            <div class="flex-shrink-0 px-4">
                                <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-200 animate-pulse-gentle">
                                    <i class="fas fa-chart-line text-emerald-600 text-sm animate-bounce-gentle"></i>
                                    <span class="text-sm font-semibold text-gray-700">Analisis Margin</span>
                                </div>
                            </div>
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>

                        <!-- Margin Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Margin Amount -->
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 2.1s">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 {{ ($barang->margin ?? 0) >= 0 ? 'bg-emerald-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center animate-bounce-gentle">
                                        <i class="fas fa-coins {{ ($barang->margin ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }} text-lg"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Margin</p>
                                        <p class="text-xs text-gray-400">(Rupiah)</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <p class="text-3xl font-bold {{ ($barang->margin ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }} animate-number-count">
                                        Rp {{ number_format($barang->margin ?? 0, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 {{ ($barang->margin ?? 0) >= 0 ? 'bg-emerald-400' : 'bg-red-400' }} rounded-full animate-pulse"></div>
                                        <p class="text-sm {{ ($barang->margin ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }} font-medium">
                                            {{ ($barang->margin ?? 0) >= 0 ? 'Keuntungan' : 'Kerugian' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Margin Percentage -->
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:scale-105 animate-card-float" style="animation-delay: 2.2s">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 {{ ($barang->margin_percent ?? 0) >= 0 ? 'bg-emerald-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center animate-bounce-gentle">
                                        <i class="fas fa-percentage {{ ($barang->margin_percent ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }} text-lg animate-spin-slow"></i>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Margin</p>
                                        <p class="text-xs text-gray-400">(Persentase)</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <p class="text-3xl font-bold {{ ($barang->margin_percent ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }} animate-number-count">
                                        {{ number_format($barang->margin_percent ?? 0, 2) }}%
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 {{ ($barang->margin_percent ?? 0) >= 0 ? 'bg-emerald-400' : 'bg-red-400' }} rounded-full animate-pulse"></div>
                                        <p class="text-sm {{ ($barang->margin_percent ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }} font-medium">
                                            {{ ($barang->margin_percent ?? 0) >= 0 ? 'Profit margin' : 'Loss margin' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Item ID & Unit ID -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 2.3s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Identifikasi Item</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Item ID:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $barang->itemid ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Unit ID:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $barang->unitid ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Category ID:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $barang->ctgry_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Vendor Information -->
                    <div class="bg-gray-50 rounded-xl p-6 animate-slide-up" style="animation-delay: 2.4s">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Informasi Vendor</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Vendor ID:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $barang->vendor_id ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Vendor Name:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $barang->vend_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Consignment:</span>
                                <span class="text-sm font-medium text-gray-900">
                                    @if($barang->consignment)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 animate-pulse-gentle">
                                            Ya
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Tidak
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($barang->description)
                <div class="bg-gray-50 rounded-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 2.5s">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Deskripsi</h3>
                    <p class="text-gray-900 leading-relaxed">{{ $barang->description }}</p>
                </div>
                @endif

                <!-- Metadata -->
                <div class="pt-6 border-t border-gray-200 animate-fade-in-up" style="animation-delay: 2.6s">
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
                        @if($barang->date)
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Tanggal Data: {{ $barang->date }}</span>
                        </div>
                        @endif
                        @if($barang->time)
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Waktu: {{ $barang->time }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes fadeInUp {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes cardFloat {
    from { 
        opacity: 0; 
        transform: translateY(20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

@keyframes bounceGentle {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-5px); }
    60% { transform: translateY(-3px); }
}

@keyframes wiggle {
    0%, 7% { transform: rotateZ(0); }
    15% { transform: rotateZ(-15deg); }
    20% { transform: rotateZ(10deg); }
    25% { transform: rotateZ(-10deg); }
    30% { transform: rotateZ(6deg); }
    35% { transform: rotateZ(-4deg); }
    40%, 100% { transform: rotateZ(0); }
}

@keyframes spinSlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes pulseGentle {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

@keyframes numberCount {
    from { 
        opacity: 0; 
        transform: scale(0.8); 
    }
    to { 
        opacity: 1; 
        transform: scale(1); 
    }
}

/* Animation Classes */
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out forwards;
    opacity: 0;
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
}

.animate-card-float {
    animation: cardFloat 0.8s ease-out forwards;
    opacity: 0;
}

.animate-bounce-gentle {
    animation: bounceGentle 2s infinite;
}

.animate-wiggle {
    animation: wiggle 2s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spinSlow 3s linear infinite;
}

.animate-pulse-gentle {
    animation: pulseGentle 2s ease-in-out infinite;
}

.animate-number-count {
    animation: numberCount 1s ease-out forwards;
}

/* Hover Effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Responsive Animations */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endsection
