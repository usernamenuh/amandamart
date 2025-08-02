@extends('layouts.dashboard')

@section('title', 'Data Barang')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Reusable Header Component -->
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
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($barangs->count(), 0, ',', '.') }}</div>
                    <p class="text-xs text-gray-500">Item terdaftar</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Stok Menipis</h3>
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-orange-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ number_format($barangs->where('qty', '<', 10)->count(), 0, ',', '.') }}</div>
                    <p class="text-xs text-gray-500">Perlu restock</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Nilai Inventori</h3>
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-rupiah-sign text-green-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">Rp
                        {{ number_format($barangs->sum('total_cost'), 0, ',', '.') }}</div>
                    <p class="text-xs text-gray-500">Total nilai stok</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Vendor</h3>
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-truck text-purple-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $barangs->pluck('vendor')->filter()->unique()->count() }}</div>
                    <p class="text-xs text-gray-500">Vendor aktif</p>
                </div>
            </div>

            <!-- Action Buttons and Search -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    @php
                        $user = auth()->user();
                    @endphp

                    @if (!isset($user->role) || $user->role !== 'owner')
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

                    <!-- Filter by Vendor -->
                    <select id="vendorFilter"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Vendor</option>
                        @foreach ($barangs->pluck('vendor')->filter()->unique()->sort() as $vendor)
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
                </div>
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
                            Total: <span class="font-medium text-gray-900">{{ $barangs->count() }}</span> item
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto" id="barang-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Item ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Item</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga Beli</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vendor</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($barangs as $i => $barang)
                                <tr class="hover:bg-gray-50 transition-colors duration-150" data-periode="{{ $barang->periode }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $i + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $barang->itemid ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $barang->nama_item }}</div>
                                        @if ($barang->description)
                                            <div class="text-sm text-gray-500 truncate max-w-xs" title="{{ $barang->description }}">
                                                {{ Str::limit($barang->description, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($barang->qty < 10)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    {{ number_format($barang->qty, 0) }}
                                                </span>
                                            @elseif($barang->qty < 50)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    {{ number_format($barang->qty, 0) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    {{ number_format($barang->qty, 0) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-green-600">
                                            Rp {{ number_format($barang->cost_price, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-truck mr-1"></i>
                                            {{ $barang->vendor ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full hover:bg-gray-100 transition-colors"
                                                onclick="toggleDropdown('dropdown-{{ $barang->id }}')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div id="dropdown-{{ $barang->id }}"
                                                class="hidden absolute right-0 z-10 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-200">
                                                <div class="py-1">
                                                    <a href="{{ route('barang.show', $barang->id) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat Detail
                                                    </a>
                                                    @if (!isset($user->role) || $user->role !== 'owner')
                                                        <a href="{{ route('barang.edit', $barang->id) }}"
                                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                            <svg class="w-4 h-4 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        <button onclick="openDeleteModal('{{ $barang->id }}', '{{ $barang->nama_item }}')"
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data barang</h3>
                                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan barang pertama Anda</p>
                                            @if (!isset($user->role) || $user->role !== 'owner')
                                                <a href="{{ route('barang.create') }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Tambah Barang Pertama
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 transition-opacity bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeImportModal()"></div>

            <div class="relative w-full max-w-lg mx-auto bg-white rounded-2xl shadow-xl">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl px-6 py-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Import Data Barang</h3>
                                <p class="text-sm text-blue-100">Upload file Excel untuk import data</p>
                            </div>
                        </div>
                        <button onclick="closeImportModal()" class="text-white hover:text-blue-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <form id="importForm" action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- File Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors mb-6"
                             ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);">
                            <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv" class="hidden" required>
                            <div id="uploadArea" class="cursor-pointer" onclick="document.getElementById('fileInput').click()">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Upload File Excel</h4>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium text-blue-600">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500">Excel (.xlsx, .xls) atau CSV hingga 10MB</p>
                            </div>

                            <!-- File Info -->
                            <div id="fileInfo" class="hidden mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span id="fileName" class="text-sm font-medium text-blue-900"></span>
                                    </div>
                                    <button type="button" onclick="clearFile()" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div id="progressContainer" class="hidden mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Mengimpor data...</span>
                                <span id="progressText" class="text-sm text-gray-500">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeImportModal()"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" id="importButton"
                                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 disabled:opacity-50">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Hapus Barang</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin menghapus barang "<span id="deleteItemName" class="font-semibold"></span>"?
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 transition-colors">
                            Hapus
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()"
                        class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Modal functions
            function openImportModal() {
                document.getElementById('importModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeImportModal() {
                document.getElementById('importModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                resetImportForm();
            }

            function resetImportForm() {
                document.getElementById('fileInput').value = '';
                document.getElementById('fileInfo').classList.add('hidden');
                document.getElementById('uploadArea').classList.remove('hidden');
                document.getElementById('progressContainer').classList.add('hidden');
            }

            function clearFile() {
                resetImportForm();
            }

            // File handling
            document.getElementById('fileInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    document.getElementById('fileName').textContent = file.name;
                    document.getElementById('fileInfo').classList.remove('hidden');
                    document.getElementById('uploadArea').classList.add('hidden');
                }
            });

            // Drag and drop
            function dragOverHandler(ev) {
                ev.preventDefault();
                ev.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
            }

            function dragLeaveHandler(ev) {
                ev.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
            }

            function dropHandler(ev) {
                ev.preventDefault();
                ev.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
                
                const files = ev.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('fileInput').files = files;
                    document.getElementById('fileName').textContent = files[0].name;
                    document.getElementById('fileInfo').classList.remove('hidden');
                    document.getElementById('uploadArea').classList.add('hidden');
                }
            }

            // Import form submission
            document.getElementById('importForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const progressContainer = document.getElementById('progressContainer');
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                const importButton = document.getElementById('importButton');

                progressContainer.classList.remove('hidden');
                importButton.disabled = true;
                importButton.innerHTML = '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Mengimpor...';

                // Simulate progress
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 30;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';
                    progressText.textContent = Math.round(progress) + '%';
                }, 200);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    clearInterval(progressInterval);
                    progressBar.style.width = '100%';
                    progressText.textContent = '100%';

                    setTimeout(() => {
                        closeImportModal();
                        if (data.success) {
                            showNotification('success', data.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification('error', data.message);
                        }
                    }, 500);
                })
                .catch(error => {
                    clearInterval(progressInterval);
                    showNotification('error', 'Terjadi kesalahan saat import!');
                    resetImportForm();
                });
            });

            // Search and filter
            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('vendorFilter').addEventListener('change', filterTable);
            document.getElementById('periodeFilter').addEventListener('change', filterTable);

            function filterTable() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const selectedVendor = document.getElementById('vendorFilter').value.toLowerCase();
                const selectedPeriode = document.getElementById('periodeFilter').value;
                const tableRows = document.querySelectorAll('#barang-table tbody tr');

                tableRows.forEach(row => {
                    if (row.cells.length === 1) return; // Skip empty state row

                    const namaItem = row.cells[2].textContent.toLowerCase();
                    const itemId = row.cells[1].textContent.toLowerCase();
                    const vendor = row.cells[5].textContent.toLowerCase();
                    
                    // Get periode from data attribute or add it to the row
                    const periode = row.dataset.periode || '';

                    const matchesSearch = namaItem.includes(searchTerm) || itemId.includes(searchTerm);
                    const matchesVendor = selectedVendor === '' || vendor.includes(selectedVendor);
                    const matchesPeriode = selectedPeriode === '' || periode === selectedPeriode;

                    row.style.display = (matchesSearch && matchesVendor && matchesPeriode) ? '' : 'none';
                });
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

            // Delete modal
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

            // Notification
            function showNotification(type, message) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${
                    type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success' 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
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

            // Auto hide alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('#alert-success, #alert-danger');
                alerts.forEach(alert => {
                    if (alert) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => alert.remove(), 300);
                    }
                });
            }, 5000);
        </script>
    @endpush
@endsection
