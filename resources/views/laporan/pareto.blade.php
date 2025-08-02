@extends('layouts.dashboard')

@section('title', 'Analisis Pareto ABC')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Analisis Pareto ABC {{ $periodeInfo ? '- ' . $periodeInfo['label'] : '- Semua Periode' }}" 
        subtitle="{{ $periodeInfo ? 'Klasifikasi barang periode ' . $periodeInfo['nama_bulan'] : 'Klasifikasi berdasarkan semua barang dalam inventori' }}"
        :showTabs="true"
        activeTab="analisis"
        :showBanner="true"
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        
        <!-- Period Info Banner -->
        @if($periodeInfo)
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-6 mb-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Analisis Periode {{ $periodeInfo['nama_bulan'] }}</h3>
                            <p class="text-blue-100">Barang dengan periode {{ $periodeInfo['nama_bulan'] }} ({{ $periodeInfo['periode'] }})</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ number_format($stats['total_barang']) }}</div>
                        <div class="text-blue-100 text-sm">Barang Periode Ini</div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 mb-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-globe text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Analisis Semua Periode</h3>
                            <p class="text-green-100">Semua barang dari periode Januari - Desember</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ number_format($stats['total_barang']) }}</div>
                        <div class="text-green-100 text-sm">Total Barang</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Total Barang</h3>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-blue-600"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_barang'], 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500 mt-1">{{ $periodeInfo ? 'Periode ' . $periodeInfo['nama_bulan'] : 'Semua periode' }}</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Total Qty</h3>
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cubes text-green-600"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_qty_inventori'], 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500 mt-1">Total unit stok</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Nilai Inventori</h3>
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-purple-600"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_nilai_inventori'], 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500 mt-1">Total nilai stok</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Basis Analisis</h3>
                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas {{ $sortBy === 'quantity' ? 'fa-cubes' : 'fa-coins' }} text-orange-600"></i>
                    </div>
                </div>
                <div class="text-lg font-bold text-gray-900">{{ $sortBy === 'quantity' ? 'Kuantitas' : 'Nilai' }}</div>
                <p class="text-xs text-gray-500 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-filter mr-2 text-blue-500"></i>Pengaturan Analisis
            </h3>
            
            <form method="GET" action="{{ route('laporan.pareto') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort mr-2"></i>Basis Klasifikasi
                        </label>
                        <select 
                            name="sort_by" 
                            id="sort_by"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:outline-none transition-colors" 
                        >
                            <option value="value" {{ request('sort_by', 'value') == 'value' ? 'selected' : '' }}>
                                Berdasarkan Nilai Inventori
                            </option>
                            <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>
                                Berdasarkan Kuantitas Stok
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="periode" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>Periode Analisis
                        </label>
                        <select 
                            name="periode" 
                            id="periode"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:outline-none transition-colors" 
                        >
                            <option value="" {{ !request('periode') ? 'selected' : '' }}>
                                Semua Periode
                            </option>
                            @foreach($availablePeriodes as $p)
                                <option value="{{ $p['value'] }}" {{ request('periode') == $p['value'] ? 'selected' : '' }}>
                                    {{ $p['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center shadow-sm"
                    >
                        <i class="fas fa-sync-alt mr-2"></i>Refresh Analisis
                    </button>
                    
                    <a 
                        href="{{ route('laporan.pareto.export', array_filter(['sort_by' => request('sort_by'), 'periode' => request('periode')])) }}" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center text-decoration-none shadow-sm"
                    >
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </a>

                    @if(request('periode'))
                        <a 
                            href="{{ route('laporan.pareto', ['sort_by' => request('sort_by')]) }}" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center text-decoration-none shadow-sm"
                        >
                            <i class="fas fa-times mr-2"></i>Reset ke Semua Periode
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ABC Categories Summary - PERBAIKAN UTAMA -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Category A -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-red-600 font-bold text-xl">A</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Kategori A</h3>
                            <p class="text-sm text-red-600 font-medium">High Value Items</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-bold text-lg">{{ number_format($stats['kategori_a_count'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Nilai Total:</span>
                        <span class="font-bold text-red-600">Rp {{ number_format($stats['nilai_kategori_a'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kontribusi:</span>
                        <span class="font-bold text-red-600">{{ $stats['kontribusi_a'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-red-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['kontribusi_a'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Category B -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-yellow-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-yellow-600 font-bold text-xl">B</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Kategori B</h3>
                            <p class="text-sm text-yellow-600 font-medium">Medium Value Items</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-bold text-lg">{{ number_format($stats['kategori_b_count'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Nilai Total:</span>
                        <span class="font-bold text-yellow-600">Rp {{ number_format($stats['nilai_kategori_b'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kontribusi:</span>
                        <span class="font-bold text-yellow-600">{{ $stats['kontribusi_b'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['kontribusi_b'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Category C -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-green-600 font-bold text-xl">C</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Kategori C</h3>
                            <p class="text-sm text-green-600 font-medium">Low Value Items</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-bold text-lg">{{ number_format($stats['kategori_c_count'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Nilai Total:</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($stats['nilai_kategori_c'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kontribusi:</span>
                        <span class="font-bold text-green-600">{{ $stats['kontribusi_c'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $stats['kontribusi_c'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Detail Analisis ABC {{ $periodeInfo ? '- ' . $periodeInfo['nama_bulan'] : '- Semua Periode' }}
                    </h3>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span>{{ $analisis->count() }} item dianalisis</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas {{ $sortBy === 'quantity' ? 'fa-cubes' : 'fa-coins' }} mr-1"></i>
                            <span>{{ $sortBy === 'quantity' ? 'Berdasarkan Kuantitas' : 'Berdasarkan Nilai' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">Rank</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Periode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Vendor</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Stok</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Harga Satuan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Nilai Total</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">%</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Akumulasi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($analisis as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 text-sm text-gray-900 font-bold">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-blue-600">{{ $index + 1 }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-cube text-purple-600 text-xs"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</div>
                                            @if($item->no_barang)
                                                <div class="text-xs text-gray-500">No: {{ $item->no_barang }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $item->periode_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    <span class="truncate">{{ $item->vendor ?: '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                    <div class="flex items-center justify-end">
                                        @if($item->total_qty < 10)
                                            <i class="fas fa-exclamation-triangle text-red-500 mr-1 text-xs"></i>
                                        @elseif($item->total_qty < 50)
                                            <i class="fas fa-exclamation-circle text-yellow-500 mr-1 text-xs"></i>
                                        @else
                                            <i class="fas fa-check-circle text-green-500 mr-1 text-xs"></i>
                                        @endif
                                        {{ number_format($item->total_qty, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">
                                    <span class="font-medium">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-bold text-blue-600">
                                    Rp {{ number_format($item->total_nilai, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 bg-gray-200 rounded-full h-1.5 mb-1">
                                            <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" style="width: {{ min($item->persentase, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">{{ $item->persentase }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700">
                                    {{ $item->akumulasi_persentase }}%
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($item->kategori == 'A')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-star mr-1"></i>A
                                        </span>
                                    @elseif($item->kategori == 'B')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <i class="fas fa-circle mr-1"></i>B
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-dot-circle mr-1"></i>C
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-12">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-chart-bar text-gray-400 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data</h3>
                                        <p class="text-gray-500">{{ $periodeInfo ? 'Tidak ada barang untuk periode ' . $periodeInfo['nama_bulan'] : 'Pastikan ada barang dengan stok > 0 untuk dianalisis' }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mt-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-blue-900 mb-3">
                        Analisis Pareto ABC {{ $periodeInfo ? '- Periode ' . $periodeInfo['nama_bulan'] : '- Semua Periode' }}
                    </h3>
                    <div class="text-blue-800 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-bold text-red-700 mb-2">
                                    <i class="fas fa-star mr-2"></i>Kategori A (~80% {{ $sortBy === 'quantity' ? 'kuantitas' : 'nilai' }})
                                </h4>
                                <p class="text-sm">Barang dengan {{ $sortBy === 'quantity' ? 'kuantitas stok' : 'nilai inventori' }} tinggi. Memerlukan kontrol ketat dan monitoring rutin.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-bold text-yellow-700 mb-2">
                                    <i class="fas fa-circle mr-2"></i>Kategori B (~15% {{ $sortBy === 'quantity' ? 'kuantitas' : 'nilai' }})
                                </h4>
                                <p class="text-sm">Barang dengan {{ $sortBy === 'quantity' ? 'kuantitas stok' : 'nilai inventori' }} sedang. Memerlukan kontrol normal dengan review berkala.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-bold text-green-700 mb-2">
                                    <i class="fas fa-dot-circle mr-2"></i>Kategori C (~5% {{ $sortBy === 'quantity' ? 'kuantitas' : 'nilai' }})
                                </h4>
                                <p class="text-sm">Barang dengan {{ $sortBy === 'quantity' ? 'kuantitas stok' : 'nilai inventori' }} rendah. Dapat dikelola dengan kontrol sederhana.</p>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-white bg-opacity-30 rounded-lg">
                            <p class="text-sm">
                                <strong>Basis Analisis:</strong> 
                                {{ $sortBy === 'quantity' ? 'Analisis berdasarkan kuantitas stok barang untuk menentukan prioritas pengelolaan inventori.' : 'Analisis berdasarkan nilai inventori barang untuk menentukan prioritas investasi dan kontrol.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form when periode changes
    document.getElementById('periode').addEventListener('change', function() {
        this.form.submit();
    });
    
    console.log('ABC Analysis loaded with {{ $analisis->count() }} items {{ $periodeInfo ? "for period " . $periodeInfo["nama_bulan"] : "from all periods" }} based on {{ $sortBy === "quantity" ? "quantity" : "value" }}');
});
</script>
@endpush
@endsection