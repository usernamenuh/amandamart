@extends('layouts.dashboard')

@section('title', 'Detail Pengguna - StockMaster')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <x-dashboard-header 
        title="Detail Pengguna" 
        subtitle="Informasi lengkap pengguna"
        :showTabs="false"
        :showBanner="false"
    />

    <div class="max-w-4xl mx-auto px-2 lg:px-8">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Detail Pengguna
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('users.edit', $user) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                        <a href="{{ route('users.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- User Profile Section -->
                <div class="flex items-center space-x-6 mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            @if($user->role === 'admin')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-user-shield mr-1"></i>
                                Administrator
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-user mr-1"></i>
                                User
                            </span>
                            @endif

                            @if($user->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Nonaktif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Informasi Dasar
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                                <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Role</label>
                                <p class="text-gray-900 font-medium">{{ ucfirst($user->role) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status</label>
                                <p class="text-gray-900 font-medium">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-calendar mr-2 text-green-500"></i>
                            Informasi Akun
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Bergabung</label>
                                <p class="text-gray-900 font-medium">{{ $user->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate</label>
                                <p class="text-gray-900 font-medium">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                                <p class="text-sm text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                            @if($user->email_verified_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email Terverifikasi</label>
                                <p class="text-gray-900 font-medium">{{ $user->email_verified_at->format('d M Y, H:i') }}</p>
                                <p class="text-sm text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Terverifikasi
                                </p>
                            </div>
                            @else
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email Terverifikasi</label>
                                <p class="text-red-600 font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Belum terverifikasi
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics (Safe version without database queries) -->
                <div class="mt-6 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                        Statistik Aktivitas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">-</div>
                            <div class="text-sm text-gray-500">Barang Ditambahkan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">-</div>
                            <div class="text-sm text-gray-500">Transaksi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">-</div>
                            <div class="text-sm text-gray-500">Import Data</div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 text-center mt-2">Statistik akan tersedia setelah konfigurasi database selesai</p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Pengguna ini {{ $user->is_active ? 'dapat' : 'tidak dapat' }} mengakses sistem
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="{{ $user->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                    onclick="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} pengguna ini?')">
                                <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }} mr-2"></i>
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        @if($user->role !== 'admin' || \App\Models\User::where('role', 'admin')->count() > 1)
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus
                            </button>
                        </form>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
