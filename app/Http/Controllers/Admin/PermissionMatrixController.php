<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionMatrixController extends Controller
{
    public function index(Request $request): View
    {
        // Route-level middleware should protect this (e.g., role:Admin)
        $roles = Role::query()->get();
        $preferred = ['Admin','Manager','Cashier','Kitchen'];
        $roles = $roles->sortBy(function($r) use ($preferred) {
            $idx = array_search($r->name, $preferred, true);
            return $idx === false ? 999 : $idx;
        })->values();
        $permissions = Permission::query()->orderBy('name')->get();

        // Group permissions by the first segment (module) before '.'
        $grouped = [];
        foreach ($permissions as $perm) {
            $parts = explode('.', $perm->name, 2);
            $group = ucfirst(str_replace(['-', '_'], ' ', $parts[0]));
            $grouped[$group][] = $perm;
        }

        // Build quick lookup map: [role_id][permission_name] => true
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rp = $role->permissions()->pluck('name')->all();
            $rolePermissions[$role->id] = array_fill_keys($rp, true);
        }

        return view('admin.permissions.matrix', [
            'roles' => $roles,
            'grouped' => $grouped,
            'rolePermissions' => $rolePermissions,
            'preferredOrder' => $preferred,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Route-level middleware should protect this (e.g., role:Admin)
        $assign = $request->input('assign', []); // [role_id => [permission_name, ...]]
        $roles = Role::query()->get();

        foreach ($roles as $role) {
            $permsForRole = $assign[$role->id] ?? [];
            // Ensure only existing permissions are synced
            $validPerms = Permission::query()->whereIn('name', $permsForRole)->pluck('name')->all();
            $role->syncPermissions($validPerms);
        }

        return back()->with('status', 'Permissions updated successfully');
    }
}
