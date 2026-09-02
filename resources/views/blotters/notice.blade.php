<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Notice to Respondent — Blotter #{{ $blotter->id }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .sheet {
                box-shadow: none !important;
                margin: 0 !important;
            }

            @page {
                size: A4;
                margin: 18mm 16mm;
            }
        }
    </style>
</head>

<body class="min-h-screen py-10 px-4 bg-gray-500 font-serif text-navy-dark">

    {{-- Print Button --}}
    <div class="no-print max-w-[210mm] mx-auto mb-4 flex justify-end">

        <button
            onclick="window.print()"
            class="inline-flex items-center gap-2 text-xs uppercase tracking-wide
                   font-sans font-semibold px-5 py-2.5 bg-navy text-white
                   rounded-sm hover:bg-navy-dark"
        >
            <i class="fa-solid fa-print"></i>
            Print Letter
        </button>

    </div>


    {{-- A4 Paper --}}
    <div class="sheet bg-white max-w-[210mm] mx-auto shadow-lg px-14 py-14">


        {{-- ============================= --}}
        {{-- LETTERHEAD --}}
        {{-- ============================= --}}

        <div class="flex items-center justify-between gap-6 pb-4 border-b-2 border-navy">

            {{-- Republic Logo --}}
            <img
                src="{{ asset('images/republic_logo.png') }}"
                alt="Republic of the Philippines Seal"
                class="w-20 h-20 object-contain"
            >


            {{-- Barangay Information --}}
            <div class="text-center flex-1">

                <p class="uppercase tracking-[0.2em] text-[11px]
                          text-navy/70 font-sans font-semibold">
                    Republic of the Philippines
                </p>

                <p class="text-[11px] text-navy/70 font-sans">
                    Province of Laguna, City of San Pedro
                </p>

                <h1 class="font-display text-xl text-navy mt-1">
                    Barangay San Lorenzo Ruiz
                </h1>

                <p class="text-[11px] text-navy/50 font-sans">
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


        {{-- ============================= --}}
        {{-- CASE INFORMATION --}}
        {{-- ============================= --}}

        <div class="flex justify-between items-baseline mt-6 text-sm font-sans">

            <p>
                <span class="text-navy/50">
                    Blotter Case No.:
                </span>

                <span class="font-semibold text-navy">
                    {{ $blotter->blotter_number }}
                </span>
            </p>


            <p>
                <span class="text-navy/50">
                    Date Issued:
                </span>

                <span class="font-semibold text-navy">
                    {{ $dateIssued }}
                </span>
            </p>

        </div>


        {{-- ============================= --}}
        {{-- ADDRESSEE --}}
        {{-- ============================= --}}

        <div class="mt-8 text-[15px] leading-relaxed">

            <p>
                To:
            </p>

            <p class="font-semibold text-navy mt-1">
                {{ $respondent?->full_name ?? '________________' }}
            </p>

            @if ($respondent?->contact_number)

                <p class="text-navy/70 text-sm">
                    Contact No.:
                    {{ $respondent->contact_number }}
                </p>

            @endif

        </div>


        {{-- ============================= --}}
        {{-- TITLE --}}
        {{-- ============================= --}}

        <p class="mt-8 text-[15px] leading-relaxed text-center
                  font-display tracking-wide">

            NOTICE TO APPEAR

        </p>


        {{-- ============================= --}}
        {{-- BODY --}}
        {{-- ============================= --}}

        <div class="mt-4 text-[15px] leading-relaxed text-justify">

            <p>

                You are hereby notified that a complaint has been filed
                against you before this Barangay, docketed as

                <strong>
                    Blotter Case No. {{ $blotter->blotter_number }}
                </strong>,

                concerning an incident which allegedly occurred on

                <strong>
                    {{ $incidentDate }}
                </strong>

                at around

                <strong>
                    {{ $incidentTime }}
                </strong>,

                at

                <strong>
                    {{ $blotter->incident_location }}
                </strong>.

            </p>


            <p class="mt-4">

                The complaint was filed by

                <strong>
                    {{ $complainants->pluck('full_name')->implode(', ') ?: '________________' }}
                </strong>.

                @if ($witnesses->isNotEmpty())

                    The following were named as witnesses:

                    <strong>
                        {{ $witnesses->pluck('full_name')->implode(', ') }}
                    </strong>.

                @endif

            </p>


            <p class="mt-4">

                You are hereby directed to appear before the Office of
                the Punong Barangay for mediation and settlement
                proceedings in accordance with the Katarungang
                Pambarangay Law and applicable law.

            </p>

        </div>


        {{-- ============================= --}}
        {{-- HEARING SCHEDULE --}}
        {{-- ============================= --}}

        <div class="mt-6 border border-rule rounded-sm px-6 py-4">

            <p class="text-[10px] uppercase tracking-wide
                      text-navy/50 font-sans font-semibold">

                Scheduled Hearing

            </p>


            @if ($nextHearing)

                <p class="font-display text-lg text-navy mt-1">

                    {{ $hearingDate }}

                    at

                    {{ $hearingTime }}

                </p>

            @else

                <p class="font-display text-lg text-navy mt-1">
                    No scheduled hearing
                </p>

            @endif


            <p class="text-sm text-navy/70 mt-1">
                Barangay Hall
            </p>

        </div>


        {{-- ============================= --}}
        {{-- CLOSING --}}
        {{-- ============================= --}}

        <p class="mt-6 text-[15px] leading-relaxed text-justify">

            Failure on your part to appear on the said date and time
            shall be addressed in accordance with applicable barangay
            procedure and law.

        </p>


        {{-- ============================= --}}
        {{-- SIGNATURE --}}
        {{-- ============================= --}}

        <div class="mt-14 flex justify-end">

            <div class="text-center">

                <p class="font-semibold text-navy">
                    HON. ROMEO B. BONOAN
                </p>

                <p class="text-[11px] text-navy/50 italic
                          font-semibold font-sans mt-1
                          border-t border-rule pt-1">

                    Punong Barangay
                </p>

            </div>

        </div>

    </div>

</body>

</html>