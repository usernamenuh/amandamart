<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', (bool) $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $totalUsers = User::count();
        $adminCount = User::admins()->count();
        $userCount = User::users()->count();
        $activeUsers = User::active()->count();

        return view('users.index', compact('users', 'totalUsers', 'adminCount', 'userCount', 'activeUsers'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => $request->has('is_active'),
                'email_verified_at' => now(), // Auto verify for admin created users
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan pengguna.')
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'is_active' => $request->has('is_active'),
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengguna.')
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Prevent deleting the last admin
        if ($user->isAdmin() && User::admins()->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        try {
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna.');
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // Prevent deactivating the last admin
        if ($user->isAdmin() && $user->isActive() && User::admins()->active()->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menonaktifkan admin terakhir yang aktif.');
        }

        try {
            $user->update(['is_active' => !$user->is_active]);

            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->route('users.index')
                ->with('success', "Pengguna berhasil {$status}.");
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat mengubah status pengguna.');
        }
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats()
    {
        return [
            'total_users' => User::count(),
            'admin_count' => User::admins()->count(),
            'user_count' => User::users()->count(),
            'active_users' => User::active()->count(),
            'inactive_users' => User::inactive()->count(),
        ];
    }
}
