<?php

namespace App\Http\Controllers;

use App\Models\Immunization;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImmunizationController extends Controller
{
    public function index()
    {
        $search = request('search');
        $sort = request('sort', 'created_at');
        $direction = request('direction', 'desc');

        $sortableColumns = [
            'family_id' => 'family_id',
            'infant' => 'infant_last_name',
            'birthday' => 'birthday',
            'created_at' => 'created_at',
        ];

        $sortColumn = $sortableColumns[$sort] ?? 'created_at';
        $sortDirection = $direction === 'asc' ? 'asc' : 'desc';

        $immunizations = Immunization::query()
            ->with('resident')
            ->withCount([
                'vaccineDoses as marked_doses_count' => fn ($query) =>
                    $query->whereNotNull('date_given'),
                'nutritionalAssessments',
            ])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('infant_first_name', 'like', "%{$search}%")
                        ->orWhere('infant_last_name', 'like', "%{$search}%")
                        ->orWhere('parent_first_name', 'like', "%{$search}%")
                        ->orWhere('parent_last_name', 'like', "%{$search}%")
                        ->orWhereHas('resident', function ($query) use ($search) {
                            $query->where('resident_id', 'like', "%{$search}%")
                                ->orWhere('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(10)
            ->withQueryString();

        $totalImmunizations = Immunization::count();
        $fullyImmunized = Immunization::whereNotNull('fic_date')->count();
        $completelyImmunized = Immunization::whereNotNull('cic_date')->count();
        $lowBirthWeight = Immunization::where('low_birth_weight', true)->count();
        $assessments = \App\Models\NutritionalAssessment::count();

        return view('immunizations.index', compact(
            'immunizations',
            'totalImmunizations',
            'fullyImmunized',
            'completelyImmunized',
            'lowBirthWeight',
            'assessments'
        ));
    }

    public function create()
    {
        return view('immunizations.create');
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
        ]);

        $dateFrom = Carbon::parse($validated['date_from'])->startOfDay();
        $dateTo = Carbon::parse($validated['date_to'])->endOfDay();

        $immunizations = Immunization::query()
            ->with([
                'vaccineDoses' => fn ($query) => $query
                    ->whereBetween('date_given', [$dateFrom->toDateString(), $dateTo->toDateString()])
                    ->orderBy('date_given'),
            ])
            ->where(function ($query) use ($dateFrom, $dateTo) {
                $query->whereHas('vaccineDoses', fn ($query) => $query->whereBetween(
                    'date_given',
                    [$dateFrom->toDateString(), $dateTo->toDateString()]
                ))
                    ->orWhereBetween('vitamin_a_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
                    ->orWhereBetween('mnp_90_sachets_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
                    ->orWhereBetween('mnp_completed_date', [$dateFrom->toDateString(), $dateTo->toDateString()]);
            })
            ->orderBy('infant_last_name')
            ->orderBy('infant_first_name')
            ->get();

        $rows = $immunizations->map(function (Immunization $immunization) use ($dateFrom, $dateTo) {
            $supplements = collect([
                'Vitamin A' => $immunization->vitamin_a_date,
                'MNP 90 sachets' => $immunization->mnp_90_sachets_date,
                'MNP completed' => $immunization->mnp_completed_date,
            ])->filter(fn ($date) => $date && $date->betweenIncluded($dateFrom, $dateTo));

            return [
                'immunization' => $immunization,
                'doses' => $immunization->vaccineDoses,
                'supplements' => $supplements,
                'event_count' => $immunization->vaccineDoses->count() + $supplements->count(),
            ];
        });

        return view('immunizations.report', [
            'rows' => $rows,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'vaccineDoseCount' => $rows->sum(fn ($row) => $row['doses']->count()),
            'vitaminACount' => $rows->sum(fn ($row) => $row['supplements']->has('Vitamin A') ? 1 : 0),
            'mnp90Count' => $rows->sum(fn ($row) => $row['supplements']->has('MNP 90 sachets') ? 1 : 0),
            'mnpCompletedCount' => $rows->sum(fn ($row) => $row['supplements']->has('MNP completed') ? 1 : 0),
        ]);
    }

    public function store(Request $request)
    {
        // Logic to store a new immunization record in the database
    }

    public function show(Immunization $immunization)
    {
        $immunization->load([
            'resident',
            'vaccineDoses',
            'nutritionalAssessments',
        ]);

        return view('immunizations.show', compact('immunization'));
    }

    public function edit(Immunization $immunization)
    {
        return view('immunizations.edit', compact('immunization'));
    }
}
