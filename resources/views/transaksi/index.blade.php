@extends('layouts.dashboard')

@section('title', 'Data Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
<!-- Header -->
<x-dashboard-header 
    title="Data Transaksi" 
    subtitle="Kelola transaksi masuk dan keluar barang"
    :showTabs="true"
    activeTab="transaksi"
    :showBanner="true"
/>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
    <!-- Success Alert -->
    @if (session('success'))
        <div id="alert-success" class="mb-6 flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            <button onclick="document.getElementById('alert-success').remove()" class="ml-3 text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Total Transaksi</h3>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exchange-alt text-blue-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_transaksi'], 0, ',', '.') }}</div>
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-chart-line mr-1"></i>Total semua transaksi
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Transaksi Masuk</h3>
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-down text-green-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['transaksi_masuk'], 0, ',', '.') }}</div>
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-plus-circle mr-1"></i>Barang masuk
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Transaksi Keluar</h3>
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['transaksi_keluar'], 0, ',', '.') }}</div>
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-minus-circle mr-1"></i>Barang keluar
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Total Nilai</h3>
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-purple-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</div>
            <p class="text-xs text-gray-500 mt-1">
                <i class="fas fa-wallet mr-1"></i>Total nilai transaksi
            </p>
        </div>
    </div>

    <!-- Filters (Simplified - No Date Range) -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-filter mr-2 text-blue-500"></i>Filter Transaksi
            </h3>
            @php $user = auth()->user(); @endphp
            @if (!isset($user->role) || $user->role !== 'owner')
                <a href="{{ route('transaksi.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm text-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Transaksi
                </a>
            @endif
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Jenis Transaksi Filter -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-tags mr-1"></i>Jenis Transaksi
                </label>
                <select id="filter-jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Semua Jenis</option>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>

            <!-- Barang Filter -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-box mr-1"></i>Barang
                </label>
                <select id="filter-barang" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Semua Barang</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->nama_item }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Search -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="search-input" placeholder="Cari berdasarkan nama barang, referensi, atau keterangan..." 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors">
        </div>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-blue-500"></i>
                    Daftar Transaksi
                </h3>
                <div class="text-sm text-gray-500">
                    <span id="showing-count">Menampilkan {{ $transaksis->count() }}</span> dari 
                    <span id="total-count">{{ $transaksis->total() }}</span> transaksi
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                            No
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                            Tanggal
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Barang
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                            Jenis
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">
                            Qty
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                            Harga Satuan
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                            Total
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                            User
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="transaksi-tbody">
                    @forelse ($transaksis as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 transaksi-row" 
                            data-jenis="{{ $item->jenis_transaksi }}"
                            data-barang="{{ $item->barang_id }}"
                            data-search="{{ strtolower($item->barang->nama_item . ' ' . ($item->no_referensi ?? '') . ' ' . ($item->keterangan ?? '')) }}">
                            
                            <!-- No -->
                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-blue-600">{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $index + 1 }}</span>
                                </div>
                            </td>
                            
                            <!-- Tanggal -->
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="font-medium">{{ $item->tanggal_transaksi->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $item->tanggal_transaksi->format('D') }}</div>
                            </td>
                            
                            <!-- Barang -->
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-cube text-purple-600 text-xs"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $item->barang->nama_item }}</div>
                                        @if($item->barang->no)
                                            <div class="text-xs text-gray-500 truncate">No: {{ $item->barang->no }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Jenis -->
                            <td class="px-4 py-3">
                                @if($item->jenis_transaksi === 'masuk')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-down mr-1"></i>
                                        Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Qty -->
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                {{ number_format($item->qty, 0) }}
                            </td>
                            
                            <!-- Harga Satuan -->
                            <td class="px-4 py-3 text-right text-sm text-gray-900">
                                <span class="font-medium">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                            </td>
                            
                            <!-- Total -->
                            <td class="px-4 py-3 text-right text-sm font-semibold text-blue-600">
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            
                            <!-- User -->
                            <td class="px-4 py-3 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                        <i class="fas fa-user text-gray-500 text-xs"></i>
                                    </div>
                                    <span class="truncate">{{ $item->user->name }}</span>
                                </div>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <!-- View Button -->
                                    <a href="{{ route('transaksi.show', $item->id) }}" 
                                       class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    
                                    @if (!isset($user->role) || $user->role !== 'owner')
                                        <!-- Edit Button -->
                                        <a href="{{ route('transaksi.edit', $item->id) }}" 
                                           class="inline-flex items-center p-1.5 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded transition-colors"
                                           title="Edit Transaksi">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button onclick="openDeleteModal('{{ $item->id }}', '{{ $item->barang->nama_item }} - {{ $item->tanggal_transaksi->format('d/m/Y') }}')" 
                                                class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors"
                                                title="Hapus Transaksi">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-data-row">
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada transaksi</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan transaksi pertama</p>
                                    @if (!isset($user->role) || $user->role !== 'owner')
                                        <a href="{{ route('transaksi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Transaksi Pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksis->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
<div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
    <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Hapus Transaksi</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus transaksi "<span id="deleteItemName" class="font-semibold"></span>"?
                    Tindakan ini tidak dapat dibatalkan dan akan mempengaruhi stok barang.
                </p>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-3">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
            <button type="button" onclick="closeDeleteModal()" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
// Optimized filtering without date range
function applyFilters() {
const jenisFilter = document.getElementById('filter-jenis').value;
const barangFilter = document.getElementById('filter-barang').value;
const searchTerm = document.getElementById('search-input').value.toLowerCase();

const rows = document.querySelectorAll('.transaksi-row');
let visibleCount = 0;

rows.forEach(row => {
    const jenis = row.dataset.jenis;
    const barang = row.dataset.barang;
    const searchData = row.dataset.search;
    
    let show = true;
    
    // Filter by jenis
    if (jenisFilter && jenis !== jenisFilter) show = false;
    
    // Filter by barang
    if (barangFilter && barang !== barangFilter) show = false;
    
    // Filter by search term
    if (searchTerm && !searchData.includes(searchTerm)) show = false;
    
    row.style.display = show ? '' : 'none';
    if (show) visibleCount++;
});

// Update count
document.getElementById('showing-count').textContent = `Menampilkan ${visibleCount}`;

// Show/hide no data message
const noDataRow = document.getElementById('no-data-row');
if (noDataRow) {
    noDataRow.style.display = visibleCount === 0 ? '' : 'none';
}
}

// Delete modal functions
function openDeleteModal(id, name) {
document.getElementById('deleteItemName').textContent = name;
document.getElementById('deleteForm').action = `/transaksi/${id}`;
document.getElementById('deleteModal').classList.remove('hidden');
document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
document.getElementById('deleteModal').classList.add('hidden');
document.body.style.overflow = 'auto';
}

// Optimized event listeners - prevent multiple bindings
document.addEventListener('DOMContentLoaded', function() {
// Debounced search for better performance
let searchTimeout;
document.getElementById('search-input').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 300);
});

// Immediate filter for dropdowns
document.getElementById('filter-jenis').addEventListener('change', applyFilters);
document.getElementById('filter-barang').addEventListener('change', applyFilters);

// Initial filter application
applyFilters();
});

// Auto hide alerts with smooth animation
setTimeout(() => {
const alerts = document.querySelectorAll('#alert-success, #alert-danger');
alerts.forEach(alert => {
    if (alert) {
        alert.style.transition = 'all 0.3s ease-out';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 300);
    }
});
}, 5000);

// Close modal when pressing Escape
document.addEventListener('keydown', function(event) {
if (event.key === 'Escape') {
    closeDeleteModal();
}
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
window.history.replaceState(null, null, window.location.href);
}
</script>
@endpush

@push('styles')
<style>
/* Optimize loading performance */
.transaksi-row {
    will-change: auto;
}

/* Smooth transitions */
.transition-colors {
    transition-property: color, background-color, border-color;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Prevent layout shift during loading */
.w-8, .h-8 {
    min-width: 2rem;
    min-height: 2rem;
}

/* Optimize table rendering */
table {
    table-layout: fixed;
}

/* Loading state optimization */
.bg-white {
    background-color: rgb(255 255 255);
}
</style>
@endpush
@endsection