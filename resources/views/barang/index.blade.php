@extends('layouts.dashboard')

@section('title', 'Data Barang')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header title="Data Barang" subtitle="Kelola data barang dan inventory" :showTabs="true"
        activeTab="barang" :showBanner="true" />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Success Alert -->
        @if (session('success'))
            <div id="alert-success" class="mb-6 flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('alert-success').remove()"
                    class="ml-3 text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Barang</h3>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_count'], 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Item terdaftar</p>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm cursor-pointer hover:bg-orange-50 transition-colors" onclick="filterLowStock()">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Stok Menipis</h3>
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">
                    {{ number_format($stats['low_stock_count'], 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Klik untuk filter</p>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Nilai Inventori</h3>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-rupiah-sign text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">Rp
                    {{ number_format($stats['total_inventory_value'], 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Total nilai stok</p>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Vendor</h3>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-truck text-purple-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['vendor_count'] }}</div>
                <p class="text-xs text-gray-500">Vendor aktif</p>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                @php
                    $user = auth()->user();
                @endphp

               @if(auth()->user()->role === 'admin')
                    <a href="{{ route('barang.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Barang
                    </a>

                    <button onclick="openImportModal()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Import Excel
                    </button>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Cari barang..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <!-- Filter by Stock Status -->
                <select id="stockFilter"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Stok</option>
                    <option value="low">Stok Menipis (&lt; 10)</option>
                    <option value="medium">Stok Sedang (10-50)</option>
                    <option value="high">Stok Aman (&gt; 50)</option>
                </select>

                <!-- Filter by Vendor -->
                <select id="vendorFilter"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Vendor</option>
                    @foreach ($stats['vendors'] as $vendor)
                        <option value="{{ $vendor }}">{{ $vendor }}</option>
                    @endforeach
                </select>

                <!-- Filter by Periode -->
                <select id="periodeFilter"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Periode</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">
                            {{ $i }} - {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>

                <button onclick="resetFilters()"
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset
                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600">Memuat data...</span>
        </div>

        <!-- Main Table Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-boxes mr-2 text-blue-500"></i>
                        Daftar Barang
                    </h3>
                    <div class="text-sm text-gray-500">
                        Total: <span id="totalCount">0</span> item
                    </div>
                </div>
            </div>

            <!-- Virtual Scrolling Container -->
            <div id="tableContainer" class="overflow-auto" style="height: 600px;">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Item</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Beli</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here via JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Load More Button -->
            <div id="loadMoreContainer" class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-center">
                <button id="loadMoreBtn" onclick="loadMoreData()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Muat Lebih Banyak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeImportModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-white">Import Data Barang</h3>
                            <p class="text-green-100 text-sm">Upload file Excel atau CSV untuk menambah data barang</p>
                        </div>
                    </div>
                    <button onclick="closeImportModal()" class="text-white hover:text-green-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="px-6 py-6">
                <!-- Progress Bar -->
                <div id="importProgress" class="hidden mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress Import</span>
                        <span id="importProgressPercent" class="text-sm text-gray-500">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div id="importProgressBar" class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                    </div>
                    <p id="importProgressText" class="text-sm text-gray-600 mt-2 flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses file...
                    </p>
                </div>

                <!-- File Upload Area -->
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="relative">
                        <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" 
                               class="hidden" onchange="handleFileSelect(this)">
                        
                        <!-- Drop Zone -->
                        <label for="importFile" id="dropZone" class="cursor-pointer block">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-green-400 hover:bg-green-50 transition-all duration-300 group">
                                <div class="space-y-4">
                                    <!-- Upload Icon -->
                                    <div class="mx-auto w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    
                                    <!-- Upload Text -->
                                    <div>
                                        <p class="text-lg font-semibold text-gray-700 group-hover:text-green-700 transition-colors">
                                            Pilih file atau seret ke sini
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Mendukung format Excel (.xlsx, .xls) dan CSV
                                        </p>
                                    </div>
                                    
                                    <!-- File Size Info -->
                                    <div class="flex items-center justify-center space-x-4 text-xs text-gray-400">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Maksimal 10MB
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Aman & Terenkripsi
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </form>

                <!-- Selected File Display -->
                <div id="selectedFile" class="hidden mt-4">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p id="fileName" class="text-sm font-semibold text-green-800"></p>
                                    <p id="fileSize" class="text-xs text-green-600"></p>
                                </div>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-green-600 hover:text-green-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Import Tips -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Import Data:</h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                <li class="flex items-center">
                                    <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Pastikan kolom header sesuai dengan format yang dibutuhkan
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Data duplikat akan diperbarui secara otomatis
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Proses import akan berjalan di background
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Data Anda aman dan terproteksi
                    </span>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="closeImportModal()"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </button>
                    <button type="button" id="importBtn" onclick="submitImport()" disabled
                            class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg text-sm font-medium hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:from-gray-400 disabled:to-gray-400 disabled:cursor-not-allowed transition-all duration-200 flex items-center">
                        <span id="importBtnText">Mulai Import</span>
                        <div id="importBtnSpinner" class="hidden ml-2 w-4 h-4">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Hapus Barang</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus barang "<span id="deleteItemName" class="font-semibold"></span>"? 
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Virtual Scrolling Variables
        let allData = [];
        let filteredData = [];
        let currentOffset = 0;
        let isLoading = false;
        let hasMoreData = true;
        let currentFilters = {
            search: '',
            vendor: '',
            periode: '',
            stock: ''
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadInitialData();
            setupInfiniteScroll();
            setupAutoFilters();
        });

        // Setup automatic filters (no need to click Filter button)
        function setupAutoFilters() {
            // Search input with debounce
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 500);
            });

            // Stock filter
            document.getElementById('stockFilter').addEventListener('change', function() {
                applyFilters();
            });

            // Vendor filter
            document.getElementById('vendorFilter').addEventListener('change', function() {
                applyFilters();
            });

            // Periode filter
            document.getElementById('periodeFilter').addEventListener('change', function() {
                applyFilters();
            });
        }

        // Filter low stock items (called from stats card)
        function filterLowStock() {
            document.getElementById('stockFilter').value = 'low';
            applyFilters();
        }

        // Load initial data
        function loadInitialData() {
            loadData(true);
        }

        // Load data from server
        function loadData(reset = false) {
            if (isLoading) return;
            
            isLoading = true;
            document.getElementById('loadingIndicator').classList.remove('hidden');
            
            if (reset) {
                currentOffset = 0;
                allData = [];
            }

            const params = new URLSearchParams({
                offset: currentOffset,
                limit: 100,
                search: currentFilters.search,
                vendor: currentFilters.vendor,
                periode: currentFilters.periode,
                stock: currentFilters.stock
            });

            fetch(`{{ route('barang.index') }}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (reset) {
                    allData = data.data;
                    document.getElementById('tableBody').innerHTML = '';
                } else {
                    allData = allData.concat(data.data);
                }
                
                hasMoreData = data.hasMore;
                currentOffset += data.data.length;
                
                renderData(data.data, !reset);
                updateTotalCount(data.total);
                updateLoadMoreButton();
            })
            .catch(error => {
                console.error('Error loading data:', error);
                showNotification('error', 'Gagal memuat data');
            })
            .finally(() => {
                isLoading = false;
                document.getElementById('loadingIndicator').classList.add('hidden');
            });
        }

        // Render data to table
        function renderData(data, append = false) {
            const tbody = document.getElementById('tableBody');
            
            if (!append) {
                tbody.innerHTML = '';
            }

            data.forEach((item, index) => {
                const row = createTableRow(item, currentOffset - data.length + index + 1);
                tbody.appendChild(row);
            });
        }

        // Create table row
        function createTableRow(item, rowNumber) {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors duration-150';
            row.dataset.periode = item.periode || '';

            // Determine stock status
            let stockBadge = '';
            if (item.qty < 10) {
                stockBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    ${parseInt(item.qty).toLocaleString()}
                </span>`;
            } else if (item.qty < 50) {
                stockBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    ${parseInt(item.qty).toLocaleString()}
                </span>`;
            } else {
                stockBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>
                    ${parseInt(item.qty).toLocaleString()}
                </span>`;
            }

            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">${rowNumber}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${item.no || 'N/A'}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${item.nama_item}</div>
                    ${item.description ? `<div class="text-sm text-gray-500 truncate max-w-xs" title="${item.description}">
                        ${item.description.substring(0, 50)}${item.description.length > 50 ? '...' : ''}
                    </div>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">${stockBadge}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-green-600">
                        Rp ${parseInt(item.cost_price).toLocaleString('id-ID')}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-truck mr-1"></i>
                        ${item.vendor || 'N/A'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="relative inline-block text-left">
                        <button type="button"
                            class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full hover:bg-gray-100 transition-colors"
                            onclick="toggleDropdown('dropdown-${item.id}')">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                        <div id="dropdown-${item.id}"
                            class="hidden absolute right-0 z-10 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-200">
                            <div class="py-1">
                                <a href="/barang/${item.id}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="/barang/${item.id}/edit"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <button onclick="openDeleteModal('${item.id}', '${item.nama_item.replace(/'/g, "\\'")}')"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            `;

            return row;
        }

        // Setup infinite scroll
        function setupInfiniteScroll() {
            const container = document.getElementById('tableContainer');
            container.addEventListener('scroll', function() {
                if (container.scrollTop + container.clientHeight >= container.scrollHeight - 100) {
                    if (hasMoreData && !isLoading) {
                        loadMoreData();
                    }
                }
            });
        }

        // Load more data
        function loadMoreData() {
            if (hasMoreData && !isLoading) {
                loadData(false);
            }
        }

        // Apply filters
        function applyFilters() {
            currentFilters.search = document.getElementById('searchInput').value;
            currentFilters.vendor = document.getElementById('vendorFilter').value;
            currentFilters.periode = document.getElementById('periodeFilter').value;
            currentFilters.stock = document.getElementById('stockFilter').value;
            
            loadData(true);
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('vendorFilter').value = '';
            document.getElementById('periodeFilter').value = '';
            document.getElementById('stockFilter').value = '';
            
            currentFilters = {
                search: '',
                vendor: '',
                periode: '',
                stock: ''
            };
            
            loadData(true);
        }

        // Update total count
        function updateTotalCount(total) {
            document.getElementById('totalCount').textContent = total.toLocaleString('id-ID');
        }

        // Update load more button
        function updateLoadMoreButton() {
            const loadMoreContainer = document.getElementById('loadMoreContainer');
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            
            if (hasMoreData) {
                loadMoreContainer.classList.remove('hidden');
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Muat Lebih Banyak
                `;
            } else {
                loadMoreBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Semua Data Telah Dimuat
                `;
                loadMoreBtn.disabled = true;
            }
        }

        // Dropdown functions
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

            allDropdowns.forEach(d => {
                if (d.id !== dropdownId) d.classList.add('hidden');
            });

            dropdown.classList.toggle('hidden');
        }

        // Delete modal functions
        function openDeleteModal(id, name) {
            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteForm').action = `/barang/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Import modal functions
        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Reset form
            document.getElementById('importForm').reset();
            document.getElementById('selectedFile').classList.add('hidden');
            document.getElementById('importBtn').disabled = true;
            document.getElementById('importProgress').classList.add('hidden');
        }

        // Handle file selection
function handleFileSelect(input) {
    const file = input.files[0];
    const selectedFileDiv = document.getElementById('selectedFile');
    const fileNameSpan = document.getElementById('fileName');
    const fileSizeSpan = document.getElementById('fileSize');
    const importBtn = document.getElementById('importBtn');
    const dropZone = document.getElementById('dropZone');
    
    if (file) {
        // Validate file type
        const allowedTypes = ['.xlsx', '.xls', '.csv'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            showNotification('error', 'Format file tidak didukung. Gunakan Excel (.xlsx, .xls) atau CSV');
            input.value = '';
            return;
        }
        
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            showNotification('error', 'Ukuran file terlalu besar. Maksimal 10MB');
            input.value = '';
            return;
        }
        
        // Format file size
        const formatFileSize = (bytes) => {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        };
        
        fileNameSpan.textContent = file.name;
        fileSizeSpan.textContent = formatFileSize(file.size);
        selectedFileDiv.classList.remove('hidden');
        importBtn.disabled = false;
        
        // Update drop zone appearance
        dropZone.classList.add('border-green-400', 'bg-green-50');
    } else {
        selectedFileDiv.classList.add('hidden');
        importBtn.disabled = true;
        dropZone.classList.remove('border-green-400', 'bg-green-50');
    }
}

