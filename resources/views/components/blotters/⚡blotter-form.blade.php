<?php

use App\Models\BlotterRecord;
use App\Models\Resident;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $incident_date = '';
    public string $incident_time = '';
    public string $incident_type = '';
    public string $incident_location = '';
    public string $incident_description = '';
    public string $action_taken = '';
    public string $status = 'Pending';
    public string $remarks = '';

    /*
     * Temporary data for the person currently being added.
     */
    public string $partyRole = 'Complainant';

    public ?int $selectedResidentId = null;

    public string $residentSearch = '';

    public string $partyFirstName = '';
    public string $partyMiddleName = '';
    public string $partyLastName = '';
    public string $partyExtensionName = '';
    public string $partyContactNumber = '';
    public string $partyAddress = '';

    /*
     * Stores people before the blotter is saved.
     */
    public array $parties = [];

    public int $partyFormKey = 0;

    /*
     * Temporary attachment.
     */
    public array $attachments = [];

    public function mount(): void
    {
        $this->incident_date = now()->format('Y-m-d');
    }

    public function selectResident(int $residentId): void
    {
        $this->clearSelectedResident();
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

    public function getResidentAddress(string $residentId): void
    {
        if (! $this->selectedResidentId) {
            $this->partyAddress = '';

            return;
        }

        $resident = Resident::findOrFail($this->selectedResidentId);

        $this->partyAddress = $resident->address ?? '';
    }

    public function clearSelectedResident(): void
    {
        $this->selectedResidentId = null;
        $this->residentSearch = '';

        $this->partyFirstName = '';
        $this->partyMiddleName = '';
        $this->partyLastName = '';
        $this->partyExtensionName = '';
        $this->partyContactNumber = '';
        $this->partyAddress = '';
    }

    public function addParty(): void
    {
        $this->resetErrorBag();

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
            'resident_id' => $this->selectedResidentId
                ? $this->selectedResidentId
                : null,

            'role' => $this->partyRole,

            'first_name' => $this->partyFirstName,
            'middle_name' => $this->partyMiddleName,
            'last_name' => $this->partyLastName,
            'extension_name' => $this->partyExtensionName,

            'contact_number' => $this->partyContactNumber,
            'address' => $this->partyAddress,
        ];

        $this->clearSelectedResident();

        $this->partyFormKey++;

        $this->partyRole = 'Complainant';
    }

    public function removeParty(int $index): void
    {
        unset($this->parties[$index]);

        $this->parties = array_values($this->parties);
    }

    public function removeAttachment(int $index): void
    {
        unset($this->attachments[$index]);

        $this->attachments = array_values($this->attachments);
    }

    public function save()
    {
        $validated = $this->validate([
            'incident_date' => ['required', 'date'],

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

            'attachments' => [
                'nullable',
                'array',
                'max:10',
            ],

            'attachments.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5 MB each
            ],

        ]);

        /*
         * A blotter should have at least one party.
         */
        if (count($this->parties) === 0) {
            $this->addError(
                'parties',
                'Add at least one person involved in the incident.'
            );

            return;
        }

        /*
         * Require a complainant.
         */
        $hasComplainant = collect($this->parties)
            ->contains('role', 'Complainant');

        if (! $hasComplainant) {
            $this->addError(
                'parties',
                'At least one complainant is required.'
            );

            return;
        }

        $blotter = BlotterRecord::create([
            ...$validated,

            'blotter_number' => 'TEMP',

            'status' => 'Pending',

            'created_by' => auth()->guard()->id(),
        ]);

        $blotter->update([
            'blotter_number' => 'BLT-' . str_pad(
                $blotter->id,
                6,
                '0',
                STR_PAD_LEFT
            ),
        ]);

        foreach ($this->parties as $party) {
            $blotter->parties()->create($party);
        }

        foreach ($this->attachments as $attachment) {

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
            'Blotter record created successfully.'
        );

        return $this->redirectRoute(
            'blotters.show',
            $blotter
        );
    }

    public function render()
    {
        $residents = Resident::query()
            ->when($this->residentSearch, function ($query) {

                $terms = preg_split(
                    '/\s+/',
                    trim($this->residentSearch)
                );

                foreach ($terms as $term) {

                    $query->where(function ($query) use ($term) {

                        $query
                            ->where('first_name', 'like', "%{$term}%")
                            ->orWhere('middle_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%")
                            ->orWhere('resident_id', 'like', "%{$term}%");

                    });
                }
            })
            ->limit(10)
            ->get();

        return $this->view([
            'residents' => $residents,
        ]);
    }
};
?>

