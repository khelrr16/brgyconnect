<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('manage-users'), 403);

        $users = User::with('roles')->latest()->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        if ($user->id === auth()->guard()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate([
            'role' => ['nullable', 'string', 'exists:roles,name'],
        ]);

        $user->syncRoles($request->input('role', []));

        return back()->with('success', 'User roles updated successfully.');
    }

    public function store(Request $request)
    {
        dd($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'User created successfully.');
    }
}
