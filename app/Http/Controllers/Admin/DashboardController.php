<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC COUNTS
        |--------------------------------------------------------------------------
        */

        $totalResidents = Resident::count();

        $maleResidents = Resident::where('sex', 'Male')->count();

        $femaleResidents = Resident::where('sex', 'Female')->count();

        $today = now();

        $minorResidents = Resident::whereDate(
            'birth_date',
            '>',
            $today->copy()->subYears(18)
        )->count();

        $seniorResidents = Resident::whereDate(
            'birth_date',
            '<=',
            $today->copy()->subYears(60)
        )->count();


        /*
        |--------------------------------------------------------------------------
        | SEX DISTRIBUTION
        |--------------------------------------------------------------------------
        */

        $sexDistribution = Resident::query()
            ->select('sex', DB::raw('COUNT(*) as total'))
            ->groupBy('sex')
            ->orderBy('sex')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGE GROUPS
        |--------------------------------------------------------------------------
        */

        $ageGroups = [
            '0-12' => 0,
            '13-17' => 0,
            '18-59' => 0,
            '60+' => 0,
        ];

        $residents = Resident::query()
            ->select('birth_date')
            ->whereNotNull('birth_date')
            ->get();

        foreach ($residents as $resident) {

            $age = $resident->birth_date->age;

            if ($age <= 12) {
                $ageGroups['0-12']++;
            } elseif ($age <= 17) {
                $ageGroups['13-17']++;
            } elseif ($age <= 59) {
                $ageGroups['18-59']++;
            } else {
                $ageGroups['60+']++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CIVIL STATUS
        |--------------------------------------------------------------------------
        |
        | Remove this section if your residents table does not have
        | a civil_status column.
        |
        */

        $civilStatusDistribution = Resident::query()
            ->select('civil_status', DB::raw('COUNT(*) as total'))
            ->whereNotNull('civil_status')
            ->groupBy('civil_status')
            ->orderBy('civil_status')
            ->get();

        $laborForce = [
            'Unemployed' => Resident::whereIn('employment_status', ['Unemployed', 'unemployed'])->count(),
            'Out-of-School Children' => Resident::whereIn('out_of_school', ['Children', 'Child', 'Yes - Child'])->count(),
            'Out-of-School Youth' => Resident::whereIn('out_of_school', ['Youth', 'Yes - Youth'])->count(),
            'PWD' => Resident::whereIn('is_pwd', ['Yes', 'yes', '1', 1])->count(),
            'OFW' => Resident::whereIn('is_ofw', ['Yes', 'yes', '1', 1])->count(),
            'Solo Parent' => Resident::whereIn('is_solo_parent', ['Yes', 'yes', '1', 1])->count(),
            'Indigenous People' => Resident::whereIn('is_indigenous', ['Yes', 'yes', '1', 1])->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | MONTHLY RESIDENT REGISTRATION
        |--------------------------------------------------------------------------
        |
        | Assumes your residents table has created_at.
        |
        */

        $monthlyRegistrations = Resident::query()
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month_key', 'month')
            ->orderBy('month_key')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalResidents',
            'maleResidents',
            'femaleResidents',
            'minorResidents',
            'seniorResidents',
            'sexDistribution',
            'ageGroups',
            'civilStatusDistribution',
            'laborForce',
            'monthlyRegistrations'
        ));
    }

    public function populationReport()
    {
        $residents = Resident::query()
            ->whereNotNull('birth_date')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $ageGroups = [
            'Under 5 years old' => fn ($age) => $age < 5,
            '5-9' => fn ($age) => $age >= 5 && $age <= 9,
            '10-14' => fn ($age) => $age >= 10 && $age <= 14,
            '15-19' => fn ($age) => $age >= 15 && $age <= 19,
            '20-24' => fn ($age) => $age >= 20 && $age <= 24,
            '25-29' => fn ($age) => $age >= 25 && $age <= 29,
            '30-34' => fn ($age) => $age >= 30 && $age <= 34,
            '35-39' => fn ($age) => $age >= 35 && $age <= 39,
            '40-44' => fn ($age) => $age >= 40 && $age <= 44,
            '45-49' => fn ($age) => $age >= 45 && $age <= 49,
            '50-54' => fn ($age) => $age >= 50 && $age <= 54,
            '55-59' => fn ($age) => $age >= 55 && $age <= 59,
            '60-64' => fn ($age) => $age >= 60 && $age <= 64,
            '65-69' => fn ($age) => $age >= 65 && $age <= 69,
            '70-74' => fn ($age) => $age >= 70 && $age <= 74,
            '75-79' => fn ($age) => $age >= 75 && $age <= 79,
            '80 years old and over' => fn ($age) => $age >= 80,
        ];

        $ageLists = collect($ageGroups)->mapWithKeys(fn ($matcher, $label) => [
            $label => $residents->filter(fn ($resident) => $matcher($resident->birth_date->age))->values(),
        ]);

        $categoryLists = [
            'Labor Force' => [
                'Unemployed' => $residents->whereIn('employment_status', ['Unemployed', 'unemployed']),
                'Out-of-School Children' => $residents->whereIn('out_of_school', ['Children', 'Child', 'Yes - Child']),
                'Out-of-School Youth' => $residents->whereIn('out_of_school', ['Youth', 'Yes - Youth']),
                'PWD' => $residents->whereIn('is_pwd', ['Yes', 'yes', '1', 1]),
                'OFW' => $residents->whereIn('is_ofw', ['Yes', 'yes', '1', 1]),
                'Solo Parent' => $residents->whereIn('is_solo_parent', ['Yes', 'yes', '1', 1]),
                'Indigenous People' => $residents->whereIn('is_indigenous', ['Yes', 'yes', '1', 1]),
            ],
            'Civil Status' => [
                'Single' => $residents->whereIn('civil_status', ['Single', 'single']),
                'Married' => $residents->whereIn('civil_status', ['Married', 'married']),
            ],
            'Citizenship' => [
                'Filipino' => $residents->whereIn('citizenship', ['Filipino', 'filipino']),
                'Foreigner' => $residents->filter(fn ($resident) => $resident->citizenship && strtolower($resident->citizenship) !== 'filipino'),
            ],
        ];

        return view('admin.population-report', compact('ageLists', 'categoryLists'));
    }
}