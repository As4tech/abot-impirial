<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionAdminController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::orderBy('name')->get();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:permissions,name'],
        ]);
        Permission::create(['name' => $data['name']]);
        return back()->with('status', 'Permission created');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();
        return back()->with('status', 'Permission deleted');
    }
}
