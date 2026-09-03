<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssistanceRequest;
use Illuminate\Http\Request;

class AssistanceRequestController extends Controller
{
    /**
     * Show all assistance requests.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $requests = AssistanceRequest::query()
            ->with([
                'resident',
                'processor',
            ])
            ->when(
                $status !== 'all',
                fn ($query) => $query->where('status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.assistance-requests.index',
            compact('requests', 'status')
        );
    }

    public function show(AssistanceRequest $assistanceRequest)
    {
        $assistanceRequest->load([
            'resident',
            'processor',
        ]);

        return view(
            'admin.assistance-requests.show',
            compact('assistanceRequest')
        );
    }


    /**
     * Approve request.
     */
    public function approve(AssistanceRequest $assistanceRequest)
    {
        if (
            !in_array(
                $assistanceRequest->status,
                ['pending', 'processing'],
                true
            )
        ) {
            return back()->with(
                'error',
                'This assistance request has already been finalized.'
            );
        }

        $assistanceRequest->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with(
            'success',
            'Assistance request approved successfully.'
        );
    }


    /**
     * Reject request.
     */
    public function reject(
        Request $request,
        AssistanceRequest $assistanceRequest
    ) {
        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        if (
            !in_array(
                $assistanceRequest->status,
                ['pending', 'processing'],
                true
            )
        ) {
            return back()->with(
                'error',
                'This assistance request has already been finalized.'
            );
        }

        $assistanceRequest->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with(
            'success',
            'Assistance request rejected successfully.'
        );
    }


    /**
     * Mark request as processing.
     */
    public function process(AssistanceRequest $assistanceRequest)
    {
        if ($assistanceRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending requests can be marked as processing.'
            );
        }

        $assistanceRequest->update([
            'status' => 'processing',
        ]);

        return back()->with(
            'success',
            'Assistance request is now being processed.'
        );
    }

    public function print(AssistanceRequest $assistanceRequest)
    {
        $assistanceRequest->load('resident');

        // abort_unless(
        //     $assistanceRequest->status === 'approved',
        //     403
        // );

        return view(
            'admin.certificates.indigency.print',
            [
                'resident' => $assistanceRequest->resident,
                'purpose' => $assistanceRequest->purpose,
                'additional_information' =>
                    $assistanceRequest->additional_information,
            ]
        );
    }
}