<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
            User Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('status'))
                        <div class="mb-6 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="text-left text-sm uppercase tracking-wide text-gray-600">
                                    <th class="py-3 px-4">Name</th>
                                    <th class="py-3 px-4">Email</th>
                                    <th class="py-3 px-4">Role</th>
                                    <th class="py-3 px-4">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="py-3 px-4">
                                            {{ $user->name }}
                                        </td>

                                        <td class="py-3 px-4">
                                            {{ $user->email }}
                                        </td>

                                        <td class="py-3 px-4">
                                            @forelse ($user->roles as $role)
                                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-medium text-indigo-700">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-gray-500">
                                                    No role assigned
                                                </span>
                                            @endforelse
                                        </td>

                                        <td class="py-3 px-4">
                                            <form method="POST"
                                                action="{{ route('admin.users.roles.update', $user) }}">
                                                @csrf
                                                @method('PATCH')

                                                <div class="flex items-center gap-3">

                                                    <select
                                                        name="role"
                                                        class="rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    >
                                                        <option value="">Select Role</option>

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
                                                        type="submit"
                                                        class="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500"
                                                    >
                                                        Save
                                                    </button>

                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>