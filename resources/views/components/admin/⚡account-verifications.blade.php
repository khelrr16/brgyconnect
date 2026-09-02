<?php

use App\Models\AccountVerification;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = 'pending';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $verifications = AccountVerification::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when(
                $this->status !== 'all',
                fn ($query) => $query->where('status', $this->status)
            )
            ->latest()
            ->paginate(10);

        return $this->view([
            'verifications' => $verifications,
        ]);
    }
};

?>

<div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">

        {{-- Search --}}
        <div class="relative flex-1">

            <i class="fa-solid fa-magnifying-glass
                      absolute left-3 top-1/2 -translate-y-1/2
                      text-gray-400"></i>

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search name or email..."
                class="w-full rounded-lg
                       border-gray-300
                       pl-10
                       shadow-sm
                       focus:border-indigo-500
                       focus:ring-indigo-500"
            >

        </div>


        {{-- Status --}}
        <select
            wire:model.live="status"
            class="rounded-lg
                   border-gray-300
                   shadow-sm
                   focus:border-indigo-500
                   focus:ring-indigo-500"
        >
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="all">All Status</option>
        </select>

    </div>


    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-50">

                <tr>

                    <th class="px-6 py-3 text-left
                               text-xs font-semibold
                               text-gray-500 uppercase">
                        Applicant
                    </th>

                    <th class="px-6 py-3 text-left
                               text-xs font-semibold
                               text-gray-500 uppercase">
                        Email
                    </th>

                    <th class="px-6 py-3 text-left
                               text-xs font-semibold
                               text-gray-500 uppercase">
                        Submitted
                    </th>

                    <th class="px-6 py-3 text-left
                               text-xs font-semibold
                               text-gray-500 uppercase">
                        Status
                    </th>

                    <th class="px-6 py-3 text-right
                               text-xs font-semibold
                               text-gray-500 uppercase">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100">

                @forelse ($verifications as $verification)

                    <tr class="hover:bg-gray-50">

                        {{-- Applicant --}}
                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <div class="w-9 h-9 rounded-full
                                            bg-indigo-100
                                            text-indigo-600
                                            flex items-center
                                            justify-center
                                            font-bold">

                                    {{ strtoupper(
                                        substr($verification->user->name, 0, 1)
                                    ) }}

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $verification->user->name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        ID: {{ $verification->user->id }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- Email --}}
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $verification->user->email }}
                        </td>


                        {{-- Submitted --}}
                        <td class="px-6 py-4 text-sm text-gray-600">

                            {{ $verification->created_at->format('M d, Y') }}

                            <p class="text-xs text-gray-400">
                                {{ $verification->created_at->format('h:i A') }}
                            </p>

                        </td>


                        {{-- Status --}}
                        <td class="px-6 py-4">

                            @if ($verification->status === 'pending')

                                <span class="inline-flex items-center gap-1.5
                                             rounded-full
                                             bg-amber-50
                                             px-3 py-1
                                             text-xs font-medium
                                             text-amber-700">

                                    <i class="fa-solid fa-clock"></i>

                                    Pending

                                </span>

                            @elseif ($verification->status === 'approved')

                                <span class="inline-flex items-center gap-1.5
                                             rounded-full
                                             bg-green-50
                                             px-3 py-1
                                             text-xs font-medium
                                             text-green-700">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Approved

                                </span>

                            @else

                                <span class="inline-flex items-center gap-1.5
                                             rounded-full
                                             bg-red-50
                                             px-3 py-1
                                             text-xs font-medium
                                             text-red-700">

                                    <i class="fa-solid fa-circle-xmark"></i>

                                    Rejected

                                </span>

                            @endif

                        </td>


                        {{-- Action --}}
                        <td class="px-6 py-4 text-right">

                            <a
                                href="{{ route(
                                    'admin.verifications.show',
                                    $verification
                                ) }}"
                                class="inline-flex items-center gap-2
                                       rounded-lg
                                       bg-indigo-600
                                       border border-indigo-700
                                       px-4 py-2
                                       text-sm font-semibold
                                       text-white
                                       shadow-sm
                                       hover:bg-indigo-700"
                            >

                                <i class="fa-solid fa-eye"></i>

                                Review

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-gray-500"
                        >

                            <i class="fa-solid fa-inbox
                                      text-3xl text-gray-300"></i>

                            <p class="mt-3 text-sm font-medium">
                                No verification requests found.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    <div class="mt-4">
        {{ $verifications->links() }}
    </div>

</div>