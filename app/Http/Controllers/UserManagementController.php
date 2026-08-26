<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
        abort_unless(auth()->user()->can('manage-users'), 403);

        $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($request->input('roles', []));

        return Redirect::route('admin.users.index')->with('status', 'User roles updated successfully.');
    }
}
