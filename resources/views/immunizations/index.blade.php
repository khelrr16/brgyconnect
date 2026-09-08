<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :items="[['label' => 'Immunization Records']]" />

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Child Health</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">Immunization Records</h1>
                <p class="mt-1 text-sm text-gray-500">Track infant vaccines, nutrition, and completion status.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.immunizations.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    <i class="fa-solid fa-plus"></i>
                    New record
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach([
                ['label' => 'Total records', 'value' => $totalImmunizations, 'icon' => 'fa-children', 'classes' => 'bg-blue-50 text-blue-600'],
                ['label' => 'FIC recorded', 'value' => $fullyImmunized, 'icon' => 'fa-circle-check', 'classes' => 'bg-green-50 text-green-600'],
                ['label' => 'CIC recorded', 'value' => $completelyImmunized, 'icon' => 'fa-shield-heart', 'classes' => 'bg-emerald-50 text-emerald-600'],
                ['label' => 'Low birth weight', 'value' => $lowBirthWeight, 'icon' => 'fa-triangle-exclamation', 'classes' => 'bg-orange-50 text-orange-600'],
                ['label' => 'Assessments', 'value' => $assessments, 'icon' => 'fa-weight-scale', 'classes' => 'bg-purple-50 text-purple-600'],
            ] as $stat)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stat['value']) }}</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg {{ $stat['classes'] }}">
                            <i class="fa-solid {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.immunizations.index') }}" class="relative max-w-lg">
            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search infant, parent, or resident ID..." class="w-full rounded-lg border-gray-300 pl-10 pr-4 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </form>

        <form method="GET" action="{{ route('admin.immunizations.report') }}" target="_blank" class="rounded-xl border border-blue-100 bg-blue-50/50 p-4 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Generate activity report</h2>
                    <p class="mt-1 text-xs text-gray-500">Count vaccine doses, Vitamin A, and MNP events recorded between two dates.</p>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:w-auto lg:grid-cols-[minmax(150px,1fr)_minmax(150px,1fr)_auto]">
                    <label class="text-xs font-semibold text-gray-600">From
                        <input type="date" name="date_from" required value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                    <label class="text-xs font-semibold text-gray-600">To
                        <input type="date" name="date_to" required value="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </label>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        <i class="fa-solid fa-print"></i>
                        Generate printable
                    </button>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @php
                                $sortLink = function (string $column): string {
                                    $nextDirection = request('sort') === $column && request('direction') === 'asc' ? 'desc' : 'asc';

                                    return route('admin.immunizations.index', array_merge(request()->query(), [
                                        'sort' => $column,
                                        'direction' => $nextDirection,
                                        'page' => 1,
                                    ]));
                                };
                            @endphp
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"><a href="{{ $sortLink('family_id') }}" class="inline-flex items-center gap-1 hover:text-blue-600">Family ID <i class="fa-solid fa-sort"></i></a></th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"><a href="{{ $sortLink('infant') }}" class="inline-flex items-center gap-1 hover:text-blue-600">Infant <i class="fa-solid fa-sort"></i></a></th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Parent / Guardian</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"><a href="{{ $sortLink('birthday') }}" class="inline-flex items-center gap-1 hover:text-blue-600">Birth date <i class="fa-solid fa-sort"></i></a></th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Progress</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($immunizations as $immunization)
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="text-sm font-semibold text-blue-700">{{ $immunization->family_id }}</p>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $immunization->full_name }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $immunization->resident?->resident_id ?? 'Manual entry' }}</p>
                                    @if($immunization->low_birth_weight)
                                        <span class="mt-2 inline-flex items-center gap-1 rounded-full bg-orange-100 px-2.5 py-1 text-xs font-semibold text-orange-700">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            Low birth weight
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ trim($immunization->parent_first_name . ' ' . $immunization->parent_last_name) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $immunization->birthday?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700"><i class="fa-solid fa-syringe"></i>{{ $immunization->marked_doses_count }} doses</span>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700"><i class="fa-solid fa-weight-scale"></i>{{ $immunization->nutritional_assessments_count }} assessments</span>
                                    </div>
                                    <p class="mt-2 text-xs font-semibold {{ $immunization->completion_status === 'In progress' ? 'text-yellow-600' : 'text-green-600' }}">{{ $immunization->completion_status }}</p>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right"><a href="{{ route('admin.immunizations.show', $immunization) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"><i class="fa-solid fa-eye"></i>View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-16 text-center"><i class="fa-solid fa-syringe text-2xl text-gray-300"></i><h3 class="mt-4 text-sm font-semibold text-gray-900">No immunization records found</h3><p class="mt-1 text-sm text-gray-500">Create an immunization record to start tracking child health.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($immunizations->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">{{ $immunizations->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
