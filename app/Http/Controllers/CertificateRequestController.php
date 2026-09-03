<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use Illuminate\Http\Request;

class CertificateRequestController extends Controller
{
    public function create()
    {
        return view('certificates.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'certificate_type' => [
                'required',
                'string',
                'max:255',
            ],

            'purpose' => [
                'required',
                'string',
                'max:2000',
            ],

            'additional_information' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        CertificateRequest::create([
            'user_id' => auth()->id(),
            'certificate_type' => $validated['certificate_type'],
            'purpose' => $validated['purpose'],
            'additional_information' =>
                $validated['additional_information'] ?? null,
            'status' => 'pending',
        ]);


        return redirect()
            ->route('certificate.requests.index')
            ->with(
                'success',
                'Your certificate request has been submitted successfully.'
            );
    }
}