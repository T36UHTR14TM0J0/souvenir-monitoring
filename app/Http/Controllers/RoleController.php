<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Role default yang tidak boleh dihapus.
     */
    private const PROTECTED_ROLES = ['Pemilik', 'Admin', 'Kasir'];

    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        $query = Role::with('permissions')
            ->withCount('users');

        // Filter pencarian nama role
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->orderBy('name')
            ->paginate(10)              // ⭐ WAJIB: pagination, bukan ->get()
            ->withQueryString();        // ⭐ Bawa query string saat pindah halaman

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all()
            ->groupBy(fn ($p) => explode('-', $p->name)[0])
            ->sortKeys();

        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
                'regex:/^[a-z0-9_-]+$/',   // ← lowercase, angka, dash, underscore
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'string',
                'exists:permissions,name',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Normalisasi nama role → lowercase
        $name = strtolower(trim($validated['name']));

        $role = Role::create(['name' => $name]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', "Role \"{$role->name}\" berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()
            ->groupBy(fn ($p) => explode('-', $p->name)[0])
            ->sortKeys();

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
                'regex:/^[a-z0-9_-]+$/',
            ],
            'permissions' => [
                'nullable',
                'array',
            ],
            'permissions.*' => [
                'string',
                'exists:permissions,name',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Normalisasi nama role → lowercase
        $name = strtolower(trim($validated['name']));

        // Proteksi: rename role default tidak diizinkan
        $isProtected = in_array($role->name, self::PROTECTED_ROLES, true);
        if ($isProtected && $name !== $role->name) {
            return back()
                ->withInput()
                ->with('error', "Role default \"{$role->name}\" tidak bisa diganti namanya.");
        }

        $role->update(['name' => $name]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', "Role \"{$role->name}\" berhasil diubah.");
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Proteksi 1: Role masih dipakai user
        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            return back()->with(
                'error',
                "Role \"{$role->name}\" masih digunakan oleh {$usersCount} user. Pindahkan user terlebih dahulu."
            );
        }

        // Proteksi 2: Role default sistem
        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return back()->with('error', "Role default \"{$role->name}\" tidak bisa dihapus.");
        }

        $name = $role->name;
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', "Role \"{$name}\" berhasil dihapus.");
    }

    /**
     * Custom validation messages (Bahasa Indonesia).
     */
    private function validationMessages(): array
    {
        return [
            // Nama Role
            'name.required' => 'Nama role wajib diisi.',
            'name.string'   => 'Nama role harus berupa teks.',
            'name.max'      => 'Nama role maksimal 255 karakter.',
            'name.unique'   => 'Nama role sudah digunakan, gunakan nama lain.',
            'name.regex'    => 'Nama role hanya boleh berisi huruf kecil, angka, tanda hubung (-), dan garis bawah (_).',

            // Permissions
            'permissions.array'    => 'Format permission tidak valid.',
            'permissions.*.string' => 'Setiap permission harus berupa teks.',
            'permissions.*.exists' => 'Permission yang dipilih tidak ditemukan.',
        ];
    }

    /**
     * Custom attribute names (untuk pesan error yang lebih rapi).
     */
    private function validationAttributes(): array
    {
        return [
            'name'           => 'Nama Role',
            'permissions'    => 'Permission',
            'permissions.*'  => 'Permission',
        ];
    }
}