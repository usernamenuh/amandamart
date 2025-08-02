@extends('layouts.dashboard')

@section('title', 'Detail Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Detail Transaksi" 
        subtitle="Informasi lengkap transaksi barang"
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

        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('transaksi.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Data Transaksi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500">Detail Transaksi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Transaction Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 {{ $transaksi->jenis_transaksi === 'masuk' ? 'bg-green-100' : 'bg-red-100' }} rounded-xl flex items-center justify-center mr-4">
                            <i class="fas {{ $transaksi->jenis_transaksi === 'masuk' ? 'fa-arrow-down text-green-600' : 'fa-arrow-up text-red-600' }} text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Transaksi {{ ucfirst($transaksi->jenis_transaksi) }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $transaksi->tanggal_transaksi->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        @php $user = auth()->user(); @endphp
                        @if (!isset($user->role) || $user->role !== 'owner')
                            <a href="{{ route('transaksi.edit', $transaksi->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                        @endif
                        <a href="{{ route('transaksi.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Transaction Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Transaction Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h4 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Detail Transaksi
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Barang Info -->
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-purple-50 rounded-lg">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-cube text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">{{ $transaksi->barang->nama_item }}</h5>
                                        @if($transaksi->barang->no)
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                No: {{ $transaksi->barang->no }}
                                            </p>
                                        @endif
                                        @if($transaksi->barang->vendor)
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-truck mr-1"></i>
                                                {{ $transaksi->barang->vendor }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction Type -->
                            <div class="space-y-4">
                                <div class="flex items-center p-4 {{ $transaksi->jenis_transaksi === 'masuk' ? 'bg-green-50' : 'bg-red-50' }} rounded-lg">
                                    <div class="w-12 h-12 {{ $transaksi->jenis_transaksi === 'masuk' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas {{ $transaksi->jenis_transaksi === 'masuk' ? 'fa-arrow-down text-green-600' : 'fa-arrow-up text-red-600' }}"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">{{ ucfirst($transaksi->jenis_transaksi) }}</h5>
                                        <p class="text-sm {{ $transaksi->jenis_transaksi === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaksi->jenis_transaksi === 'masuk' ? 'Penambahan stok' : 'Pengurangan stok' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quantity & Pricing -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h4 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-calculator mr-2 text-green-500"></i>
                            Perhitungan Harga
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Basic Calculation -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600 mb-1">
                                        <i class="fas fa-cubes mr-2"></i>
                                        {{ number_format($transaksi->qty, 0) }}
                                    </div>
                                    <div class="text-sm text-gray-600">Quantity</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600 mb-1">
                                        <i class="fas fa-rupiah-sign mr-1"></i>
                                        {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-600">Harga Satuan</div>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg">
                                    <div class="text-2xl font-bold text-purple-600 mb-1">
                                        <i class="fas fa-coins mr-1"></i>
                                        {{ number_format($transaksi->subtotal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-600">Subtotal</div>
                                </div>
                            </div>

                            <!-- Detailed Calculation -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-900 mb-3">
                                    <i class="fas fa-list-ul mr-2"></i>
                                    Rincian Perhitungan
                                </h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Subtotal ({{ number_format($transaksi->qty, 0) }} × Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}):</span>
                                        <span class="font-medium">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    @if($transaksi->discount_amount > 0)
                                        <div class="flex justify-between items-center text-orange-600">
                                            <span>Diskon Total:</span>
                                            <span class="font-medium">- Rp {{ number_format($transaksi->discount_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center border-t pt-2">
                                            <span class="text-gray-600">Subtotal Setelah Diskon:</span>
                                            <span class="font-medium">Rp {{ number_format($transaksi->subtotal_after_discount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if($transaksi->ppn_amount > 0)
                                        <div class="flex justify-between items-center text-purple-600">
                                            <span>PPN (11%):</span>
                                            <span class="font-medium">+ Rp {{ number_format($transaksi->ppn_amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between items-center border-t pt-2 text-lg font-bold">
                                        <span class="text-gray-800">TOTAL AKHIR:</span>
                                        <span class="text-green-600">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                @if($transaksi->no_referensi || $transaksi->keterangan)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h4 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                                Informasi Tambahan
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($transaksi->no_referensi)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-hashtag mr-1"></i>
                                            No. Referensi
                                        </label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <span class="font-mono text-gray-900">{{ $transaksi->no_referensi }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($transaksi->keterangan)
                                    <div class="{{ $transaksi->no_referensi ? '' : 'md:col-span-2' }}">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-comment mr-1"></i>
                                            Keterangan
                                        </label>
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-gray-900">{{ $transaksi->keterangan }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Transaction Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h4 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-chart-pie mr-2 text-indigo-500"></i>
                            Ringkasan
                        </h4>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Tanggal</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $transaksi->tanggal_transaksi->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-user text-green-600 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Dibuat oleh</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $transaksi->user->name }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-purple-600 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Dibuat</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        @if($transaksi->updated_at != $transaksi->created_at)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-edit text-yellow-600 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">Diupdate</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $transaksi->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stock Impact -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h4 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-warehouse mr-2 text-orange-500"></i>
                            Dampak Stok
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="w-16 h-16 {{ $transaksi->jenis_transaksi === 'masuk' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas {{ $transaksi->jenis_transaksi === 'masuk' ? 'fa-plus text-green-600' : 'fa-minus text-red-600' }} text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $transaksi->jenis_transaksi === 'masuk' ? 'Menambah' : 'Mengurangi' }} Stok
                            </h5>
                            <p class="text-sm text-gray-600 mb-4">
                                Transaksi ini {{ $transaksi->jenis_transaksi === 'masuk' ? 'menambahkan' : 'mengurangi' }} 
                                <span class="font-semibold">{{ number_format($transaksi->qty, 0) }}</span> unit 
                                dari stok barang
                            </p>
                            <div class="p-3 {{ $transaksi->jenis_transaksi === 'masuk' ? 'bg-green-50' : 'bg-red-50' }} rounded-lg">
                                <div class="text-sm text-gray-600">Stok saat ini</div>
                                <div class="text-xl font-bold {{ $transaksi->jenis_transaksi === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($transaksi->barang->qty, 0) }} unit
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barang Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h4 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-chart-bar mr-2 text-green-500"></i>
                            Statistik Barang
                        </h4>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Transaksi:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($stats['total_transaksi_barang'], 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-green-600">Transaksi Masuk:</span>
                            <span class="font-semibold text-green-700">{{ number_format($stats['transaksi_masuk_barang'], 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-red-600">Transaksi Keluar:</span>
                            <span class="font-semibold text-red-700">{{ number_format($stats['transaksi_keluar_barang'], 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="text-sm text-gray-600">Total Nilai:</span>
                            <span class="font-semibold text-blue-600">Rp {{ number_format($stats['total_nilai_barang'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if (!isset($user->role) || $user->role !== 'owner')
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h4 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                                Aksi Cepat
                            </h4>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('transaksi.edit', $transaksi->id) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Transaksi
                            </a>
                            <a href="{{ route('transaksi.create') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Transaksi Baru
                            </a>
                            <a href="{{ route('barang.show', $transaksi->barang->id) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-cube mr-2"></i>
                                Lihat Barang
                            </a>
                            <button onclick="openDeleteModal('{{ $transaksi->id }}', '{{ $transaksi->barang->nama_item }} - {{ $transaksi->tanggal_transaksi->format('d/m/Y') }}')" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus Transaksi
                            </button>
                        </div>
                    </div>
                @endif
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

// Close modal when pressing Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
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