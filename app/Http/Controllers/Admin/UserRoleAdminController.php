<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserRoleAdminController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('name')->with('roles')->get(['id','name','email']);
        $roles = Role::orderBy('name')->get(['id','name']);
        return view('admin.users.roles.index', compact('users','roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'roles' => ['array'],
            'roles.*' => ['string','exists:roles,name'],
        ]);

        $user->syncRoles($data['roles'] ?? []);
        return back()->with('status', 'User roles updated');
    }
}
