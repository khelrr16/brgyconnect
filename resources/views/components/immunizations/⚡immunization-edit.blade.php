<?php

use App\Models\Immunization;
use App\Models\NutritionalAssessment;
use App\Models\VaccineDose;
use Livewire\Component;
use Illuminate\Validation\Rule;

new class extends Component {
    public Immunization $immunization;

    public array $dates = [];

    public array $infantNutrition = [];

    public array $vitaminMnp = [];

    public array $completion = [];

    public array $assessments = [];

    public string $activeAgeTab = 'newborn';

    public bool $showAssessmentForm = false;

    public string $assessmentStage = 'newborn';

    public int $assessmentAgeValue = 0;

    public string $assessmentAgeUnit = 'weeks';

    public ?string $assessmentLength = null;

    public ?string $assessmentWeight = null;

    public string $assessmentStatus = 'normal';

    public string $assessmentDate = '';

    public function mount(Immunization $immunization): void
    {
        $this->immunization = $immunization->load(['vaccineDoses', 'nutritionalAssessments']);
        $this->assessmentDate = now()->format('Y-m-d');

        $this->infantNutrition = [
            'breastfeeding_initiated' => (bool) $this->immunization->breastfeeding_initiated,
            'breastfeeding_initiated_date' => $this->immunization->breastfeeding_initiated_date?->format('Y-m-d'),
            'exclusive_bf_5m29d' => (bool) $this->immunization->exclusive_bf_5m29d,
            'exclusive_bf_date' => $this->immunization->exclusive_bf_date?->format('Y-m-d'),
            'complementary_feeding_status' => $this->immunization->complementary_feeding_status,
        ];

        $this->vitaminMnp = [
            'vitamin_a_date' => $this->immunization->vitamin_a_date?->format('Y-m-d'),
            'mnp_90_sachets_date' => $this->immunization->mnp_90_sachets_date?->format('Y-m-d'),
            'mnp_completed_date' => $this->immunization->mnp_completed_date?->format('Y-m-d'),
        ];

        $this->completion = [
            'fic_date' => $this->immunization->fic_date?->format('Y-m-d'),
            'cic_date' => $this->immunization->cic_date?->format('Y-m-d'),
        ];

        $this->assessments = $this->immunization->nutritionalAssessments
            ->mapWithKeys(fn (NutritionalAssessment $assessment) => [
                $assessment->id => [
                    'stage' => $assessment->stage,
                    'age_value' => $assessment->age_value,
                    'age_unit' => $assessment->age_unit,
                    'length_cm' => $assessment->length_cm,
                    'weight_kg' => $assessment->weight_kg,
                    'status' => $assessment->status,
                    'assessment_date' => $assessment->assessment_date?->format('Y-m-d'),
                ],
            ])->all();

        foreach ($this->schedule() as $group) {
            foreach ($group['vaccines'] as $vaccine => $doseCount) {
                for ($dose = 1; $dose <= $doseCount; $dose++) {
                    $this->dates[$vaccine][$dose] = $this->immunization->vaccineDoses
                        ->first(fn (VaccineDose $record) =>
                            $record->vaccine === $vaccine && $record->dose_number === $dose
                        )?->date_given?->format('Y-m-d');
                }
            }
        }
    }

    public function save()
    {
        $this->validate([
            'dates.*.*' => ['nullable', 'date', 'before_or_equal:today'],
            'infantNutrition.breastfeeding_initiated' => ['boolean'],
            'infantNutrition.breastfeeding_initiated_date' => ['nullable', 'date', 'before_or_equal:today'],
            'infantNutrition.exclusive_bf_5m29d' => ['boolean'],
            'infantNutrition.exclusive_bf_date' => ['nullable', 'date', 'before_or_equal:today'],
            'infantNutrition.complementary_feeding_status' => ['nullable', 'string', 'max:255'],
            'vitaminMnp.*' => ['nullable', 'date', 'before_or_equal:today'],
            'completion.*' => ['nullable', 'date', 'before_or_equal:today'],
            'assessments.*.age_value' => ['required', 'integer', 'min:0', 'max:65535'],
            'assessments.*.age_unit' => ['required', Rule::in(['weeks', 'months'])],
            'assessments.*.length_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'assessments.*.weight_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'assessments.*.status' => ['required', 'string', 'max:255'],
            'assessments.*.assessment_date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $this->immunization->update([
            ...$this->infantNutrition,
            ...$this->vitaminMnp,
            ...$this->completion,
        ]);

        foreach ($this->dates as $vaccine => $doses) {
            foreach ($doses as $doseNumber => $dateGiven) {
                VaccineDose::updateOrCreate(
                    [
                        'immunization_id' => $this->immunization->id,
                        'vaccine' => $vaccine,
                        'dose_number' => $doseNumber,
                    ],
                    ['date_given' => $dateGiven ?: null],
                );
            }
        }

        foreach ($this->assessments as $assessmentId => $assessment) {
            NutritionalAssessment::where('immunization_id', $this->immunization->id)
                ->whereKey($assessmentId)
                ->update($assessment);
        }

        session()->flash('success', 'Immunization dates updated.');

        return redirect()->route('admin.immunizations.show', $this->immunization);
    }

    public function deleteAssessment(int $assessmentId): void
    {
        NutritionalAssessment::where('immunization_id', $this->immunization->id)
            ->whereKey($assessmentId)
            ->delete();

        unset($this->assessments[$assessmentId]);
    }

    public function addAssessment(): void
    {
        $this->validate([
            'assessmentStage' => ['required', Rule::in(array_keys(NutritionalAssessment::STATUS_OPTIONS))],
            'assessmentAgeValue' => ['required', 'integer', 'min:0', 'max:65535'],
            'assessmentAgeUnit' => ['required', Rule::in(['weeks', 'months'])],
            'assessmentLength' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'assessmentWeight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'assessmentStatus' => ['required', Rule::in($this->assessmentStatusOptions())],
            'assessmentDate' => ['required', 'date', 'before_or_equal:today'],
        ]);

        NutritionalAssessment::create([
            'immunization_id' => $this->immunization->id,
            'stage' => $this->assessmentStage,
            'age_value' => $this->assessmentAgeValue,
            'age_unit' => $this->assessmentAgeUnit,
            'length_cm' => $this->assessmentLength,
            'weight_kg' => $this->assessmentWeight,
            'status' => $this->assessmentStatus,
            'assessment_date' => $this->assessmentDate,
        ]);

        $this->resetAssessmentForm();
        session()->flash('success', 'Nutritional assessment added.');
    }

    public function assessmentStatusOptions(): array
    {
        return NutritionalAssessment::STATUS_OPTIONS[$this->assessmentStage] ?? [];
    }

    public function updatedAssessmentStage(): void
    {
        $options = $this->assessmentStatusOptions();

        if (! in_array($this->assessmentStatus, $options, true)) {
            $this->assessmentStatus = $options[0] ?? 'unknown';
        }
    }

    private function resetAssessmentForm(): void
    {
        $this->showAssessmentForm = false;
        $this->assessmentStage = 'newborn';
        $this->assessmentAgeValue = 0;
        $this->assessmentAgeUnit = 'weeks';
        $this->assessmentLength = null;
        $this->assessmentWeight = null;
        $this->assessmentStatus = 'normal';
        $this->assessmentDate = now()->format('Y-m-d');
        $this->resetValidation();
    }

    private function schedule(): array
    {
        return [
            'newborn' => [
                'label' => 'Newborn',
                'icon' => 'fa-baby',
                'color' => 'blue',
                'vaccines' => [
                    'BCG' => 1,
                    'Hepa B-BD' => 1,
                ],
            ],
            '1-3-months' => [
                'label' => '1-3 Months',
                'icon' => 'fa-calendar-days',
                'color' => 'indigo',
                'vaccines' => [
                    'DPT-HiB-HepB' => 3,
                    'OPV' => 3,
                    'PCV' => 3,
                    'IPV' => 1,
                ],
            ],
            '6-11-months' => [
                'label' => '6-11 Months',
                'icon' => 'fa-baby-carriage',
                'color' => 'pink',
                'vaccines' => [
                    'MMR' => 1,
                    'IPV' => 1,
                ],
            ],
            '12-months' => [
                'label' => '12 Months',
                'icon' => 'fa-child',
                'color' => 'green',
                'vaccines' => [
                    'MMR' => 2,
                ],
            ],
        ];
    }
}
?>

