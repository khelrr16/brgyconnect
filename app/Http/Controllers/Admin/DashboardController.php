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
            'monthlyRegistrations'
        ));
    }
}