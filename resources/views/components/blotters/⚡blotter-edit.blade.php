<?php

use App\Models\BlotterRecord;
use App\Models\Resident;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public int $blotterId;
    public string $blotter_number = '';

    // =========================
    // INCIDENT
    // =========================

    public string $incident_date = '';
    public string $incident_time = '';
    public string $incident_type = '';
    public string $incident_location = '';
    public string $incident_description = '';
    public string $status = 'Pending';
    public string $remarks = '';

    // =========================
    // PARTY FORM
    // =========================

    public string $partyRole = 'Complainant';

    public ?int $selectedResidentId = null;

    public string $residentSearch = '';

    public string $partyFirstName = '';
    public string $partyMiddleName = '';
    public string $partyLastName = '';
    public string $partyExtensionName = '';
    public string $partyContactNumber = '';
    public string $partyAddress = '';

    public array $parties = [];

    public int $partyFormKey = 0;

    // =========================
    // ATTACHMENTS
    // =========================

    public array $existingAttachments = [];

    public array $newAttachments = [];


    public function mount(BlotterRecord $blotter): void
    {
        $this->blotterId = $blotter->id;
        $this->blotter_number = $blotter->blotter_number;

        // Incident
        $this->incident_date = $blotter->incident_date?->format('Y-m-d') ?? '';
        $this->incident_time = $blotter->incident_time
        ? \Carbon\Carbon::parse($blotter->incident_time)->format('H:i')
        : '';
        $this->incident_type = $blotter->incident_type;
        $this->incident_location = $blotter->incident_location;
        $this->incident_description = $blotter->incident_description;
        $this->status = $blotter->status;
        $this->remarks = $blotter->remarks ?? '';

        // Existing parties
        $this->parties = $blotter->parties
            ->map(function ($party) {
                return [
                    'id' => $party->id,
                    'resident_id' => $party->resident_id,
                    'role' => $party->role,
                    'first_name' => $party->first_name,
                    'middle_name' => $party->middle_name ?? '',
                    'last_name' => $party->last_name,
                    'extension_name' => $party->extension_name ?? '',
                    'contact_number' => $party->contact_number ?? '',
                    'address' => $party->address ?? '',
                ];
            })
            ->values()
            ->toArray();

        // Existing attachments
        $this->existingAttachments = $blotter->attachments
            ->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'file_path' => $attachment->file_path,
                    'original_name' => $attachment->original_name,
                    'mime_type' => $attachment->mime_type,
                    'file_size' => $attachment->file_size,
                ];
            })
            ->values()
            ->toArray();
    }


    // ============================================================
    // RESIDENT SEARCH
    // ============================================================

    public function selectResident(int $residentId): void
    {
        $this->clearPartyForm();

        $resident = Resident::findOrFail($residentId);

        $this->selectedResidentId = $resident->id;

        $this->partyFirstName = $resident->first_name;
        $this->partyMiddleName = $resident->middle_name ?? '';
        $this->partyLastName = $resident->last_name;
        $this->partyExtensionName = $resident->extension_name ?? '';
        $this->partyContactNumber = $resident->contact_number ?? '';
        $this->partyAddress = $resident->address ?? '';

        $this->residentSearch = '';
    }


    public function clearPartyForm(): void
    {
        $this->selectedResidentId = null;

        $this->partyFirstName = '';
        $this->partyMiddleName = '';
        $this->partyLastName = '';
        $this->partyExtensionName = '';
        $this->partyContactNumber = '';
        $this->partyAddress = '';

        $this->residentSearch = '';
    }


    // ============================================================
    // ADD PARTY
    // ============================================================

    public function addParty(): void
    {
        $this->validate([
            'partyRole' => [
                'required',
                'in:Complainant,Respondent,Witness',
            ],

            'partyFirstName' => [
                'required',
                'string',
                'max:255',
            ],

            'partyMiddleName' => [
                'nullable',
                'string',
                'max:255',
            ],

            'partyLastName' => [
                'required',
                'string',
                'max:255',
            ],

            'partyExtensionName' => [
                'nullable',
                'string',
                'max:50',
            ],

            'partyContactNumber' => [
                'nullable',
                'string',
                'max:20',
            ],

            'partyAddress' => [
                'nullable',
                'string',
            ],
        ]);

        $this->parties[] = [
            'id' => null,
            'resident_id' => $this->selectedResidentId,
            'role' => $this->partyRole,
            'first_name' => $this->partyFirstName,
            'middle_name' => $this->partyMiddleName,
            'last_name' => $this->partyLastName,
            'extension_name' => $this->partyExtensionName,
            'contact_number' => $this->partyContactNumber,
            'address' => $this->partyAddress,
        ];

        $this->clearPartyForm();

        $this->partyFormKey++;
        $this->partyRole = 'Complainant';
    }


    // ============================================================
    // REMOVE PARTY
    // ============================================================

    public function removeParty(int $index): void
    {
        unset($this->parties[$index]);

        $this->parties = array_values($this->parties);
    }


    // ============================================================
    // REMOVE EXISTING ATTACHMENT
    // ============================================================

    public function removeAttachment(int $attachmentId): void
    {
        $attachment = $this->blotter()
            ->attachments()
            ->findOrFail($attachmentId);

        if ($attachment->file_path) {
            Storage::disk('public')->delete(
                $attachment->file_path
            );
        }

        $attachment->delete();

        $this->existingAttachments = collect(
            $this->existingAttachments
        )
            ->reject(
                fn ($attachment) => $attachment['id'] === $attachmentId
            )
            ->values()
            ->toArray();
    }


    // ============================================================
    // REMOVE NEW ATTACHMENT BEFORE SAVE
    // ============================================================

    public function removeNewAttachment(int $index): void
    {
        unset($this->newAttachments[$index]);

        $this->newAttachments = array_values(
            $this->newAttachments
        );
    }


    // ============================================================
    // SAVE
    // ============================================================

    public function save()
    {
        $validated = $this->validate([
            'incident_date' => [
                'required',
                'date',
            ],

            'incident_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'incident_type' => [
                'required',
                'string',
                'max:255',
            ],

            'incident_location' => [
                'required',
                'string',
                'max:255',
            ],

            'incident_description' => [
                'required',
                'string',
            ],

            'status' => [
                'required',
                'in:Pending,Under Investigation,Settled,Referred,Closed',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'newAttachments' => [
                'nullable',
                'array',
                'max:10',
            ],

            'newAttachments.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);


        // Make sure there is still a complainant.
        if (! collect($this->parties)
            ->contains('role', 'Complainant')
        ) {
            $this->addError(
                'parties',
                'At least one complainant is required.'
            );

            return;
        }


        $blotter = $this->blotter();


        // Update main blotter.
        $blotter->update([
            'incident_date' => $validated['incident_date'],
            'incident_time' => $validated['incident_time'],
            'incident_type' => $validated['incident_type'],
            'incident_location' => $validated['incident_location'],
            'incident_description' => $validated['incident_description'],
            'status' => $validated['status'],
            'remarks' => $validated['remarks'],
        ]);


        // ========================================================
        // Synchronize parties
        // ========================================================

        $existingPartyIds = collect($this->parties)
            ->pluck('id')
            ->filter()
            ->values();

        $blotter->parties()
            ->whereNotIn('id', $existingPartyIds)
            ->delete();


        foreach ($this->parties as $party) {

            if ($party['id']) {

                $blotter->parties()
                    ->where('id', $party['id'])
                    ->update([
                        'resident_id' => $party['resident_id'],
                        'role' => $party['role'],
                        'first_name' => $party['first_name'],
                        'middle_name' => $party['middle_name'],
                        'last_name' => $party['last_name'],
                        'extension_name' => $party['extension_name'],
                        'contact_number' => $party['contact_number'],
                        'address' => $party['address'],
                    ]);

            } else {

                $blotter->parties()->create([
                    'resident_id' => $party['resident_id'],
                    'role' => $party['role'],
                    'first_name' => $party['first_name'],
                    'middle_name' => $party['middle_name'],
                    'last_name' => $party['last_name'],
                    'extension_name' => $party['extension_name'],
                    'contact_number' => $party['contact_number'],
                    'address' => $party['address'],
                ]);

            }
        }


        // ========================================================
        // Save new attachments
        // ========================================================

        foreach ($this->newAttachments as $attachment) {

            $path = $attachment->store(
                'blotter-attachments',
                'public'
            );

            $blotter->attachments()->create([
                'file_path' => $path,
                'original_name' => $attachment->getClientOriginalName(),
                'mime_type' => $attachment->getMimeType(),
                'file_size' => $attachment->getSize(),
            ]);
        }


        session()->flash(
            'success',
            'Blotter record updated successfully.'
        );

        return $this->redirectRoute(
            'blotters.show',
            $blotter
        );
    }


    private function blotter(): BlotterRecord
    {
        return BlotterRecord::findOrFail(
            $this->blotterId
        );
    }


    public function render()
    {
        $residents = Resident::query()
            ->when(
                $this->residentSearch,
                function ($query) {

                    $terms = preg_split(
                        '/\s+/',
                        trim($this->residentSearch)
                    );

                    foreach ($terms as $term) {

                        $query->where(function ($query) use ($term) {

                            $query
                                ->where(
                                    'first_name',
                                    'like',
                                    "%{$term}%"
                                )
                                ->orWhere(
                                    'middle_name',
                                    'like',
                                    "%{$term}%"
                                )
                                ->orWhere(
                                    'last_name',
                                    'like',
                                    "%{$term}%"
                                )
                                ->orWhere(
                                    'resident_id',
                                    'like',
                                    "%{$term}%"
                                );

                        });
                    }
                }
            )
            ->limit(10)
            ->get();

        return $this->view([
            'residents' => $residents,
        ]);
    }
};
?>

<div class="space-y-6">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Edit Blotter Record
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                {{ $blotter_number }}
            </p>
        </div>

        <a
            href="{{ route('blotters.show', $blotterId) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Cancel
        </a>

    </div>


    <form wire:submit="save" class="space-y-6">

        {{-- =====================================================
             INCIDENT DETAILS
        ====================================================== --}}
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <div class="mb-5 flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                    <i class="fa-solid fa-file-circle-exclamation"></i>
                </div>

                <div>
                    <h2 class="font-semibold text-gray-900">
                        Incident Details
                    </h2>

                    <p class="text-sm text-gray-500">
                        Update the details of the incident.
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <label class="mb-1 block font-medium">
                        Incident Date
                    </label>

                    <input
                        type="date"
                        wire:model="incident_date"
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('incident_date')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label class="mb-1 block font-medium">
                        Incident Time
                    </label>

                    <input
                        type="time"
                        wire:model="incident_time"
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('incident_time')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label class="mb-1 block font-medium">
                        Incident Type
                    </label>

                    <input
                        type="text"
                        wire:model="incident_type"
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('incident_type')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div>
                    <label class="mb-1 block font-medium">
                        Location
                    </label>

                    <input
                        type="text"
                        wire:model="incident_location"
                        class="w-full rounded-lg border-gray-300"
                    >

                    @error('incident_location')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                <div class="md:col-span-2">

                    <label class="mb-1 block font-medium">
                        Description
                    </label>

                    <textarea
                        wire:model="incident_description"
                        rows="6"
                        class="w-full rounded-lg border-gray-300"
                    ></textarea>

                    @error('incident_description')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>
                    <label class="mb-1 block font-medium">
                        Status
                    </label>

                    <select
                        wire:model="status"
                        class="w-full rounded-lg border-gray-300"
                    >
                        <option value="Pending">Pending</option>
                        <option value="Under Investigation">
                            Under Investigation
                        </option>
                        <option value="Settled">Settled</option>
                        <option value="Referred">Referred</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>


                <div>
                    <label class="mb-1 block font-medium">
                        Remarks
                    </label>

                    <textarea
                        wire:model="remarks"
                        rows="1"
                        class="w-full rounded-lg border-gray-300"
                    ></textarea>
                </div>

            </div>

        </section>


        {{-- =====================================================
             PEOPLE INVOLVED
        ====================================================== --}}
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <div class="mb-5 flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                    <i class="fa-solid fa-users"></i>
                </div>

                <div>
                    <h2 class="font-semibold text-gray-900">
                        People Involved
                    </h2>

                    <p class="text-sm text-gray-500">
                        Add, edit, or remove people from this case.
                    </p>
                </div>

            </div>


            {{-- Existing parties --}}
            <div class="space-y-3">

                @forelse($parties as $index => $party)

                    <div
                        wire:key="party-{{ $party['id'] ?? 'new-' . $index }}"
                        class="rounded-xl border border-gray-200 p-4"
                    >

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                            <div>

                                <div class="flex flex-wrap items-center gap-2">

                                    <p class="font-semibold text-gray-900">
                                        {{ $party['last_name'] }},
                                        {{ $party['first_name'] }}
                                        {{ $party['middle_name'] }}
                                    </p>

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium">
                                        {{ $party['role'] }}
                                    </span>

                                    @if($party['resident_id'])

                                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Registered
                                        </span>

                                    @else

                                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                            Unregistered
                                        </span>

                                    @endif

                                </div>

                                <div class="mt-2 space-y-1 text-sm text-gray-500">

                                    <p>
                                        <i class="fa-solid fa-phone mr-1"></i>
                                        {{ $party['contact_number'] ?: 'No contact number' }}
                                    </p>

                                    <p>
                                        <i class="fa-solid fa-location-dot mr-1"></i>
                                        {{ $party['address'] ?: 'No address' }}
                                    </p>

                                </div>

                            </div>


                            <button
                                type="button"
                                wire:click="removeParty({{ $index }})"
                                class="inline-flex w-fit items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                            >
                                <i class="fa-solid fa-trash"></i>
                                Remove
                            </button>

                        </div>

                    </div>

                @empty

                    <p class="py-6 text-center text-sm text-gray-500">
                        No people involved.
                    </p>

                @endforelse

            </div>

            <div class="mt-6 border-t pt-6">
                <h2 class="font-semibold mb-4">
                    Add Person
                </h2>

                <div class="mb-8">
                    {{-- Role --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">
                            Role
                        </label>

                        <select
                            wire:model="partyRole"
                            class="w-full rounded-lg border-gray-300"
                        >
                            <option value="Complainant">Complainant</option>
                            <option value="Respondent">Respondent</option>
                            <option value="Witness">Witness</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">
                            Search Registered Resident
                        </label>

                        <input
                            type="text"
                            wire:key="resident-search-{{ $selectedResidentId ?? 'none' }}"
                            wire:model.live.debounce.300ms="residentSearch"
                            placeholder="Search name or resident ID..."
                            class="w-full rounded-lg border-gray-300"
                        >

                        @if(strlen($residentSearch) >= 2)

                            <div class="mt-2 border rounded-lg bg-white">

                                @forelse($residents as $resident)

                                    <button
                                        type="button"
                                        wire:click="selectResident({{ $resident->id }})"
                                        class="block w-full text-left px-4 py-3 hover:bg-gray-100"
                                    >
                                        <div class="font-medium">
                                            {{ $resident->last_name }},
                                            {{ $resident->first_name }}
                                            {{ $resident->middle_name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $resident->resident_id }}
                                        </div>
                                    </button>

                                @empty

                                    <div class="px-4 py-3 text-sm text-gray-500">
                                        No registered resident found.
                                        You may enter the person's information manually.
                                    </div>

                                @endforelse

                            </div>

                        @endif

                        @if($selectedResidentId)
                            <div class="flex items-start justify-between rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900 dark:bg-blue-950">

                                <div>
                                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                        Registered Resident
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $partyFirstName }}
                                        {{ $partyMiddleName }}
                                        {{ $partyLastName }}
                                        {{ $partyExtensionName }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fa-solid fa-location-dot mr-1"></i>
                                        {{ $partyAddress ?: 'No address recorded' }}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    wire:click="clearSelectedResident"
                                    class="ml-4 text-gray-500 hover:text-red-600"
                                    title="Remove selected resident"
                                >
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </button>

                            </div>
                        @endif
                    </div>


                    {{-- Person details --}}
                    <div
                        wire:key="party-details-{{ $partyFormKey }}-{{ $selectedResidentId ?? 'manual' }}"
                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                    >

                        <div>
                            <label>First Name</label>

                            <input
                                type="text"
                                wire:model="partyFirstName"
                                class="w-full rounded-lg border-gray-300"
                            >

                            @error('partyFirstName')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label>Middle Name</label>

                            <input
                                type="text"
                                wire:model="partyMiddleName"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>


                        <div>
                            <label>Last Name</label>

                            <input
                                type="text"
                                wire:model="partyLastName"
                                class="w-full rounded-lg border-gray-300"
                            >

                            @error('partyLastName')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label>Extension Name</label>

                            <input
                                type="text"
                                wire:model="partyExtensionName"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>


                        <div>
                            <label>Contact Number</label>

                            <input
                                type="text"
                                wire:model="partyContactNumber"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>


                        <div>
                            <label>Address</label>

                            <input
                                type="text"
                                wire:model="partyAddress"
                                class="w-full rounded-lg border-gray-300"
                            >
                        </div>

                    </div>


                    @if($selectedResidentId)

                        <p class="mt-2 text-sm text-green-600">
                            <i class="fa-solid fa-circle-check mr-1"></i>
                            Registered resident found. Information has been filled automatically.
                        </p>

                    @endif


                    <button
                        type="button"
                        wire:click="addParty"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg"
                    >
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Person
                    </button>

                </div>

                @error('parties') <p class="mb-4 text-red-500 text-sm">{{ $message }}</p> @enderror

                @if(count($parties))
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-left">
                            <thead class="border-b bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Contact</th>
                                    <th class="px-4 py-3">Address</th>
                                    <th class="px-2 text-center">Registered</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parties as $index => $party)
                                    <tr class="border-b last:border-b-0">
                                        <td class="px-4 py-3">
                                            {{ $party['first_name'] }} {{ $party['middle_name'] }} {{ $party['last_name'] }} {{ $party['extension_name'] }}
                                        </td>
                                        <td class="px-4 py-3">{{ $party['role'] }}</td>
                                        <td class="px-4 py-3">{{ $party['contact_number'] ?: 'N/A' }}</td>
                                        <td class="px-4 py-3">{{ $party['address'] ?: 'N/A' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($party['resident_id'])
                                                <i class="text-green-500 fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="text-red-500 fa-solid fa-circle-xmark"></i>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <button type="button" wire:click="removeParty({{ $index }})" class="text-red-600 hover:underline">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No people added yet.</p>
                @endif
            </div class="mt-6 border-t pt-6">

        </section>


        {{-- =====================================================
             ATTACHMENTS
        ====================================================== --}}
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">

            <div class="mb-5 flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                    <i class="fa-solid fa-paperclip"></i>
                </div>

                <div>
                    <h2 class="font-semibold text-gray-900">
                        Attachments
                    </h2>

                    <p class="text-sm text-gray-500">
                        Add or remove images related to this case.
                    </p>
                </div>

            </div>


            {{-- Existing attachments --}}
            @if(count($existingAttachments))

                <div>

                    <p class="mb-3 text-sm font-medium text-gray-700">
                        Existing Attachments
                    </p>

                    <div class="max-h-80 overflow-y-auto pr-2">

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">

                            @foreach($existingAttachments as $attachment)

                                <div
                                    wire:key="existing-attachment-{{ $attachment['id'] }}"
                                    class="relative overflow-hidden rounded-xl border border-gray-200"
                                >

                                    <div class="aspect-square bg-gray-100">

                                        <img
                                            src="{{ asset('storage/' . $attachment['file_path']) }}"
                                            alt="{{ $attachment['original_name'] }}"
                                            class="h-full w-full object-cover"
                                        >

                                    </div>

                                    <div class="p-2">

                                        <p class="truncate text-xs text-gray-600">
                                            {{ $attachment['original_name'] }}
                                        </p>

                                    </div>

                                    <button
                                        type="button"
                                        wire:click="removeAttachment({{ $attachment['id'] }})"
                                        wire:confirm="Remove this attachment? This cannot be undone."
                                        class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white shadow hover:bg-red-700"
                                    >
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            @endif


            {{-- New attachments --}}
            <div class="mt-6">

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Add New Attachments
                </label>

                <input
                    type="file"
                    wire:model="newAttachments"
                    multiple
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white hover:file:bg-blue-700"
                >

                @error('newAttachments')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

                @error('newAttachments.*')
                    <p class="mt-1 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror


                <div
                    wire:loading
                    wire:target="newAttachments"
                    class="mt-2 text-sm text-blue-600"
                >
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                    Uploading images...
                </div>


                @if(count($newAttachments))

                    <div class="mt-4 max-h-80 overflow-y-auto pr-2">

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">

                            @foreach($newAttachments as $index => $attachment)

                                <div
                                    wire:key="new-attachment-{{ $index }}"
                                    class="relative overflow-hidden rounded-xl border border-gray-200"
                                >

                                    <div class="aspect-square bg-gray-100">

                                        <img
                                            src="{{ $attachment->temporaryUrl() }}"
                                            class="h-full w-full object-cover"
                                            alt="New attachment"
                                        >

                                    </div>

                                    <button
                                        type="button"
                                        wire:click="removeNewAttachment({{ $index }})"
                                        class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white shadow hover:bg-red-700"
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif

            </div>

        </section>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}
        <div class="flex items-center justify-between">

            <a
                href="{{ route('blotters.show', $blotterId) }}"
                class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200"
            >
                Cancel
            </a>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
            >

                <span wire:loading.remove wire:target="save">
                    <i class="fa-solid fa-save"></i>
                    Save Changes
                </span>

                <span wire:loading wire:target="save">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Saving...
                </span>

            </button>

        </div>

    </form>

</div>