<div x-data="{ openSection: 'immunization', activeAgeTab: @entangle('activeAgeTab') }" class="max-w-6xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">
        <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $immunization->full_name }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Born {{ $immunization->birthday?->format('F d, Y') ?? '—' }}
                </p>
            </div>
            <a
                href="{{ route('admin.immunizations.show', $immunization) }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Cancel
            </a>
        </div>
    </div>

    <form wire:submit="save" class="space-y-3">
        <section class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <button type="button" @click="openSection = openSection === 'immunization' ? null : 'immunization'" class="w-full flex items-center justify-between px-5 py-4 bg-white hover:bg-gray-50 transition">
                <span class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-syringe"></i></span>
                    <span class="text-left"><span class="block font-semibold text-gray-900">Immunization</span><span class="block text-xs text-gray-500">Vaccine dates by age group</span></span>
                </span>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="{ 'rotate-180': openSection === 'immunization' }"></i>
            </button>

            <div x-show="openSection === 'immunization'" x-collapse x-cloak class="border-t border-gray-200">
                <div class="p-5 sm:p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-1 p-1 mb-6 bg-gray-100 rounded-lg" role="tablist" aria-label="Edit immunization schedule">
                @foreach($this->schedule() as $tabKey => $group)
                    <button
                        type="button"
                        role="tab"
                        aria-controls="edit-panel-{{ $tabKey }}"
                        :aria-selected="activeAgeTab === '{{ $tabKey }}'"
                        @click="activeAgeTab = '{{ $tabKey }}'"
                        :class="activeAgeTab === '{{ $tabKey }}' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-3 py-2 text-xs sm:text-sm font-semibold rounded-md transition"
                    >
                        {{ $group['label'] }}
                    </button>
                @endforeach
            </div>

            @foreach($this->schedule() as $tabKey => $group)
                <section
                    x-show="activeAgeTab === '{{ $tabKey }}'"
                    x-cloak
                    id="edit-panel-{{ $tabKey }}"
                    role="tabpanel"
                    class="space-y-4"
                >
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fa-solid {{ $group['icon'] }}"></i>
                        </span>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $group['label'] }}</h3>
                            <p class="text-xs text-gray-500">Enter the date each dose was given.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Vaccine</th>
                                    @for($dose = 1; $dose <= max($group['vaccines']); $dose++)
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Dose {{ $dose }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($group['vaccines'] as $vaccine => $doseCount)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">{{ $vaccine }}</td>
                                        @for($dose = 1; $dose <= max($group['vaccines']); $dose++)
                                            <td class="px-4 py-3">
                                                @if($dose <= $doseCount)
                                                    <input
                                                        type="date"
                                                        wire:model="dates.{{ $vaccine }}.{{ $dose }}"
                                                        max="{{ now()->format('Y-m-d') }}"
                                                        class="w-full min-w-32 rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    >
                                                    @error("dates.$vaccine.$dose")
                                                        <span class="text-xs text-red-600">{{ $message }}</span>
                                                    @enderror
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endforeach

                </div>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <button type="button" @click="openSection = openSection === 'nutrition' ? null : 'nutrition'" class="w-full flex items-center justify-between px-5 py-4 bg-white hover:bg-gray-50 transition">
                <span class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><i class="fa-solid fa-weight-scale"></i></span>
                    <span class="text-left"><span class="block font-semibold text-gray-900">Nutritional Assessments</span><span class="block text-xs text-gray-500">Growth and nutritional status records</span></span>
                </span>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="{ 'rotate-180': openSection === 'nutrition' }"></i>
            </button>

            <div x-show="openSection === 'nutrition'" x-collapse x-cloak class="border-t border-gray-200">
                <div class="p-5 sm:p-6">
                    @if(count($assessments))
                        <div class="space-y-4 mb-6">
                            @foreach($assessments as $assessmentId => $assessment)
                                <div wire:key="assessment-{{ $assessmentId }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3 items-end border border-gray-200 rounded-xl p-4">
                                    <div><label class="block text-xs font-medium text-gray-500 mb-1">Stage</label><input type="text" wire:model="assessments.{{ $assessmentId }}.stage" class="w-full rounded-md border-gray-300 text-sm"></div>
                                    <div><label class="block text-xs font-medium text-gray-500 mb-1">Age</label><input type="number" min="0" wire:model="assessments.{{ $assessmentId }}.age_value" class="w-full rounded-md border-gray-300 text-sm"></div>
                                    <div><label class="block text-xs font-medium text-gray-500 mb-1">Unit</label><select wire:model="assessments.{{ $assessmentId }}.age_unit" class="w-full rounded-md border-gray-300 text-sm"><option value="weeks">Weeks</option><option value="months">Months</option></select></div>
                                    <div><label class="block text-xs font-medium text-gray-500 mb-1">Length (cm)</label><input type="number" step="0.01" min="0" wire:model="assessments.{{ $assessmentId }}.length_cm" class="w-full rounded-md border-gray-300 text-sm"></div>
                                    <div><label class="block text-xs font-medium text-gray-500 mb-1">Weight (kg)</label><input type="number" step="0.01" min="0" wire:model="assessments.{{ $assessmentId }}.weight_kg" class="w-full rounded-md border-gray-300 text-sm"></div>
                                    <div><label class="block text-xs font-medium text-gray-500 mb-1">Status</label><input type="text" wire:model="assessments.{{ $assessmentId }}.status" class="w-full rounded-md border-gray-300 text-sm"></div>
                                    <div><label class="block text-xs font-medium text-gray-500 mb-1">Date</label><input type="date" max="{{ now()->format('Y-m-d') }}" wire:model="assessments.{{ $assessmentId }}.assessment_date" class="w-full rounded-md border-gray-300 text-sm"></div>
                                    <button type="button" wire:click="deleteAssessment({{ $assessmentId }})" class="text-left text-xs font-semibold text-red-600 hover:text-red-800">Remove record</button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mb-6 text-sm text-gray-400">No nutritional assessments recorded yet.</p>
                    @endif

            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">Nutritional assessments</h3>
                        <p class="text-xs text-gray-500">Record growth measurements and nutritional status.</p>
                    </div>
                    <button
                        type="button"
                        wire:click="$set('showAssessmentForm', true)"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-orange-300 text-orange-700 text-sm font-semibold hover:bg-orange-50 transition"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Add assessment
                    </button>
                </div>

                @if($showAssessmentForm)
                    <div class="mt-4 rounded-xl border border-orange-200 bg-orange-50/40 p-4 sm:p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stage</label>
                                <select wire:model.live="assessmentStage" class="w-full rounded-md border-gray-300 text-sm">
                                    @foreach(array_keys(\App\Models\NutritionalAssessment::STATUS_OPTIONS) as $stage)
                                        <option value="{{ $stage }}">{{ str_replace('_', ' ', ucfirst($stage)) }}</option>
                                    @endforeach
                                </select>
                                @error('assessmentStage') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                                <div class="flex gap-2">
                                    <input type="number" min="0" wire:model="assessmentAgeValue" class="w-full rounded-md border-gray-300 text-sm">
                                    <select wire:model="assessmentAgeUnit" class="rounded-md border-gray-300 text-sm">
                                        <option value="weeks">Weeks</option>
                                        <option value="months">Months</option>
                                    </select>
                                </div>
                                @error('assessmentAgeValue') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Length (cm)</label>
                                <input type="number" min="0" step="0.01" wire:model="assessmentLength" class="w-full rounded-md border-gray-300 text-sm">
                                @error('assessmentLength') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                                <input type="number" min="0" step="0.01" wire:model="assessmentWeight" class="w-full rounded-md border-gray-300 text-sm">
                                @error('assessmentWeight') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select wire:model="assessmentStatus" class="w-full rounded-md border-gray-300 text-sm">
                                    @foreach($this->assessmentStatusOptions() as $status)
                                        <option value="{{ $status }}">{{ strtoupper($status) }}</option>
                                    @endforeach
                                </select>
                                @error('assessmentStatus') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assessment date</label>
                                <input type="date" max="{{ now()->format('Y-m-d') }}" wire:model="assessmentDate" class="w-full rounded-md border-gray-300 text-sm">
                                @error('assessmentDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" wire:click="$set('showAssessmentForm', false)" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900">Cancel</button>
                            <button type="button" wire:click="addAssessment" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-600 text-white text-sm font-semibold hover:bg-orange-700 disabled:opacity-60 transition">
                                <i class="fa-solid fa-plus"></i>
                                Add record
                            </button>
                        </div>
                    </div>
                @endif
            </div>
                </div>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <button type="button" @click="openSection = openSection === 'infantNutrition' ? null : 'infantNutrition'" class="w-full flex items-center justify-between px-5 py-4 bg-white hover:bg-gray-50 transition">
                <span class="flex items-center gap-3"><span class="w-9 h-9 rounded-lg bg-green-100 text-green-600 flex items-center justify-center"><i class="fa-solid fa-bowl-food"></i></span><span class="text-left"><span class="block font-semibold text-gray-900">Infant Nutrition</span><span class="block text-xs text-gray-500">Breastfeeding and complementary feeding</span></span></span>
                <i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="{ 'rotate-180': openSection === 'infantNutrition' }"></i>
            </button>
            <div x-show="openSection === 'infantNutrition'" x-collapse x-cloak class="border-t border-gray-200 p-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700"><input type="checkbox" wire:model="infantNutrition.breastfeeding_initiated" class="rounded border-gray-300 text-blue-600"> Breastfeeding initiated after birth</label>
                    <input type="date" max="{{ now()->format('Y-m-d') }}" wire:model="infantNutrition.breastfeeding_initiated_date" class="rounded-md border-gray-300 text-sm" aria-label="Breastfeeding initiated date">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700"><input type="checkbox" wire:model="infantNutrition.exclusive_bf_5m29d" class="rounded border-gray-300 text-blue-600"> Exclusive breastfeeding up to 5 months and 29 days</label>
                    <input type="date" max="{{ now()->format('Y-m-d') }}" wire:model="infantNutrition.exclusive_bf_date" class="rounded-md border-gray-300 text-sm" aria-label="Exclusive breastfeeding date">
                    <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Complementary feeding at 6 months</label><textarea wire:model="infantNutrition.complementary_feeding_status" rows="2" class="w-full rounded-md border-gray-300 text-sm"></textarea></div>
                </div>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <button type="button" @click="openSection = openSection === 'vitaminMnp' ? null : 'vitaminMnp'" class="w-full flex items-center justify-between px-5 py-4 bg-white hover:bg-gray-50 transition"><span class="flex items-center gap-3"><span class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i class="fa-solid fa-pills"></i></span><span class="text-left"><span class="block font-semibold text-gray-900">Vitamin A and MNP</span><span class="block text-xs text-gray-500">Supplementation and micronutrient records</span></span></span><i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="{ 'rotate-180': openSection === 'vitaminMnp' }"></i></button>
            <div x-show="openSection === 'vitaminMnp'" x-collapse x-cloak class="border-t border-gray-200 p-5 sm:p-6"><div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach(['vitamin_a_date' => 'Vitamin A date', 'mnp_90_sachets_date' => 'MNP 90 sachets date', 'mnp_completed_date' => 'MNP completed date'] as $field => $label)<div><label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label><input type="date" max="{{ now()->format('Y-m-d') }}" wire:model="vitaminMnp.{{ $field }}" class="w-full rounded-md border-gray-300 text-sm"></div>@endforeach
            </div></div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <button type="button" @click="openSection = openSection === 'ficCic' ? null : 'ficCic'" class="w-full flex items-center justify-between px-5 py-4 bg-white hover:bg-gray-50 transition"><span class="flex items-center gap-3"><span class="w-9 h-9 rounded-lg bg-green-100 text-green-600 flex items-center justify-center"><i class="fa-solid fa-shield-heart"></i></span><span class="text-left"><span class="block font-semibold text-gray-900">Immunization Completion</span><span class="block text-xs text-gray-500">Fully and completely immunized dates</span></span></span><i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="{ 'rotate-180': openSection === 'ficCic' }"></i></button>
            <div x-show="openSection === 'ficCic'" x-collapse x-cloak class="border-t border-gray-200 p-5 sm:p-6"><div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach(['fic_date' => 'Fully Immunized Child (FIC) date', 'cic_date' => 'Completely Immunized Child (CIC) date'] as $field => $label)<div><label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label><input type="date" max="{{ now()->format('Y-m-d') }}" wire:model="completion.{{ $field }}" class="w-full rounded-md border-gray-300 text-sm"></div>@endforeach
            </div></div>
        </section>

        <div class="bg-white border border-gray-200 rounded-xl px-5 sm:px-6 py-4 flex justify-end">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 disabled:opacity-60 transition"
            >
                <i class="fa-solid fa-floppy-disk"></i>
                <span wire:loading.remove>Save changes</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </form>
</div>
