<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Blotter Record — BLT-{{ str_pad($blotter->id, 6, '0', STR_PAD_LEFT) }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            html,
            body {
                width: 210mm;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .sheet {
                width: 190mm !important;
                max-width: 190mm !important;
                min-height: 277mm;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                overflow: visible !important;
            }
        }

    </style>

</head>


<body class="min-h-screen py-10 px-4 bg-gray-500 font-serif text-gray-900">


    {{-- ===================================================== --}}
    {{-- PRINT BUTTON --}}
    {{-- ===================================================== --}}

    <div class="no-print max-w-[210mm] mx-auto mb-4 flex justify-end">

        <button
            onclick="window.print()"
            class="inline-flex items-center gap-2
                   text-xs uppercase tracking-wide
                   font-sans font-semibold
                   px-5 py-2.5
                   bg-gray-800 text-white
                   rounded-sm
                   hover:bg-gray-700"
        >

            <i class="fa-solid fa-print"></i>

            Print Blotter Record

        </button>

    </div>



    {{-- ===================================================== --}}
    {{-- A4 PAPER --}}
    {{-- ===================================================== --}}

    <div class="sheet bg-white max-w-[210mm] mx-auto shadow-lg px-14 py-12">


        {{-- ================================================= --}}
        {{-- LETTERHEAD --}}
        {{-- ================================================= --}}

        <div class="flex items-center justify-between gap-6
                    pb-4 border-b-2 border-gray-800">


            {{-- Republic Logo --}}

            <img
                src="{{ asset('images/republic_logo.png') }}"
                alt="Republic of the Philippines Seal"
                class="w-20 h-20 object-contain"
            >


            {{-- Barangay Information --}}

            <div class="text-center flex-1">

                <p class="uppercase tracking-[0.2em]
                          text-[11px] text-gray-600
                          font-sans font-semibold">

                    Republic of the Philippines

                </p>


                <p class="text-[11px] text-gray-600 font-sans">

                    Province of Laguna, City of San Pedro

                </p>


                <h1 class="text-xl font-bold text-gray-900 mt-1">

                    Barangay San Lorenzo Ruiz

                </h1>


                <p class="text-[11px] text-gray-500 font-sans">

                    Office of the Punong Barangay

                </p>

            </div>


            {{-- Barangay Logo --}}

            <img
                src="{{ asset('images/brgy_logo.png') }}"
                alt="Barangay Logo"
                class="w-20 h-20 object-contain"
            >

        </div>



        {{-- ================================================= --}}
        {{-- TITLE --}}
        {{-- ================================================= --}}

        <div class="text-center mt-7">

            <h2 class="text-lg font-bold uppercase tracking-wide">

                Blotter Record Report

            </h2>

            <p class="text-xs text-gray-500 mt-1">

                Official Barangay Incident Record

            </p>

        </div>



        {{-- ================================================= --}}
        {{-- CASE INFORMATION --}}
        {{-- ================================================= --}}

        <div class="mt-7 border border-gray-300 rounded-sm">

            <div class="bg-gray-100 px-4 py-2
                        border-b border-gray-300">

                <h3 class="text-xs uppercase tracking-wider
                           font-bold text-gray-700">

                    Case Information

                </h3>

            </div>


            <div class="grid grid-cols-2 text-sm">


                {{-- Blotter Number --}}

                <div class="px-4 py-3 border-b border-r border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Blotter Case No.

                    </p>

                    <p class="font-semibold mt-1">

                        BLT-{{ str_pad($blotter->id, 6, '0', STR_PAD_LEFT) }}

                    </p>

                </div>


                {{-- Date Reported --}}

                <div class="px-4 py-3 border-b border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Date Reported

                    </p>

                    <p class="font-semibold mt-1">

                        {{ $dateReported ?? '________________' }}

                    </p>

                </div>


                {{-- Incident Date --}}

                <div class="px-4 py-3 border-b border-r border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Incident Date

                    </p>

                    <p class="font-semibold mt-1">

                        {{ $incidentDate ?? '________________' }}

                    </p>

                </div>


                {{-- Incident Time --}}

                <div class="px-4 py-3 border-b border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Incident Time

                    </p>

                    <p class="font-semibold mt-1">

                        {{ $incidentTime ?? '________________' }}

                    </p>

                </div>


                {{-- Location --}}

                <div class="px-4 py-3 border-b border-r border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Incident Location

                    </p>

                    <p class="font-semibold mt-1">

                        {{ $blotter->incident_location ?? '________________' }}

                    </p>

                </div>


                {{-- Status --}}

                <div class="px-4 py-3 border-b border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Case Status

                    </p>

                    <p class="font-semibold mt-1 uppercase">

                        {{ $blotter->status ?? '________________' }}

                    </p>

                </div>


                {{-- Nature --}}

                <div class="px-4 py-3 col-span-2">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Nature of Incident

                    </p>

                    <p class="font-semibold mt-1">

                        {{ $blotter->incident_type ?? '________________' }}

                    </p>

                </div>

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- PARTIES --}}
        {{-- ================================================= --}}

        <div class="mt-6 border border-gray-300 rounded-sm">

            <div class="bg-gray-100 px-4 py-2
                        border-b border-gray-300">

                <h3 class="text-xs uppercase tracking-wider
                           font-bold text-gray-700">

                    Parties Involved

                </h3>

            </div>


            <div class="text-sm">


                {{-- Complainants --}}

                <div class="px-4 py-3 border-b border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Complainant(s)

                    </p>

                    @forelse ($complainants as $complainant)

                        <p class="font-semibold mt-1">

                            {{ $complainant->full_name }}

                        </p>

                        @if ($complainant->contact_number)

                            <p class="text-xs text-gray-500">

                                Contact:
                                {{ $complainant->contact_number }}

                            </p>

                        @endif

                    @empty

                        <p class="text-gray-400 mt-1">
                            No complainant recorded.
                        </p>

                    @endforelse

                </div>



                {{-- Respondent --}}

                <div class="px-4 py-3 border-b border-gray-300">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Respondent

                    </p>


                    @if ($respondent)

                        <p class="font-semibold mt-1">

                            {{ $respondent->full_name }}

                        </p>


                        @if ($respondent->contact_number)

                            <p class="text-xs text-gray-500">

                                Contact:
                                {{ $respondent->contact_number }}

                            </p>

                        @endif

                    @else

                        <p class="text-gray-400 mt-1">

                            No respondent recorded.

                        </p>

                    @endif

                </div>



                {{-- Witnesses --}}

                <div class="px-4 py-3">

                    <p class="text-[10px] uppercase
                              tracking-wide text-gray-500">

                        Witness(es)

                    </p>


                    @forelse ($witnesses as $witness)

                        <p class="font-semibold mt-1">

                            {{ $witness->full_name }}

                        </p>

                    @empty

                        <p class="text-gray-400 mt-1">

                            No witnesses recorded.

                        </p>

                    @endforelse

                </div>

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- INCIDENT DETAILS --}}
        {{-- ================================================= --}}

        <div class="mt-6 border border-gray-300 rounded-sm">

            <div class="bg-gray-100 px-4 py-2
                        border-b border-gray-300">

                <h3 class="text-xs uppercase tracking-wider
                           font-bold text-gray-700">

                    Incident / Complaint Details

                </h3>

            </div>


            <div class="px-4 py-4">

                <p class="text-sm leading-7 text-justify">
                    @if($blotter->incident_description)
                        {{ $blotter->incident_description }}
                    @else
                        <span class="text-gray-500 italic">No incident details were recorded.</span>
                    @endif
                </p>

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- HEARING / PROCEEDINGS --}}
        {{-- ================================================= --}}

        <div class="mt-6 border border-gray-300 rounded-sm">

            <div class="bg-gray-100 px-4 py-2
                        border-b border-gray-300">

                <h3 class="text-xs uppercase tracking-wider
                           font-bold text-gray-700">

                    Hearing / Proceedings

                </h3>

            </div>


            <div class="px-4 py-4">


                @if ($hearings->isNotEmpty())

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="border-b border-gray-300">

                                <th class="text-left py-2">
                                    Date
                                </th>

                                <th class="text-left py-2">
                                    Time
                                </th>

                                <th class="text-left py-2">
                                    Status
                                </th>

                                <th class="text-left py-2">
                                    Notes
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($hearings as $hearing)

                                <tr class="border-b border-gray-200">

                                    <td class="py-2">

                                        {{ \Illuminate\Support\Carbon::parse(
                                            $hearing->hearing_date
                                        )->format('F d, Y') }}

                                    </td>


                                    <td class="py-2">

                                        {{ \Illuminate\Support\Carbon::parse(
                                            $hearing->hearing_time
                                        )->format('g:i A') }}

                                    </td>


                                    <td class="py-2 uppercase">

                                        {{ $hearing->status }}

                                    </td>


                                    <td class="py-2">
                                        @if( $hearing->notes )
                                            {{ $hearing->notes }}
                                        @else
                                            <span class="text-gray-500 italic">No notes recorded.</span> 
                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <p class="text-sm text-gray-500">

                        No hearing or proceeding has been scheduled.

                    </p>

                @endif


            </div>

        </div>



        {{-- ================================================= --}}
        {{-- NEXT HEARING --}}
        {{-- ================================================= --}}

        @if ($nextHearing)

            <div class="mt-5 border border-gray-400
                        px-5 py-4">

                <p class="text-[10px] uppercase
                          tracking-wider text-gray-500
                          font-sans font-semibold">

                    Next Scheduled Hearing

                </p>


                <p class="font-bold text-lg mt-1">

                    {{ $hearingDate }}

                    at

                    {{ $hearingTime }}

                </p>


                <p class="text-sm text-gray-600 mt-1">

                    Barangay Hall

                </p>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- ACTION TAKEN --}}
        {{-- ================================================= --}}

        <div class="mt-6 border border-gray-300 rounded-sm">

            <div class="bg-gray-100 px-4 py-2
                        border-b border-gray-300">

                <h3 class="text-xs uppercase tracking-wider
                           font-bold text-gray-700">

                    Action Taken

                </h3>

            </div>


            <div class="px-4 py-4">

                <p class="text-sm leading-7 text-justify">
                    @if($blotter->action_taken)
                        {{ $blotter->action_taken }}
                    @else
                        <span class="text-gray-500 italic">No action has been taken.</span>
                    @endif
                </p>

            </div>

        </div>

        {{-- ================================================= --}}
        {{-- ATTACHMENTS --}}
        {{-- ================================================= --}}

        <div class="mt-6 border border-gray-300 rounded-sm section">

            <div class="bg-gray-100 px-4 py-2 border-b border-gray-300">

                <h3 class="text-xs uppercase tracking-wider font-bold text-gray-700">
                    Attachments
                </h3>

            </div>


            <div class="px-4 py-4">

                @if ($blotter->attachments->isNotEmpty())

                    <div class="grid grid-cols-2 gap-4">

                        @foreach ($blotter->attachments as $attachment)

                            <div class="border border-gray-200 rounded-sm p-2">

                                <img
                                    src="{{ asset('storage/' . $attachment->file_path) }}"
                                    alt="Blotter Attachment"
                                    class="w-full h-auto max-h-[100mm] object-contain"
                                >

                                @if ($attachment->description)
                                    <p class="text-xs text-gray-500 mt-2 text-center">
                                        {{ $attachment->description }}
                                    </p>
                                @endif

                            </div>

                        @endforeach

                    </div>

                @else

                    <p class="text-sm text-gray-500 italic">
                        No attachments have been uploaded.
                    </p>

                @endif

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- CERTIFICATION --}}
        {{-- ================================================= --}}

        <div class="mt-8 text-sm leading-relaxed text-justify">

            <p>

                This document is a record of the complaint and
                proceedings recorded in the Barangay Blotter System.
                The information contained herein is based on the
                records submitted to and maintained by the Office
                of the Punong Barangay.

            </p>

        </div>



        {{-- ================================================= --}}
        {{-- SIGNATURE --}}
        {{-- ================================================= --}}

        <div class="mt-16 flex justify-end">

            <div class="text-center min-w-[220px]">

                <p class="font-bold">

                    HON. ROMEO B. BONOAN

                </p>


                <p class="text-xs text-gray-500
                          font-sans border-t
                          border-gray-400 pt-1 mt-1">

                    Punong Barangay

                </p>

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- FOOTER --}}
        {{-- ================================================= --}}

        <div class="mt-12 pt-3 border-t
                    border-gray-200 text-center">

            <p class="text-[9px] text-gray-400 font-sans">

                BrgyConnect — Barangay Information System

            </p>

            <p class="text-[9px] text-gray-400 font-sans">

                Blotter Case No.
                BLT-{{ str_pad($blotter->id, 6, '0', STR_PAD_LEFT) }}

            </p>

        </div>


    </div>

</body>

</html>