@extends('layouts.dashboard')

@section('title', 'Dashboard - StockMaster')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <x-dashboard-header 
        title="Manajemen Stok Barang" 
        subtitle="Dashboard overview dan analisis inventory dengan ABC Analysis"
        :showTabs="true"
        activeTab="overview"
        :showBanner="true"
    />

    <!-- Stock Alert Notification -->
    @if($stokMenipis > 0)
    <div class="max-w-7xl mx-auto px-2 lg:px-8 mb-6">
        <div class="bg-gradient-to-r from-orange-50 to-red-50 border-l-4 border-orange-400 rounded-lg shadow-sm">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-orange-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-orange-800">
                                Peringatan Stok Menipis!
                            </h3>
                            <div class="mt-1">
                                <p class="text-sm text-orange-700">
                                    Terdapat <span class="font-bold text-orange-900">{{ number_format($stokMenipis) }} barang</span> 
                                    yang memiliki stok kurang dari 10 unit dan perlu segera direstock.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-right mr-4">
                            <div class="text-2xl font-bold text-orange-600">{{ $stokMenipis }}</div>
                            <div class="text-xs text-orange-500 uppercase tracking-wide">Item Kritis</div>
                        </div>
                        <a href="{{ route('barang.index', ['filter_stok' => 'menipis']) }}" 
                           class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Barang
                        </a>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-orange-600 mb-1">
                        <span>Status Inventori</span>
                        <span>{{ number_format(($stokMenipis / $totalBarang) * 100, 1) }}% dari total barang</span>
                    </div>
                    <div class="w-full bg-orange-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-orange-400 to-red-500 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ min(($stokMenipis / $totalBarang) * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-2 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-rupiah-sign text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Total penjualan estimasi</p>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Nilai Inventori</h3>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-warehouse text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Total nilai stok</p>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Barang</h3>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-purple-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($totalBarang, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Item terdaftar</p>
            </div>

            <a href="{{ route('barang.index', ['filter_stok' => 'menipis']) }}" 
               class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-200 group">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600 group-hover:text-orange-600 transition-colors">Stok Menipis</h3>
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">{{ number_format($stokMenipis, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500 group-hover:text-orange-500 transition-colors">Perlu restock</p>
                @if($stokMenipis > 0)
                <div class="mt-2 text-xs text-orange-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-mouse-pointer mr-1"></i>
                    Klik untuk melihat detail
                </div>
                @endif
            </a>
        </div>

        <!-- ABC Analysis Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-pie mr-2 text-indigo-500"></i>
                        Analisis ABC Pareto
                    </h3>
                    <p class="text-sm text-gray-600">Klasifikasi barang berdasarkan nilai inventori</p>
                </div>
                <a href="{{ route('laporan.pareto') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Lihat Detail
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kategori A -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-bold text-red-800">Kategori A</h4>
                        <div class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                            {{ $abcAnalysis['kategori_a']['percentage'] }}%
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-red-700">Jumlah Item:</span>
                            <span class="font-semibold text-red-800">{{ number_format($abcAnalysis['kategori_a']['count']) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-red-700">Nilai:</span>
                            <span class="font-semibold text-red-800">Rp {{ number_format($abcAnalysis['kategori_a']['value'], 0, ',', '.') }}</span>
                        </div>
                        <div class="text-xs text-red-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Item dengan nilai tinggi (80% kontribusi)
                        </div>
                    </div>
                </div>

                <!-- Kategori B -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border-2 border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-bold text-yellow-800">Kategori B</h4>
                        <div class="bg-yellow-600 text-white px-2 py-1 rounded text-xs font-bold">
                            {{ $abcAnalysis['kategori_b']['percentage'] }}%
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-yellow-700">Jumlah Item:</span>
                            <span class="font-semibold text-yellow-800">{{ number_format($abcAnalysis['kategori_b']['count']) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-yellow-700">Nilai:</span>
                            <span class="font-semibold text-yellow-800">Rp {{ number_format($abcAnalysis['kategori_b']['value'], 0, ',', '.') }}</span>
                        </div>
                        <div class="text-xs text-yellow-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Item dengan nilai sedang (15% kontribusi)
                        </div>
                    </div>
                </div>

                <!-- Kategori C -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-lg font-bold text-green-800">Kategori C</h4>
                        <div class="bg-green-600 text-white px-2 py-1 rounded text-xs font-bold">
                            {{ $abcAnalysis['kategori_c']['percentage'] }}%
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-green-700">Jumlah Item:</span>
                            <span class="font-semibold text-green-800">{{ number_format($abcAnalysis['kategori_c']['count']) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-green-700">Nilai:</span>
                            <span class="font-semibold text-green-800">Rp {{ number_format($abcAnalysis['kategori_c']['value'], 0, ',', '.') }}</span>
                        </div>
                        <div class="text-xs text-green-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Item dengan nilai rendah (5% kontribusi)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Sales Chart berdasarkan Periode -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Ringkasan Penjualan</h3>
                        <p class="text-sm text-gray-500">Berdasarkan periode barang ({{ count($salesData['labels'] ?? []) }} bulan)</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Periode</button>
                        <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-full">Estimasi</button>
                    </div>
                </div>
                
                @if(!empty($salesData['data']) && count($salesData['data']) > 0)
                <div class="h-64 relative">
                    <canvas id="salesChart" class="w-full h-full"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <div class="text-sm text-gray-600">
                        Total {{ count($salesData['labels']) }} periode dengan data
                    </div>
                </div>
                @else
                <div class="h-64 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-gray-500">Belum ada data penjualan berdasarkan periode</p>
                        <p class="text-sm text-gray-400 mt-2">Data akan muncul ketika ada barang dengan periode 1-12</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- ABC Distribution Chart -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Distribusi ABC</h3>
                    <div class="text-sm text-gray-500">
                        Total: {{ number_format($abcAnalysis['total_items']) }} item
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="abcChart" class="w-full h-full"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-xl font-bold text-red-600">{{ $abcAnalysis['kategori_a']['count'] }}</div>
                        <div class="text-sm text-gray-500">Kategori A</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-yellow-600">{{ $abcAnalysis['kategori_b']['count'] }}</div>
                        <div class="text-sm text-gray-500">Kategori B</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-green-600">{{ $abcAnalysis['kategori_c']['count'] }}</div>
                        <div class="text-sm text-gray-500">Kategori C</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Items & Recent Sales -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Sales -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-shopping-cart mr-2 text-green-500"></i>
                        Penjualan Terbaru
                    </h3>
                    <a href="{{ route('barang.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 mb-4">Simulasi penjualan berdasarkan {{ $recentSalesCount }} item terbaru.</p>
                    
                    @forelse($recentSales as $sale)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ strtoupper(substr($sale['name'], 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $sale['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $sale['email'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600">+Rp {{ number_format($sale['amount'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $sale['date'] ?? '' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <p class="text-gray-500">Belum ada penjualan terbaru</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Performing Items -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                        Barang Terbaik
                    </h3>
                    <a href="{{ route('barang.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    @forelse($topPerformingItems as $index => $item)
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($item->nama, 30) }}</p>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <span>Stok: {{ number_format($item->does_pcs) }}</span>
                                    <span>•</span>
                                    <span>Profit/unit: Rp {{ number_format($item->profit_per_unit, 0, ',', '.') }}</span>
                                    @if($item->periode)
                                    <span>•</span>
                                    <span>Periode: {{ $item->periode }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600">Rp {{ number_format($item->total_potential_profit, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">Potensi profit</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <p class="text-gray-500">Belum ada data performa item</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-tags mr-2 text-purple-500"></i>
                    Kategori Teratas
                </h3>
                <div class="text-sm text-gray-500">
                    Berdasarkan departemen
                </div>
            </div>
            <div class="space-y-4">
                @foreach($categoryData as $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 {{ $category['color'] }} rounded-full"></div>
                        <span class="text-sm font-medium text-gray-900">{{ $category['name'] }}</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">{{ $category['count'] }} item</span>
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="{{ $category['color'] }} h-2 rounded-full" style="width: {{ $category['percentage'] }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400 w-10 text-right">{{ $category['percentage'] }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        Aktivitas Terbaru
                    </h3>
                    <a href="{{ route('barang.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($barangs->take(5) as $barang)
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-plus text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $barang->nama ?? $barang->nama_item }}</p>
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <span>Ditambahkan ke {{ $barang->golongan ?: $barang->dept_description ?: 'Tidak Berkategori' }}</span>
                                @if($barang->periode)
                                <span>•</span>
                                <span>Periode: {{ $barang->periode }}</span>
                                @endif
                                @if($barang->qty)
                                <span>•</span>
                                <span>Qty: {{ number_format($barang->qty) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-sm text-gray-500">
                            {{ $barang->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari PHP
    const salesData = @json($salesData);
    const abcData = @json($abcAnalysis);
    
    console.log('Sales Data:', salesData);
    
    // Sales Chart berdasarkan Periode
    if (salesData.data && salesData.data.length > 0) {
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.labels,
                datasets: [{
                    label: 'Penjualan Estimasi',
                    data: salesData.data,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000) + 'K';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // ABC Distribution Chart
    const abcCtx = document.getElementById('abcChart').getContext('2d');
    new Chart(abcCtx, {
        type: 'doughnut',
        data: {
            labels: ['Kategori A', 'Kategori B', 'Kategori C'],
            datasets: [{
                data: [abcData.kategori_a.count, abcData.kategori_b.count, abcData.kategori_c.count],
                backgroundColor: [
                    'rgb(239, 68, 68)',   // Red for A
                    'rgb(245, 158, 11)',  // Yellow for B
                    'rgb(34, 197, 94)'    // Green for C
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
});
</script>
@endsection
