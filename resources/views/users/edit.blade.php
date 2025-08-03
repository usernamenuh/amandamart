@extends('layouts.dashboard')

@section('title', 'Edit Pengguna - StockMaster')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <x-dashboard-header 
        title="Edit Pengguna" 
        subtitle="Mengubah informasi pengguna"
        :showTabs="false"
        :showBanner="false"
    />

    <div class="max-w-4xl mx-auto px-2 lg:px-8">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-user-edit mr-2 text-blue-500"></i>
                        Edit Pengguna: {{ $user->name }}
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('users.show', $user) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        <a href="{{ route('users.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('users.update', $user) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                               placeholder="Kosongkan jika tidak ingin mengubah">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ulangi password baru">
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Pengguna aktif dapat login ke sistem</p>
                    </div>
                </div>

                <!-- User Info -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Akun
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Bergabung:</span><br>
                            {{ $user->created_at->format('d M Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Terakhir Diupdate:</span><br>
                            {{ $user->updated_at->format('d M Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Email Verified:</span><br>
                            {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y H:i') : 'Belum terverifikasi' }}
                        </div>
                    </div>
                </div>

                <!-- Warning for current user -->
                @if($user->id === auth()->id())
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium">Perhatian!</p>
                            <p>Anda sedang mengedit akun Anda sendiri. Pastikan informasi yang dimasukkan benar.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Button -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('users.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Update Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
