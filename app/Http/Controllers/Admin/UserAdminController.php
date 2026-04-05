<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserAdminController extends Controller
{
    public function show(User $user): View
    {
        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get(['id','name']);
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6','confirmed'],
            'roles' => ['array'],
            'roles.*' => ['string','exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // hashed via cast in model
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('admin.users.roles.index')->with('status', 'User created');
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get(['id','name']);
        $assigned = $user->roles()->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'assigned'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:6','confirmed'],
            'roles' => ['array'],
            'roles.*' => ['string','exists:roles,name'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.roles.index')->with('status', 'User updated');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.roles.index')->with('status', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('admin.users.roles.index')->with('status', 'User deleted');
    }
}
