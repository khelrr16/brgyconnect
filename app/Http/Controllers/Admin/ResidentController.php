<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        return view('admin.residents.index');
    }

    public function create(Request $request, Household $household)
    {
        return view('admin.residents.create', compact('household'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'household_id' => ['required','exists:households,id'],
            'first_name' => ['required','string','max:255'],
            'middle_name' => ['nullable','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'extension_name' => ['nullable','string','max:20'],
            'birth_date' => ['required','date'],

            'sex' => ['required','in:Male,Female'],

            'civil_status' => ['nullable','string','max:50'],

            // Education
            'school_attendance' => ['nullable','in:attending,not_attending,never_attended'],
            'educational_attainment' => ['nullable','in:no_formal_education,elementary,junior_high_school,senior_high_school,college,postgraduate,other'],
            'school_name' => ['nullable','string','max:255'],
            'grade_year_level' => ['nullable','string','max:100'],
            'employment_status' => ['nullable','in:employed,self_employed,unemployed,student,student_and_working,not_in_labor_force,other'],
            'other_educational_attainment' => ['nullable','string','max:255'],
            'reason_not_attending' => ['nullable','string','max:1000'],
        ]);

        $resident = Resident::create($validated);

        return redirect()
            ->route(
                'admin.households.show',
                $resident->household_id
            )
            ->with(
                'success',
                'Resident added to the household successfully.'
            );
    }

    public function show(Resident $resident)
    {
        return view('admin.residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        return view('admin.residents.edit', compact('resident'));
    }

    public function update(Resident $resident)
    {
        //
    }

    public function destroy(Resident $resident)
    {
        //
    }
}
