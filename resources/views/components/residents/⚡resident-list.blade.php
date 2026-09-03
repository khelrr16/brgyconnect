<?php

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new class extends Component
{
    use withFileUploads;
    use WithPagination;

    public $csvFile;

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

    public function importCsv(): void
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $this->csvFile->getRealPath();

        $handle = fopen($path, 'r');

        if ($handle === false) {
            $this->addError('csvFile', 'Unable to open the CSV file.');

            return;
        }

        $headers = fgetcsv($handle);
        $headers = $headers === false
            ? []
            : array_map(fn ($header) => trim((string) $header), $headers);

        if ($headers === []) {
            fclose($handle);
            $this->addError('csvFile', 'The CSV file is empty or has no header row.');

            return;
        }

        $rowNumber = 1;
        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) {
                    continue;
                }

                if (count($row) !== count($headers)) {
                    $errors[] = "Row {$rowNumber}: the number of columns does not match the header.";
                    $skipped++;
                    continue;
                }

                $data = array_combine($headers, $row);
                $householdReference = trim((string) ($data['Household ID'] ?? ''));
                $householdId = ctype_digit($householdReference)
                    ? (int) $householdReference
                    : Household::where('household_id', $householdReference)->value('id');

                $resident = [
                    'household_id' => $householdId,
                    'first_name' => trim((string) ($data['First Name'] ?? '')),
                    'middle_name' => trim((string) ($data['Middle Name (Optional)'] ?? '')) ?: null,
                    'last_name' => trim((string) ($data['Last Name'] ?? '')),
                    'extension_name' => trim((string) ($data['Extension Name (Optional)'] ?? '')) ?: null,
                    'birth_date' => trim((string) ($data['Date of Birth'] ?? '')),
                    'sex' => trim((string) ($data['Sex'] ?? '')),
                    'civil_status' => trim((string) ($data['Civil Status'] ?? '')),
                    'citizenship' => trim((string) ($data['Citizenship'] ?? '')),
                    'place_of_birth' => trim((string) ($data['Place of Birth'] ?? '')),
                    'contact_number' => trim((string) ($data['Contact Number'] ?? '')) ?: null,
                    'registered_voter' => trim((string) ($data['Registered Voter?'] ?? '')),
                    'house_ownership' => trim((string) ($data['House Ownership'] ?? '')),
                    'relationship_to_head' => trim((string) ($data['Your relationship to the Head of the Family'] ?? '')),
                    'residence_since' => trim((string) ($data['Residence Since'] ?? '')),
                    'educational_attainment' => trim((string) ($data['Educational Attainment'] ?? '')),
                    'employment_status' => trim((string) ($data['Employment Status'] ?? '')),
                    'religion' => trim((string) ($data['Religion'] ?? '')) ?: null,
                    'occupation' => trim((string) ($data['Occupation'] ?? '')) ?: null,
                ];

                $validation = Validator::make($resident, [
                    'household_id' => ['required', 'integer', 'exists:households,id'],
                    'first_name' => ['required', 'string', 'max:255'],
                    'middle_name' => ['nullable', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                    'extension_name' => ['nullable', 'string', 'max:50'],
                    'birth_date' => ['required', 'date'],
                    'sex' => ['required', 'in:Male,Female'],
                    'civil_status' => ['required', 'in:Single,Married,Widow/Widower,Divorced,Legally Separated'],
                    'citizenship' => ['required', 'string', 'max:100'],
                    'place_of_birth' => ['required', 'string', 'max:255'],
                    'contact_number' => ['nullable', 'string', 'max:20'],
                    'registered_voter' => ['required', 'in:Yes - within,Yes - elsewhere,No'],
                    'house_ownership' => ['required', 'string', 'max:100'],
                    'relationship_to_head' => ['required', 'string', 'max:100'],
                    'residence_since' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
                    'educational_attainment' => ['required', 'string', 'max:255'],
                    'employment_status' => ['required', 'string', 'max:100'],
                    'religion' => ['nullable', 'string', 'max:100'],
                    'occupation' => ['nullable', 'string', 'max:255'],
                ]);

                if ($validation->fails()) {
                    $errors[] = "Row {$rowNumber}: " . $validation->errors()->first();
                    $skipped++;
                    continue;
                }

                $resident['resident_id'] = $this->nextResidentId();
                Resident::create($resident);
                $imported++;
            }

            fclose($handle);
            DB::commit();
        } catch (\Throwable $exception) {
            fclose($handle);
            DB::rollBack();
            $this->addError('csvFile', 'The import could not be completed. No residents were saved.');

            report($exception);

            return;
        }

        $this->reset('csvFile');

        $message = "CSV import finished: {$imported} resident(s) imported";

        if ($skipped > 0) {
            $message .= ", {$skipped} row(s) skipped.";
            session()->flash('csvImportErrors', $errors);
        } else {
            $message .= '.';
        }

        session()->flash('status', $message);

        $this->resetPage();
    }

    protected function nextResidentId(): string
    {
        $number = Resident::query()->count() + 1;

        do {
            $residentId = 'RES-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
            $number++;
        } while (Resident::where('resident_id', $residentId)->exists());

        return $residentId;
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
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if (session('csvImportErrors'))
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
            <p class="font-semibold">Some rows were not imported:</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach (session('csvImportErrors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by name (First Name, Middle Name, Last Name) or resident ID..."
            class="flex-1 border-gray-300 rounded-lg">
            
            <div class="flex items-center gap-2">

                <label
                    class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                >
                    Upload CSV

                    <input
                        type="file"
                        wire:model="csvFile"
                        accept=".csv,.txt"
                        class="hidden"
                    >
                </label>

                <span wire:loading wire:target="csvFile" class="text-sm text-gray-500">
                    Uploading file...
                </span>

                @if ($csvFile)
                    <button
                        type="button"
                        wire:click="importCsv"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="importCsv">
                            Import
                        </span>

                        <span wire:loading wire:target="importCsv">
                            Importing...
                        </span>
                    </button>
                @endif
            </div>        

            @error('csvFile')
                <div class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </div>
            @enderror
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
                                href="{{ route('admin.residents.show', $resident) }}"
                                target="_blank"
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