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

        $roleOrder = "CASE name WHEN 'super-admin' THEN 1 WHEN 'admin' THEN 2 WHEN 'member' THEN 3 ELSE 4 END";
        $users = User::with(['roles', 'permissions'])
            ->orderBy('name')
            ->get()
            ->sortBy(function (User $user) {
                return match ($user->roles->first()?->name) {
                    'super-admin' => 1,
                    'admin' => 2,
                    'member' => 3,
                    default => 4,
                };
            })
            ->values();
        $roles = Role::whereIn('name', ['super-admin', 'admin', 'member'])
            ->orderByRaw($roleOrder)
            ->get();
        $permissions = [
            'households.view' => 'Households',
            'residents.view' => 'Residents',
            'assistance-requests.view' => 'Assistance Requests',
            'posts.view' => 'News & Announcements',
            'blotter.view' => 'Blotter Records',
            'immunization.view' => 'Immunization Records',
        ];

        return view('admin.users.index', compact('users', 'roles', 'permissions'));
    }

    public function updateRoles(Request $request, User $user)
    {
        if ($user->id === auth()->guard()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate([
            'role' => ['required', 'string', 'in:super-admin,admin,member'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:households.view,residents.view,assistance-requests.view,posts.view,blotter.view,immunization.view'],
        ]);

        $role = $request->string('role')->toString();
        $user->syncRoles($role);
        $user->syncPermissions($role === 'admin' ? $request->input('permissions', []) : []);

        return back()->with('success', 'User role and permissions updated successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->assignRole('admin');

        return back()->with('success', 'User created successfully.');
    }
}
