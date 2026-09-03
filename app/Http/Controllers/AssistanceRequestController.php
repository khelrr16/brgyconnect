<?php

namespace App\Http\Controllers;

use App\Models\AssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssistanceRequestController extends Controller
{
    public function index()
    {
        $requests = AssistanceRequest::query()
            ->with('resident')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view(
            'certificates.assistance.index',
            compact('requests')
        );
    }

    public function create()
    {
        $resident = auth()->guard()->user()->resident;

        abort_unless($resident, 403);

        return view(
            'certificates.assistance.create',
            compact('resident')
        );
    }

    public function store(Request $request)
    {
        $user = auth()->guard()->user();

        abort_unless($user->resident, 403);

        $validated = $request->validate([

            'income_source' => [
                'required',
                'in:None,Occupation,Business',
            ],

            'occupation' => [
                'nullable',
                'required_if:income_source,Occupation',
                'string',
                'max:255',
            ],

            'monthly_income' => [
                'nullable',
                'required_if:income_source,Occupation',
                'numeric',
                'min:0',
            ],

            'business_type' => [
                'nullable',
                'required_if:income_source,Business',
                'string',
                'max:255',
            ],

            'business_duration' => [
                'nullable',
                'required_if:income_source,Business',
                'integer',
                'min:0',
            ],

            'assistance_type' => [
                'required',
                'in:Educational Assistance,Financial Assistance,Burial Assistance,Medical Assistance',
            ],

            'addressed_to' => [
                'required',
                'array',
                'min:1',
            ],

            'addressed_to.*' => [
                'required',
                'in:DSWD,DOH',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Request ID
        |--------------------------------------------------------------------------
        */

        $lastRequest = AssistanceRequest::query()
            ->latest('id')
            ->first();

        $nextNumber = $lastRequest
            ? $lastRequest->id + 1
            : 1;

        $requestId = 'AR-' . str_pad(
            $nextNumber,
            6,
            '0',
            STR_PAD_LEFT
        );


        /*
        |--------------------------------------------------------------------------
        | Create Request
        |--------------------------------------------------------------------------
        */

        AssistanceRequest::create([

            'request_id' => $requestId,

            'user_id' => $user->id,

            'resident_id' => $user->resident->id,

            'income_source' => $validated['income_source'],

            'occupation' =>
                $validated['occupation'] ?? null,

            'monthly_income' =>
                $validated['monthly_income'] ?? null,

            'business_type' =>
                $validated['business_type'] ?? null,

            'business_duration' =>
                $validated['business_duration'] ?? null,

            'assistance_type' =>
                $validated['assistance_type'],

            'addressed_to' =>
                $validated['addressed_to'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'status' => 'pending',
        ]);


        return redirect()
            ->route('certificate.assistance.index')
            ->with(
                'success',
                'Your assistance request has been submitted successfully.'
            );
    }

    public function show(AssistanceRequest $assistanceRequest)
    {
        abort_unless(
            $assistanceRequest->user_id === auth()->id(),
            403
        );

        return view(
            'certificates.assistance.show',
            compact('assistanceRequest')
        );
    }
}