@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna - StockMaster')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <x-dashboard-header 
        title="Kelola Pengguna" 
        subtitle="Manajemen pengguna sistem StockMaster"
        :showTabs="true"
        activeTab="pengguna"
        :showBanner="false"
    />

    <div class="max-w-7xl mx-auto px-2 lg:px-8">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-user-plus mr-2 text-blue-500"></i>
                        Tambah Pengguna Baru
                    </h3>
                    <a href="{{ route('users.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('users.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="contoh@email.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                               placeholder="Minimal 8 karakter">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ulangi password">
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Administrator memiliki akses penuh ke sistem</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Pengguna aktif dapat login ke sistem</p>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Informasi Penting:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Password minimal 8 karakter</li>
                                <li>Email akan otomatis terverifikasi</li>
                                <li>Pengguna akan menerima notifikasi akun baru</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('users.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
