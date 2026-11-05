<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index(Request $request)
    {
        $query = Permission::with('roles')->withCount('roles');

        // Filter: search by name
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter: by module group (e.g. "kas-masuk", "bahan-baku", "produk", etc.)
        if ($request->filled('group')) {
            $grp = trim((string) $request->group);
            $query->where('name', 'like', $grp.'-%');
        }

        // Filter: by status pemakaian
        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->has('roles');
            } elseif ($request->status === 'unused') {
                $query->doesntHave('roles');
            }
        }

        // Sort
        $sort = $request->get('sort', 'name_asc');
        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'roles_desc' => $query->orderBy('roles_count', 'desc')->orderBy('name', 'asc'),
            'roles_asc' => $query->orderBy('roles_count', 'asc')->orderBy('name', 'asc'),
            default => $query->orderBy('name', 'asc'),
        };

        $permissions = $query->paginate(15)->withQueryString();

        // Ambil semua grup modul secara cross-database (MySQL & SQLite compatible) sesuai urutan menu sidebar
        $allNames = Permission::orderBy('name')->pluck('name');
        $orderedKeys = array_keys(RoleController::MODULE_META);
        $groups = $allNames
            ->map(fn (string $name) => RoleController::extractModuleGroup($name))
            ->unique()
            ->sortBy(function (string $g) use ($orderedKeys) {
                $idx = array_search($g, $orderedKeys, true);

                return $idx !== false ? sprintf('%03d_%s', $idx, $g) : '999_'.$g;
            })
            ->values();

        // Total stats untuk 4 KPI Cards
        $totalPermissions = $allNames->count();
        $totalGroups = $groups->count();
        $totalUsedPermissions = Permission::has('roles')->count();
        $totalUnusedPermissions = Permission::doesntHave('roles')->count();
        $moduleMeta = RoleController::MODULE_META;
        $permLabels = RoleController::PERMISSION_LABELS;

        return view('permissions.index', compact(
            'permissions',
            'groups',
            'totalPermissions',
            'totalGroups',
            'totalUsedPermissions',
            'totalUnusedPermissions',
            'moduleMeta',
            'permLabels'
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

        $name = strtolower(trim($validated['name']));

        if (! str_contains($name, '-')) {
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

        $name = strtolower(trim($validated['name']));

        if (! str_contains($name, '-')) {
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
            'name.string' => 'Nama permission harus berupa teks.',
            'name.max' => 'Nama permission maksimal 255 karakter.',
            'name.unique' => 'Permission sudah ada, gunakan nama lain.',
            'name.regex' => 'Nama permission hanya boleh berisi huruf kecil, angka, tanda hubung (-), dan garis bawah (_).',
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
