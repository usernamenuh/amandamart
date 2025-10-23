@extends('layouts.dashboard')

@section('title', 'Sistem Pendukung Keputusan (SPK)')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Added dashboard header component -->
    <x-dashboard-header 
        title="Sistem Pendukung Keputusan (SPK)" 
        subtitle="Analisis ABC dan Rekomendasi Pengadaan Barang"
        :showTabs="true"
        activeTab="spk"
        :showBanner="true"
    />

    @php
    $total_items = $summary['total_items'] ?? 0;
    $kategori_a = $summary['kategori_a'] ?? 0;
    $kategori_b = $summary['kategori_b'] ?? 0;
    $kategori_c = $summary['kategori_c'] ?? 0;
    
    $persen_a = $total_items > 0 ? round(($kategori_a / $total_items) * 100, 1) : 0;
    $persen_b = $total_items > 0 ? round(($kategori_b / $total_items) * 100, 1) : 0;
    $persen_c = $total_items > 0 ? round(($kategori_c / $total_items) * 100, 1) : 0;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        
        <!-- Added period info banner with gradient -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-lightbulb text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Sistem Pendukung Keputusan</h3>
                        <p class="text-purple-100">Rekomendasi pengendalian persediaan berdasarkan analisis ABC dan kondisi stok</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $summary['total_items'] }}</div>
                    <div class="text-purple-100 text-sm">Total Item</div>
                </div>
            </div>
        </div>

        <!-- Removed Filter & Pengaturan section -->

        <!-- Removed category cards (A, B, C) - only keeping Total Item card -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Total Item</h3>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-blue-600"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_items'], 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500 mt-1">Item dalam sistem</p>
            </div>
        </div> --}}

        <!-- Added clickable category cards (A, B, C) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Category A Card -->
            <div class="filter-card cursor-pointer bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg hover:border-red-300 transition-all duration-300 transform hover:scale-105" data-filter="A">
                <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-red-600 font-bold text-xl">A</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Kategori A</h3>
                            <p class="text-sm text-red-600 font-medium">Stok Rendah</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-bold text-lg">{{ $summary['kategori_a'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Persentase:</span>
                        <!-- Tambahkan pengecekan total_items > 0 untuk menghindari division by zero -->
                        <span class="font-bold text-red-600">{{ $persen_a }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-red-500 h-2 rounded-full transition-all duration-300" style="width: {{ $persen_a }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-4 text-center">
                        <i class="fas fa-hand-pointer mr-1"></i>Klik untuk filter
                    </div>
                </div>
            </div>

            <!-- Category B Card -->
            <div class="filter-card cursor-pointer bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg hover:border-yellow-300 transition-all duration-300 transform hover:scale-105" data-filter="B">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-yellow-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-yellow-600 font-bold text-xl">B</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Kategori B</h3>
                            <p class="text-sm text-yellow-600 font-medium">Stok Sedang</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-bold text-lg">{{ $summary['kategori_b'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Persentase:</span>
                        <!-- Gunakan variabel $persen_b yang sudah aman dari division by zero -->
                        <span class="font-bold text-yellow-600">{{ $persen_b }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-yellow-500 h-2 rounded-full transition-all duration-300" style="width: {{ $persen_b }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-4 text-center">
                        <i class="fas fa-hand-pointer mr-1"></i>Klik untuk filter
                    </div>
                </div>
            </div>

            <!-- Category C Card -->
            <div class="filter-card cursor-pointer bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg hover:border-green-300 transition-all duration-300 transform hover:scale-105" data-filter="C">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-green-600 font-bold text-xl">C</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Kategori C</h3>
                            <p class="text-sm text-green-600 font-medium">Stok Tinggi</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-bold text-lg">{{ $summary['kategori_c'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Persentase:</span>
                        <!-- Gunakan variabel $persen_c yang sudah aman dari division by zero -->
                        <span class="font-bold text-green-600">{{ $persen_c }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $persen_c }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-4 text-center">
                        <i class="fas fa-hand-pointer mr-1"></i>Klik untuk filter
                    </div>
                </div>
            </div>
        </div>

        <!-- Removed ABC Categories Summary section (3 category cards) -->

        <!-- Added stock categorization guide table -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-book mr-2 text-blue-500"></i>Panduan Kategori Stok
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">A</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">10–30 pcs</td>
                            <td class="px-6 py-4 text-sm text-gray-700">Stok sedikit, laku cepat, harus restock.</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">B</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">30–70 pcs</td>
                            <td class="px-6 py-4 text-sm text-gray-700">Stok sedang, penjualan stabil.</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">C</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">70 pcs ke atas</td>
                            <td class="px-6 py-4 text-sm text-gray-700">Stok banyak, penjualan lambat, perlu dikontrol.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Updated main table styling with lazy loading -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Rekomendasi Pengendalian Persediaan
                    </h3>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <!-- Tambahkan pengecekan apakah ada data sebelum menampilkan tombol export -->
                        @if($summary['total_items'] > 0)
                            <a href="{{ route('spk.export-pdf', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </a>
                        @endif
                        
                        <div id="filterStatus" class="hidden">
                            <span class="font-semibold text-gray-700">Filter: </span>
                            <span id="filterLabel" class="font-bold"></span>
                            <button id="clearFilter" class="ml-2 px-2 py-1 text-xs bg-gray-200 hover:bg-gray-300 rounded transition-colors">
                                <i class="fas fa-times mr-1"></i>Hapus Filter
                            </button>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="itemCount">0</span> / {{ count($analisisData) }} item
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">NO</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">KODE</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                              </i>Nama Barang
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Kategori</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">
                                </i>Stok
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                        <!-- Rows will be loaded via lazy loading JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Loading indicator for lazy loading -->
            <div id="loadingIndicator" class="hidden px-6 py-4 text-center text-gray-500">
                <i class="fas fa-spinner fa-spin mr-2"></i>Memuat data...
            </div>

            <!-- Tambahkan pesan ketika tidak ada data -->
            @if($summary['total_items'] === 0)
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 block opacity-50"></i>
                    <p class="text-lg font-medium">Tidak ada data tersedia</p>
                    <p class="text-sm">Silakan tambahkan barang terlebih dahulu untuk melihat analisis ABC</p>
                </div>
            @else
                <!-- Sentinel element for Intersection Observer -->
                <div id="lazyLoadSentinel" class="px-6 py-4 text-center text-gray-500">
                    <i class="fas fa-arrow-down mr-2"></i>Scroll untuk memuat lebih banyak data
                </div>
            @endif
        </div>

        <!-- Added info panel -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-6 mt-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-purple-900 mb-3">
                        Panduan Rekomendasi SPK
                    </h3>
                    <div class="text-purple-800 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-bold text-red-700 mb-2">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Pengadaan Segera
                                </h4>
                                <p class="text-sm">Stok di bawah safety stock. Segera lakukan pengadaan untuk menghindari stockout.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-bold text-yellow-700 mb-2">
                                    <i class="fas fa-exclamation-circle mr-2"></i>Pertahankan Stok
                                </h4>
                                <p class="text-sm">Stok dalam kondisi normal. Monitor dan pertahankan level stok saat ini.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-bold text-blue-700 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>Pantau Penjualan
                                </h4>
                                <p class="text-sm">Stok mencukupi. Monitor pola penjualan untuk perencanaan pengadaan berikutnya.</p>
                            </div>
                            <div class="bg-white bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-bold text-green-700 mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>Pengadaan Bulan Cukup
                                </h4>
                                <p class="text-sm">Item kategori C. Pengadaan dapat dilakukan sesuai kebutuhan bulanan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Updated lazy loading script with filtering functionality -->
<script>
    const allData = @json($analisisData);
    const recommendations = @json($recommendations);
    const itemsPerLoad = 20;
    let currentIndex = 0;
    let filteredData = allData;
    let currentFilter = null;

    // Get icon for recommendation
    function getRecommendationIcon(rekomendasi) {
        const icons = {
            'Pengadaan Segera': 'fa-exclamation-triangle text-red-600',
            'Pertahankan Stok': 'fa-shield-alt text-yellow-600',
            'Pantau Penjualan': 'fa-eye text-blue-600',
            'Pengadaan Bulan Cukup': 'fa-check-circle text-green-600',
        };
        return icons[rekomendasi] || 'fa-info-circle text-gray-600';
    }

    // Get icon for category
    function getCategoryIcon(kategori) {
        const icons = {
            'A': 'fa-exclamation-circle text-red-600',
            'B': 'fa-balance-scale text-yellow-600',
            'C': 'fa-check-circle text-green-600',
        };
        return icons[kategori] || 'fa-circle text-gray-600';
    }

    function getStockBadgeStyle(stok) {
        if (stok >= 70) {
            return 'bg-green-100 text-green-800 border-green-200';
        } else if (stok >= 30) {
            return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        } else {
            return 'bg-red-100 text-red-800 border-red-200';
        }
    }

    function renderRows(startIndex, endIndex) {
        const tableBody = document.getElementById('tableBody');
        
        if (!filteredData || filteredData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i><p>Tidak ada data untuk ditampilkan</p></td></tr>';
            return;
        }
        
        for (let i = startIndex; i < endIndex && i < filteredData.length; i++) {
            const item = filteredData[i];
            const rec = recommendations[item.barang_id] || {};
            
            const kategoriColor = {
                'A': 'bg-red-100 text-red-800 border-red-200',
                'B': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'C': 'bg-green-100 text-green-800 border-green-200',
            }[item.kategori] || 'bg-gray-100 text-gray-800 border-gray-200';
            
            const rekomendasiColor = {
                'Pengadaan Segera': 'bg-red-50 text-red-700 border-l-4 border-red-500',
                'Pertahankan Stok': 'bg-yellow-50 text-yellow-700 border-l-4 border-yellow-500',
                'Pantau Penjualan': 'bg-blue-50 text-blue-700 border-l-4 border-blue-500',
                'Pengadaan Bulan Cukup': 'bg-green-50 text-green-700 border-l-4 border-green-500',
                'Pertimbangkan Pemesanan': 'bg-orange-50 text-orange-700 border-l-4 border-orange-500',
            }[rec.rekomendasi] || 'bg-gray-50 text-gray-700 border-l-4 border-gray-500';
            
            const categoryIcon = getCategoryIcon(item.kategori);
            const recIcon = getRecommendationIcon(rec.rekomendasi);
            const stockBadgeStyle = getStockBadgeStyle(item.stok);
            
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors';
            row.innerHTML = `
                <td class="px-6 py-4 text-sm text-gray-900">${i + 1}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">${item.kode}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                    ${item.nama}
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full border ${kategoriColor} flex items-center justify-center gap-1">
                        <i class="fas ${categoryIcon}"></i>
                        ${item.kategori}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full border ${stockBadgeStyle} flex items-center justify-center gap-1">
                        <i class="fas fa-cube"></i>
                        ${new Intl.NumberFormat('id-ID').format(item.stok)}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-3 py-1 text-xs font-semibold rounded border ${rekomendasiColor} flex items-center gap-1">
                        <i class="fas ${recIcon}"></i>
                        ${rec.rekomendasi || '-'}
                    </span>
                </td>
            `;
            tableBody.appendChild(row);
        }
        
        document.getElementById('itemCount').textContent = Math.min(endIndex, filteredData.length);
    }

    function loadMoreData() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.classList.remove('hidden');
        
        setTimeout(() => {
            const nextIndex = currentIndex + itemsPerLoad;
            renderRows(currentIndex, nextIndex);
            currentIndex = nextIndex;
            
            loadingIndicator.classList.add('hidden');
            
            if (currentIndex >= filteredData.length) {
                document.getElementById('lazyLoadSentinel').innerHTML = '<i class="fas fa-check mr-2"></i>Semua data telah dimuat';
            }
        }, 300);
    }

    function applyFilter(kategori) {
        currentFilter = kategori;
        filteredData = allData.filter(item => item.kategori === kategori);
        currentIndex = 0;
        
        // Clear table
        document.getElementById('tableBody').innerHTML = '';
        
        // Update filter status
        const filterStatus = document.getElementById('filterStatus');
        const filterLabel = document.getElementById('filterLabel');
        const categoryNames = { 'A': 'Kategori A (Stok Rendah)', 'B': 'Kategori B (Stok Sedang)', 'C': 'Kategori C (Stok Tinggi)' };
        
        filterStatus.classList.remove('hidden');
        filterLabel.textContent = categoryNames[kategori];
        
        // Reset sentinel
        document.getElementById('lazyLoadSentinel').innerHTML = '<i class="fas fa-arrow-down mr-2"></i>Scroll untuk memuat lebih banyak data';
        
        // Load initial data
        renderRows(0, itemsPerLoad);
        currentIndex = itemsPerLoad;
    }

    function clearFilter() {
        currentFilter = null;
        filteredData = allData;
        currentIndex = 0;
        
        // Clear table
        document.getElementById('tableBody').innerHTML = '';
        
        // Hide filter status
        document.getElementById('filterStatus').classList.add('hidden');
        
        // Reset sentinel
        document.getElementById('lazyLoadSentinel').innerHTML = '<i class="fas fa-arrow-down mr-2"></i>Scroll untuk memuat lebih banyak data';
        
        // Load initial data
        renderRows(0, itemsPerLoad);
        currentIndex = itemsPerLoad;
    }

    // Add event listeners to filter cards
    document.querySelectorAll('.filter-card').forEach(card => {
        card.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            applyFilter(filter);
        });
    });

    // Add event listener to clear filter button
    document.getElementById('clearFilter').addEventListener('click', clearFilter);

    // Initial load
    renderRows(0, itemsPerLoad);
    currentIndex = itemsPerLoad;

    // Intersection Observer for lazy loading
    const sentinel = document.getElementById('lazyLoadSentinel');
    if (sentinel) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && currentIndex < filteredData.length) {
                loadMoreData();
            }
        }, { threshold: 0.1 });

        observer.observe(sentinel);
    }
</script>
@endsection