// Enhanced submit import with better progress
function submitImport() {
    const fileInput = document.getElementById('importFile');
    const file = fileInput.files[0];
    
    if (!file) {
        showNotification('error', 'Pilih file terlebih dahulu');
        return;
    }

    // Show progress
    const importBtn = document.getElementById('importBtn');
    const importBtnText = document.getElementById('importBtnText');
    const importBtnSpinner = document.getElementById('importBtnSpinner');
    const importProgress = document.getElementById('importProgress');
    const importProgressBar = document.getElementById('importProgressBar');
    const importProgressText = document.getElementById('importProgressText');
    const importProgressPercent = document.getElementById('importProgressPercent');
    
    importBtn.disabled = true;
    importBtnText.textContent = 'Mengimpor...';
    importBtnSpinner.classList.remove('hidden');
    importProgress.classList.remove('hidden');
    
    // Enhanced progress simulation
    let progress = 0;
    const progressSteps = [
        { progress: 15, text: 'Memvalidasi file...' },
        { progress: 35, text: 'Membaca data...' },
        { progress: 60, text: 'Memproses data...' },
        { progress: 85, text: 'Menyimpan ke database...' },
        { progress: 95, text: 'Menyelesaikan...' }
    ];
    
    let currentStep = 0;
    const progressInterval = setInterval(() => {
        if (currentStep < progressSteps.length) {
            const step = progressSteps[currentStep];
            progress = step.progress;
            importProgressBar.style.width = progress + '%';
            importProgressPercent.textContent = progress + '%';
            importProgressText.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ${step.text}
            `;
            currentStep++;
        }
    }, 800);

    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch('{{ route("barang.import") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        clearInterval(progressInterval);
        importProgressBar.style.width = '100%';
        importProgressPercent.textContent = '100%';
        importProgressText.innerHTML = `
            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Import berhasil diselesaikan!
        `;
        
        setTimeout(() => {
            if (data.success) {
                showNotification('success', data.message);
                closeImportModal();
                loadData(true); // Reload data
                
                // Show detailed results if available
                if (data.data) {
                    const details = `
                        📊 Hasil Import: ${data.data.total_data || 0} total | 
                        ✅ ${data.data.berhasil || 0} berhasil | 
                        🔄 ${data.data.diperbarui || 0} diperbarui | 
                        ❌ ${data.data.gagal || 0} gagal
                    `;
                    setTimeout(() => {
                        showNotification('info', details);
                    }, 2000);
                }
            } else {
                showNotification('error', data.message || 'Terjadi kesalahan saat import');
            }
        }, 1000);
    })
    .catch(error => {
        clearInterval(progressInterval);
        console.error('Import error:', error);
        showNotification('error', 'Terjadi kesalahan saat import: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        setTimeout(() => {
            importBtn.disabled = false;
            importBtnText.textContent = 'Mulai Import';
            importBtnSpinner.classList.add('hidden');
        }, 2000);
    });
}

        // Notification function
        function showNotification(type, message) {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
                           type === 'info' ? 'bg-blue-50 border-blue-200 text-blue-800' :
                           'bg-red-50 border-red-200 text-red-800';
            
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${bgColor} border max-w-md`;

            const icon = type === 'success' ? 
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' :
                type === 'info' ?
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';

            notification.innerHTML = `
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${icon}
                    </svg>
                    <div class="flex-1">
                        <span class="font-medium text-sm">${message}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-current opacity-70 hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[onclick*="toggleDropdown"]')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                    d.classList.add('hidden');
                });
            }
        });

        // Close modals when pressing Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
                closeImportModal();
            }
        });

        // Drag and drop functionality
        const importModal = document.getElementById('importModal');
        const fileInput = document.getElementById('importFile');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            importModal.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            importModal.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            importModal.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            const dropZone = importModal.querySelector('.border-dashed');
            if (dropZone) {
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            }
        }

        function unhighlight(e) {
            const dropZone = importModal.querySelector('.border-dashed');
            if (dropZone) {
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            }
        }

        importModal.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(fileInput);
            }
        }
    </script>
@endpush
@endsection