<div>
    {{-- Loading Overlay --}}
    <div
        wire:loading.flex
        wire:target="save"
        class="fixed inset-0 z-[100] items-center justify-center bg-black/50 backdrop-blur-sm">

        <div class="flex flex-col items-center rounded-xl bg-white px-8 py-6 shadow-xl">

            <i class="fa-solid fa-spinner fa-spin text-4xl text-blue-600"></i>

            <p class="mt-4 font-semibold text-gray-800">
                Saving blotter record...
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Please don't close this page.
            </p>

        </div>

    </div>

    <div class="mb-8">
        
        <form wire:submit="save" class="space-y-8">
            <section>
                <h2 class="border-b-2 border-gray text-lg font-semibold mb-4">
                    Incident Details
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="incident_date" class="block font-medium mb-1">Incident Date</label>
                        <input id="incident_date" type="date" wire:model="incident_date" class="w-full rounded-lg border-gray-300">
                        @error('incident_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="incident_time" class="block font-medium mb-1">Incident Time</label>
                        <input id="incident_time" type="time" wire:model="incident_time" class="w-full rounded-lg border-gray-300">
                        @error('incident_time') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="incident_type" class="block font-medium mb-1">Incident Type</label>
                        <input id="incident_type" type="text" wire:model="incident_type" class="w-full rounded-lg border-gray-300">
                        @error('incident_type') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="incident_location" class="block font-medium mb-1">Location</label>
                        <input id="incident_location" type="text" wire:model="incident_location" class="w-full rounded-lg border-gray-300">
                        @error('incident_location') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="incident_description" class="block font-medium mb-1">Description</label>
                        <textarea id="incident_description" wire:model="incident_description" rows="5" class="w-full rounded-lg border-gray-300"></textarea>
                        @error('incident_description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section>
                <h2 class="border-b-2 border-gray-300 text-lg font-semibold mb-4">
                    People Involved
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
            </section>

            <section>
                <h2 class="border-b-2 border-gray-300 text-lg font-semibold mb-4">
                    Case Attachments
                </h2>

                <p class="text-sm text-gray-500 mb-4">
                    Upload photos or other image evidence related to this incident.
                </p>

                <input
                    type="file"
                    wire:model="attachments"
                    multiple
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-gray-600
                        file:mr-4 file:rounded-lg file:border-0
                        file:bg-blue-600 file:px-4 file:py-2
                        file:text-white hover:file:bg-blue-700"
                >

                @error('attachments')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

                @error('attachments.*')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror


                {{-- Uploading --}}
                <div wire:loading wire:target="attachments" class="mt-3">
                    <p class="text-sm text-blue-600">
                        Uploading...
                    </p>
                </div>


                {{-- Preview area --}}
                @if(count($attachments))

                    <div class="mt-4">

                        <div class="max-h-80 overflow-y-auto rounded-lg border p-4">

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">

                                @foreach($attachments as $index => $attachment)

                                    <div class="relative">

                                        <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">

                                            <img
                                                src="{{ $attachment->temporaryUrl() }}"
                                                class="h-full w-full object-cover"
                                                alt="Attachment preview"
                                            >

                                        </div>

                                        <button
                                            type="button"
                                            wire:click="removeAttachment({{ $index }})"
                                            class="absolute right-1 top-1 flex h-7 w-7 items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700"
                                            title="Remove"
                                        >
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ count($attachments) }} attachment(s) selected.
                        </p>

                    </div>

                @endif

            
            </section>

            <div class="flex justify-end">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">

                    <span wire:loading.remove wire:target="save">
                        Save Blotter Record
                    </span>

                    <span wire:loading wire:target="save">
                        Saving...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>