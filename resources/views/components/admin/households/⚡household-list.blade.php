<?php

use App\Models\Household;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $sortableFields = [
            'household_id',
            'street',
            'subdivision',
            'residents_count',
            'created_at',
        ];

        if (! in_array($field, $sortableFields, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $households = Household::query()
            ->withCount('residents')
            ->when(trim($this->search) !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {
                    $query->where('household_id', 'like', "%{$search}%")
                        ->orWhere('subdivision', 'like', "%{$search}%")
                        ->orWhere('block', 'like', "%{$search}%")
                        ->orWhere('lot', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('street', 'like', "%{$search}%");
                });
            })
            ->when(
                $this->sortField === 'residents_count',
                fn ($query) => $query->orderBy('residents_count', $this->sortDirection),
                fn ($query) => $query->orderBy($this->sortField, $this->sortDirection)
            )
            ->paginate(10);

        return $this->view([
            'households' => $households,
        ]);
    }
};
?>

<div>
    <div class="mb-6">
        <div class="relative max-w-md">
            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search household or address..."
                class="w-full rounded-lg border-gray-300 pl-10 pr-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>
    </div>

    <div wire:loading class="mb-3 text-sm text-gray-500" wire:target="search, sortBy, gotoPage, nextPage, previousPage">
        Updating households...
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <button type="button" wire:click="sortBy('household_id')" class="inline-flex items-center gap-2 hover:text-indigo-600">
                                Household
                                @if ($sortField === 'household_id')
                                    <span aria-hidden="true">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <button type="button" wire:click="sortBy('street')" class="inline-flex items-center gap-2 hover:text-indigo-600">
                                Address
                                @if ($sortField === 'street')
                                    <span aria-hidden="true">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <button type="button" wire:click="sortBy('residents_count')" class="inline-flex items-center gap-2 hover:text-indigo-600">
                                Members
                                @if ($sortField === 'residents_count')
                                    <span aria-hidden="true">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($households as $household)
                        <tr wire:key="household-{{ $household->id }}" class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                        <i class="fa-solid fa-house"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $household->household_id }}</p>
                                        <p class="text-xs text-gray-500">Registered {{ $household->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $household->full_address }}</p>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                    <i class="fa-solid fa-users"></i>
                                    {{ $household->residents_count }} {{ Str::plural('member', $household->residents_count) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('admin.households.show', $household) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fa-solid fa-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                    <i class="fa-solid fa-house text-xl"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-gray-900">No households found</h3>
                                <p class="mt-1 text-sm text-gray-500">Start by creating a new household.</p>
                                <a href="{{ route('admin.households.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                    <i class="fa-solid fa-plus"></i>
                                    Add Household
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($households->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $households->links() }}
            </div>
        @endif
    </div>
</div>
