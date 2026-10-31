<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filter: search by name/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter: by role (Spatie)
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter: by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Create user
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Assign role (Spatie)
        $user->assignRole($validated['role']);

        return redirect()
            ->route('users.index')
            ->with('success', "User \"{$user->name}\" berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Update basic info
        $user->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Sync role (Spatie) — replace semua role lama
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('users.index')
            ->with('success', "User \"{$user->name}\" berhasil diubah.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Proteksi: tidak bisa hapus akun sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "User \"{$name}\" berhasil dihapus.");
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        // Proteksi: tidak bisa nonaktifkan akun sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User \"{$user->name}\" berhasil {$status}.");
    }

    /**
     * Custom validation messages (Bahasa Indonesia).
     */
    private function validationMessages(): array
    {
        return [
            // Nama
            'name.required' => 'Nama wajib diisi.',
            'name.string'   => 'Nama harus berupa teks.',
            'name.max'      => 'Nama maksimal 255 karakter.',

            // Email
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.max'      => 'Email maksimal 255 karakter.',
            'email.unique'   => 'Email sudah terdaftar, gunakan email lain.',

            // Password
            'password.required'  => 'Password wajib diisi.',
            'password.string'    => 'Password harus berupa teks.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            // Role
            'role.required' => 'Role wajib dipilih.',
            'role.string'   => 'Role tidak valid.',
            'role.exists'   => 'Role yang dipilih tidak ditemukan.',

            // Status
            'is_active.boolean' => 'Status aktif tidak valid.',
        ];
    }

    /**
     * Custom attribute names (untuk pesan error yang lebih rapi).
     */
    private function validationAttributes(): array
    {
        return [
            'name'                  => 'Nama',
            'email'                 => 'Email',
            'password'              => 'Password',
            'password_confirmation' => 'Konfirmasi Password',
            'role'                  => 'Role',
            'is_active'             => 'Status Aktif',
        ];
    }
}