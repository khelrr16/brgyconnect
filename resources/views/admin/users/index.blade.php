<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">
            User Management
        </h2>

        <span class="text-sm text-gray-500">
            {{ $users->count() }} active users
        </span>
    </div>
</x-slot>


<div
    class="py-8"
    x-data="{ addAdminOpen: {{ $errors->any() ? 'true' : 'false' }} }">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


        {{-- ================================================= --}}
        {{-- MAIN CARD --}}
        {{-- ================================================= --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">


            {{-- ================================================= --}}
            {{-- CARD HEADER --}}
            {{-- ================================================= --}}

            <div class="px-6 py-5 border-b border-gray-100
                        flex flex-col sm:flex-row sm:items-center
                        sm:justify-between gap-4">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        User Accounts
                    </h3>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Manage registered users and assign roles.
                    </p>

                </div>


                {{-- Add Admin Button --}}
                <button
                    type="button"
                    @click="addAdminOpen = true"
                    class="inline-flex items-center justify-center gap-2
                           rounded-lg
                           bg-indigo-600
                           border border-indigo-700
                           px-5 py-2.5
                           text-sm font-semibold text-white
                           shadow-md
                           hover:bg-indigo-700
                           hover:shadow-lg
                           active:bg-indigo-800
                           active:shadow-sm
                           transition-all duration-150
                           focus:outline-none
                           focus:ring-2
                           focus:ring-indigo-500
                           focus:ring-offset-2
                           shrink-0"
                >

                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Add Admin Account

                </button>

            </div>


            {{-- ================================================= --}}
            {{-- TABLE --}}
            {{-- ================================================= --}}

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-left
                                       text-xs font-semibold text-gray-500
                                       uppercase tracking-wider"
                            >
                                User
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-left
                                       text-xs font-semibold text-gray-500
                                       uppercase tracking-wider"
                            >
                                Email
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-left
                                       text-xs font-semibold text-gray-500
                                       uppercase tracking-wider"
                            >
                                Role
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-right
                                       text-xs font-semibold text-gray-500
                                       uppercase tracking-wider"
                            >
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="bg-white divide-y divide-gray-100">

                        @forelse ($users as $user)

                            {{-- Don't display Super Admin --}}


                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">


                                {{-- ================================================= --}}
                                {{-- USER --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="flex items-center">

                                        <div
                                            class="h-9 w-9 rounded-full
                                                   bg-indigo-100 text-indigo-600
                                                   flex items-center justify-center
                                                   text-sm font-bold"
                                        >
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="ml-3">

                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $user->name }}
                                            </div>

                                            <div class="text-xs text-gray-500">
                                                ID: {{ $user->id }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- EMAIL --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="text-sm text-gray-700">
                                        {{ $user->email }}
                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- ROLE --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    @forelse ($user->roles as $role)

                                        <span
                                            class="inline-flex items-center
                                                   rounded-full
                                                   bg-indigo-50
                                                   px-3 py-1
                                                   text-xs font-medium
                                                   text-indigo-700
                                                   ring-1 ring-inset
                                                   ring-indigo-700/10"
                                        >
                                            {{ $role->name }}
                                        </span>

                                    @empty

                                        <span
                                            class="inline-flex items-center
                                                   rounded-full
                                                   bg-gray-100
                                                   px-3 py-1
                                                   text-xs font-medium
                                                   text-gray-600
                                                   ring-1 ring-inset
                                                   ring-gray-500/10"
                                        >
                                            No role
                                        </span>

                                    @endforelse

                                </td>


                                {{-- ================================================= --}}
                                {{-- ACTIONS --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-4 whitespace-nowrap text-right">

                                    <div class="flex items-center justify-end gap-2">


                                        {{-- ================================================= --}}
                                        {{-- ROLE CHANGE --}}
                                        {{-- ================================================= --}}

                                        @if ($user->id === auth()->id())

                                            {{-- Current user cannot change their own role --}}
                                            <span
                                                class="inline-flex items-center gap-1.5
                                                       rounded-md
                                                       bg-gray-100
                                                       border border-gray-200
                                                       px-3 py-1.5
                                                       text-xs font-medium
                                                       text-gray-500"
                                                title="You cannot change your own role."
                                            >

                                                <svg
                                                    class="w-3.5 h-3.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                                    />
                                                </svg>

                                                Your Account

                                            </span>

                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.roles.update', $user) }}"
                                                x-data="{ selectedRole: '{{ $user->roles->first()?->name ?? 'member' }}', permissionsOpen: false }"
                                                class="flex flex-wrap items-center justify-end gap-2"
                                            >

                                                @csrf
                                                @method('PATCH')


                                                <select
                                                    name="role"
                                                    x-model="selectedRole"
                                                    class="block w-36 rounded-md
                                                           border-gray-300
                                                           shadow-sm
                                                           text-sm
                                                           focus:border-indigo-500
                                                           focus:ring-indigo-500
                                                           py-1.5 pl-3 pr-8
                                                           cursor-pointer
                                                           hover:border-gray-400
                                                           transition-colors"
                                                >

                                                    <option value="">
                                                        -- Select Role --
                                                    </option>

                                                    @foreach ($roles as $role)

                                                        <option
                                                            value="{{ $role->name }}"
                                                            {{ $user->hasRole($role->name) ? 'selected' : '' }}
                                                        >
                                                            {{ $role->name }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                                <button
                                                    type="button"
                                                    x-show="selectedRole === 'admin'"
                                                    x-cloak
                                                    @click="permissionsOpen = true"
                                                    class="inline-flex items-center gap-1.5 rounded-md border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-sm font-medium text-indigo-700 hover:bg-indigo-100 transition"
                                                >
                                                    <i class="fa-solid fa-sliders"></i>
                                                    Permissions
                                                </button>

                                                <div
                                                    x-show="permissionsOpen"
                                                    x-cloak
                                                    x-transition.opacity
                                                    @keydown.escape.window="permissionsOpen = false"
                                                    @click.self="permissionsOpen = false"
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 px-4 text-left"
                                                >
                                                    <div x-show="permissionsOpen" x-transition class="w-full max-w-md rounded-xl bg-white shadow-2xl ring-1 ring-black/10">
                                                        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                                                            <div>
                                                                <h3 class="font-semibold text-gray-900">Admin permissions</h3>
                                                                <p class="mt-1 text-xs text-gray-500">Choose which areas this admin can access.</p>
                                                            </div>
                                                            <button type="button" @click="permissionsOpen = false" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600" aria-label="Close permissions">
                                                                <i class="fa-solid fa-xmark"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid gap-3 px-5 py-5 sm:grid-cols-2">
                                                            @foreach($permissions as $permission => $label)
                                                                <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <input type="checkbox" name="permissions[]" value="{{ $permission }}" @checked($user->hasPermissionTo($permission)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                                    {{ $label }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        <div class="flex justify-end border-t border-gray-100 px-5 py-4">
                                                            <button type="button" @click="permissionsOpen = false" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Done</button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5
                                                           rounded-md
                                                           bg-white
                                                           border border-gray-300
                                                           px-3 py-1.5
                                                           text-sm font-medium
                                                           text-gray-700
                                                           shadow-sm
                                                           hover:bg-gray-50
                                                           hover:border-gray-400
                                                           hover:shadow
                                                           active:bg-gray-100
                                                           transition-all"
                                                >

                                                    <svg
                                                        class="w-3.5 h-3.5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 13l4 4L19 7"
                                                        />
                                                    </svg>

                                                    Save

                                                </button>

                                            </form>

                                        @endif


                                        {{-- ================================================= --}}
                                        {{-- RESET PASSWORD --}}
                                        {{-- ================================================= --}}

                                        @if ($user->id !== auth()->id())

                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.password.reset', $user) }}"
                                                onsubmit="return confirm('Are you sure you want to reset the password of this user?');"
                                                class="inline"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5
                                                           rounded-md
                                                           bg-amber-50
                                                           border border-amber-200
                                                           px-3 py-1.5
                                                           text-sm font-medium
                                                           text-amber-700
                                                           shadow-sm
                                                           hover:bg-amber-100
                                                           hover:border-amber-300
                                                           hover:shadow
                                                           active:bg-amber-200
                                                           transition-all"
                                                    title="Reset Password"
                                                >

                                                    <svg
                                                        class="w-3.5 h-3.5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582M20 20v-5h-.581M5.08 9A7 7 0 0117.66 6.34L20 9m-2.34 8.66A7 7 0 015.08 15L4 17"
                                                        />
                                                    </svg>

                                                    Reset Password

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-12 text-center">

                                    <div
                                        class="flex flex-col items-center
                                               justify-center text-gray-400"
                                    >

                                        <svg
                                            class="w-12 h-12 mb-3 text-gray-300"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.5"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium text-gray-500">
                                            No users found
                                        </p>

                                        <p class="text-xs text-gray-400 mt-1">
                                            All users may have the super-admin role.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ================================================= --}}
            {{-- PAGINATION --}}
            {{-- ================================================= --}}

            @if(method_exists($users, 'links'))

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $users->links() }}
                </div>

            @endif

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- ADD ADMIN MODAL --}}
    {{-- ================================================= --}}

    <div
        x-show="addAdminOpen"
        x-cloak
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center
               bg-gray-900/50 backdrop-blur-sm px-4"
        @keydown.escape.window="addAdminOpen = false">

        <div
            x-show="addAdminOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="w-full max-w-md rounded-xl bg-white
                   shadow-2xl ring-1 ring-black/10"
        >


            {{-- Modal Header --}}

            <div
                class="flex items-center justify-between
                       px-6 py-4 border-b border-gray-100"
            >

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Add Admin Account
                    </h3>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Create a new administrator account.
                    </p>

                </div>


                <button
                    type="button"
                    @click="addAdminOpen = false"
                    class="rounded-lg p-2
                           text-gray-400
                           hover:text-gray-600
                           hover:bg-gray-100
                           transition-colors"
                >

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>


            {{-- Form --}}

            <form
                method="POST"
                action="{{ route('admin.users.store') }}"
                class="px-6 py-5 space-y-4"
            >

                @csrf


                {{-- Name --}}

                <div>

                    <x-input-label
                        for="admin_name"
                        value="Full Name"
                        class="text-gray-700 font-medium"
                    />

                    <x-text-input
                        id="admin_name"
                        name="name"
                        type="text"
                        class="mt-1.5 block w-full rounded-lg
                               border-gray-300 shadow-sm
                               focus:border-indigo-500
                               focus:ring-indigo-500"
                        required
                        autofocus
                        placeholder="Enter full name"
                    />

                    <x-input-error
                        class="mt-1.5"
                        :messages="$errors->get('name')"
                    />

                </div>


                {{-- Email --}}

                <div>

                    <x-input-label
                        for="admin_email"
                        value="Email Address"
                        class="text-gray-700 font-medium"
                    />

                    <x-text-input
                        id="admin_email"
                        name="email"
                        type="email"
                        class="mt-1.5 block w-full rounded-lg
                               border-gray-300 shadow-sm
                               focus:border-indigo-500
                               focus:ring-indigo-500"
                        required
                        placeholder="admin@example.com"
                    />

                    <x-input-error
                        class="mt-1.5"
                        :messages="$errors->get('email')"
                    />

                </div>


                {{-- Password --}}

                <div>

                    <x-input-label
                        for="admin_password"
                        value="Password"
                        class="text-gray-700 font-medium"
                    />

                    <x-text-input
                        id="admin_password"
                        name="password"
                        type="password"
                        class="mt-1.5 block w-full rounded-lg
                               border-gray-300 shadow-sm
                               focus:border-indigo-500
                               focus:ring-indigo-500"
                        required
                        placeholder="••••••••"
                    />

                    <x-input-error
                        class="mt-1.5"
                        :messages="$errors->get('password')"
                    />

                </div>


                {{-- Confirm Password --}}

                <div>

                    <x-input-label
                        for="admin_password_confirmation"
                        value="Confirm Password"
                        class="text-gray-700 font-medium"
                    />

                    <x-text-input
                        id="admin_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="mt-1.5 block w-full rounded-lg
                               border-gray-300 shadow-sm
                               focus:border-indigo-500
                               focus:ring-indigo-500"
                        required
                        placeholder="••••••••"
                    />

                </div>


                {{-- Buttons --}}

                <div class="flex items-center justify-end gap-3 pt-3">

                    <button
                        type="button"
                        @click="addAdminOpen = false"
                        class="rounded-lg
                               border border-gray-300
                               bg-white
                               px-4 py-2.5
                               text-sm font-medium
                               text-gray-700
                               shadow-sm
                               hover:bg-gray-50
                               hover:shadow
                               transition-all"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="rounded-lg
                               border border-indigo-700
                               bg-indigo-600
                               px-4 py-2.5
                               text-sm font-medium
                               text-white
                               shadow-sm
                               hover:bg-indigo-700
                               hover:shadow-md
                               active:bg-indigo-800
                               transition-all
                               focus:outline-none
                               focus:ring-2
                               focus:ring-indigo-500
                               focus:ring-offset-2">
                               
                        <i class="fa-solid fa-plus"></i>

                        Create Admin

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>
