<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        return view('residents.index');
    }

    public function create()
    {
        return view('residents.create');
    }

    public function store()
    {
        //
    }

    public function show(Resident $resident)
    {
        return view('residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        return view('residents.edit', compact('resident'));
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
