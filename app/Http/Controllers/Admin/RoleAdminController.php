<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAdminController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('permissions')->orderBy('name')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['string','exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $data['name']]);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('status', 'Role created');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get();
        $assigned = $role->permissions()->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role','permissions','assigned'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:roles,name,'.$role->id],
            'permissions' => ['array'],
            'permissions.*' => ['string','exists:permissions,name'],
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('status', 'Role updated');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (strtolower($role->name) === 'admin') {
            return back()->with('status', 'Admin role cannot be deleted');
        }
        $role->delete();
        return redirect()->route('admin.roles.index')->with('status', 'Role deleted');
    }
}
