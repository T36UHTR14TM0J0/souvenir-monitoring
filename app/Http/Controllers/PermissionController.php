<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index(Request $request)
    {
        $query = Permission::withCount('roles');

        // Filter: search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter: by group (prefix of name, e.g. "user")
        if ($request->filled('group')) {
            $query->where('name', 'like', $request->group . '-%');
        }

        // Pagination 15 per halaman
        $permissions = $query->orderBy('name')->paginate(15)->withQueryString();

        // Ambil semua grup untuk dropdown filter
        $groups = Permission::selectRaw("SUBSTRING_INDEX(name, '-', 1) as grp")
            ->distinct()
            ->orderBy('grp')
            ->pluck('grp');

        // Total stats
        $totalPermissions = Permission::count();
        $totalGroups = $groups->count();

        return view('permissions.index', compact(
            'permissions',
            'groups',
            'totalPermissions',
            'totalGroups'
        ));
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,name',
                'regex:/^[a-z0-9_-]+$/',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Normalisasi: lowercase & trim
        $name = strtolower(trim($validated['name']));

        // Validasi tambahan: harus format modul-aksi (ada dash)
        if (!str_contains($name, '-')) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Format permission harus modul-aksi (contoh: produk-export).',
                ]);
        }

        Permission::create(['name' => $name]);

        return redirect()
            ->route('permissions.index')
            ->with('success', "Permission \"{$name}\" berhasil ditambahkan.");
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permission->id),
                'regex:/^[a-z0-9_-]+$/',
            ],
        ], $this->validationMessages(), $this->validationAttributes());

        // Normalisasi
        $name = strtolower(trim($validated['name']));

        // Validasi format modul-aksi
        if (!str_contains($name, '-')) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Format permission harus modul-aksi (contoh: produk-export).',
                ]);
        }

        $oldName = $permission->name;
        $permission->update(['name' => $name]);

        return redirect()
            ->route('permissions.index')
            ->with('success', "Permission \"{$oldName}\" berhasil diubah menjadi \"{$name}\".");
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission)
    {
        // Proteksi: permission masih dipakai role
        $rolesCount = $permission->roles()->count();
        if ($rolesCount > 0) {
            return back()->with(
                'error',
                "Permission \"{$permission->name}\" masih digunakan oleh {$rolesCount} role. Cabut permission dari role terlebih dahulu."
            );
        }

        $name = $permission->name;
        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('success', "Permission \"{$name}\" berhasil dihapus.");
    }

    /**
     * Custom validation messages (Bahasa Indonesia).
     */
    private function validationMessages(): array
    {
        return [
            'name.required' => 'Nama permission wajib diisi.',
            'name.string'   => 'Nama permission harus berupa teks.',
            'name.max'      => 'Nama permission maksimal 255 karakter.',
            'name.unique'   => 'Permission sudah ada, gunakan nama lain.',
            'name.regex'    => 'Nama permission hanya boleh berisi huruf kecil, angka, tanda hubung (-), dan garis bawah (_).',
        ];
    }

    /**
     * Custom attribute names.
     */
    private function validationAttributes(): array
    {
        return [
            'name' => 'Nama Permission',
        ];
    }
}