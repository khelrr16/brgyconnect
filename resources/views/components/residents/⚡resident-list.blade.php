<?php

use App\Models\Resident;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $sortableFields = [
            'resident_id',
            'last_name',
            'birth_date',
            'sex',
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
        $residents = Resident::query()
            ->when($this->search, function ($query) {

                $terms = preg_split('/\s+/', trim($this->search));

                foreach ($terms as $term) {
                    $query->where(function ($query) use ($term) {

                        $query->where('first_name', 'like', "%{$term}%")
                            ->orWhere('middle_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%")
                            ->orWhere('resident_id', 'like', "%{$term}%");

                    });
                }

            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return $this->view([
            'residents' => $residents,
        ]);
    }
};
?>

<div>
    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by name (First Name, Middle Name, Last Name) or resident ID..."
            class="flex-1 border-gray-300 rounded-lg">
            
        <a
            href="{{ route('residents.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + New Resident
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="px-4 py-3">
                        <button type="button" wire:click="sortBy('resident_id')" class="inline-flex items-center gap-2 font-semibold hover:text-blue-600">
                            Resident ID
                            @if($sortField === 'resident_id')
                                <span aria-hidden="true">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">
                        <button type="button" wire:click="sortBy('last_name')" class="inline-flex items-center gap-2 font-semibold hover:text-blue-600">
                            Name
                            @if($sortField === 'last_name')
                                <span aria-hidden="true">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">
                        <button type="button" wire:click="sortBy('birth_date')" class="inline-flex items-center gap-2 font-semibold hover:text-blue-600">
                            Birthday
                            @if($sortField === 'birth_date')
                                <span aria-hidden="true">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">
                        <button type="button" wire:click="sortBy('sex')" class="inline-flex items-center gap-2 font-semibold hover:text-blue-600">
                            Sex
                            @if($sortField === 'sex')
                                <span aria-hidden="true">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($residents as $resident)
                    <tr class="border-b">
                        <td class="px-4 py-3">
                            {{ $resident->resident_id }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $resident->last_name }},
                            {{ $resident->first_name }}
                            {{ $resident->middle_name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $resident->birth_date->format('F j, Y') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $resident->sex }}
                        </td>

                        <td class="px-4 py-3">
                            <a
                                href="{{ route('residents.show', $resident) }}"
                                class="text-blue-600 hover:underline">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="5"
                            class="px-4 py-6 text-center text-gray-500">
                            No residents found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $residents->links() }}
    </div>
</div>