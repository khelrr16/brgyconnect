<?php

namespace App\Http\Controllers;

use App\Models\BlotterRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlotterRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BlotterRecord $blotter)
    {
        return view('blotters.show', compact('blotter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlotterRecord $blotter)
    {
        return view('blotters.edit', compact('blotter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request, BlotterRecord $blotter)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Resolved,Closed',
        ]);

        $blotter->update([
            'status' => $request->input('status'),
        ]);

        return redirect()->back()->with('status', 'Blotter Record updated successfully.');
    }

    public function print(BlotterRecord $blotter)
    {
        // Parties
        $respondent = $blotter->parties
            ->firstWhere('role', 'Respondent');

        $complainants = $blotter->parties
            ->where('role', 'Complainant');

        $witnesses = $blotter->parties
            ->where('role', 'Witness');


        // Hearings
        $hearings = $blotter->hearings
            ->sortBy(function ($hearing) {
                return $hearing->hearing_date . ' ' . $hearing->hearing_time;
            });


        // Next scheduled hearing
        $nextHearing = $hearings
            ->where('status', 'Scheduled')
            ->first();

        // Attachments
        $attachments = $blotter->attachments
            ->sortBy('created_at');


        // Formatted dates
        $dateReported = $blotter->created_at
            ? $blotter->created_at->format('F d, Y')
            : null;

        $incidentDate = $blotter->incident_date
            ? Carbon::parse($blotter->incident_date)->format('F d, Y')
            : null;

        $incidentTime = $blotter->incident_time
            ? Carbon::parse($blotter->incident_time)->format('g:i A')
            : null;


        // Hearing information
        $hearingDate = $nextHearing
            ? Carbon::parse($nextHearing->hearing_date)->format('F d, Y')
            : null;

        $hearingTime = $nextHearing
            ? Carbon::parse($nextHearing->hearing_time)->format('g:i A')
            : null;


        return view('blotters.print', [
            'blotter' => $blotter,

            'respondent' => $respondent,
            'complainants' => $complainants,
            'witnesses' => $witnesses,

            'hearings' => $hearings,
            'nextHearing' => $nextHearing,

            'dateReported' => $dateReported,
            'incidentDate' => $incidentDate,
            'incidentTime' => $incidentTime,

            'hearingDate' => $hearingDate,
            'hearingTime' => $hearingTime,
            'attachments' => $attachments,
        ]);
    }

    public function notice(BlotterRecord $blotter)
    {
        $respondent = $blotter->parties
            ->firstWhere('role', 'Respondent');

        $complainants = $blotter->parties
            ->where('role', 'Complainant');

        $witnesses = $blotter->parties
            ->where('role', 'Witness');

        $nextHearing = $blotter->hearings
            ->where('status', 'Scheduled')
            ->sortBy(function ($hearing) {
                return $hearing->hearing_date . ' ' . $hearing->hearing_time;
            })
            ->first();

        return view('blotters.notice', [
            'blotter' => $blotter,
            'respondent' => $respondent,
            'complainants' => $complainants,
            'witnesses' => $witnesses,
            'nextHearing' => $nextHearing,

            'incidentDate' => Carbon::parse($blotter->incident_date)
                ->format('F d, Y'),

            'incidentTime' => Carbon::parse($blotter->incident_time)
                ->format('g:i A'),

            'dateIssued' => now()
                ->format('F d, Y'),

            'hearingDate' => $nextHearing
                ? Carbon::parse($nextHearing->hearing_date)->format('F d, Y')
                : null,

            'hearingTime' => $nextHearing
                ? Carbon::parse($nextHearing->hearing_time)->format('g:i A')
                : null,
        ]);
    }
}
