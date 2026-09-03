<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HouseholdController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->search ?? '');

        $households = Household::query()
            ->withCount('residents')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('household_id', 'like', "%{$search}%")
                        ->orWhere('subdivision', 'like', "%{$search}%")
                        ->orWhere('block', 'like', "%{$search}%")
                        ->orWhere('lot', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('street', 'like', "%{$search}%");

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.households.index', compact('households'));
    }

    public function create()
    {
        return view('admin.households.create');
    }

    public function show(Household $household)
    {
        return view('admin.households.show', compact('household'));
    }
}