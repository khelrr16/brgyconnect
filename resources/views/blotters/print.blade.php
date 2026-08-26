<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Blotter Record - {{ $blotter->blotter_number }}
    </title>

    @vite('resources/css/app.css')

    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: Roboto, Arial, sans-serif;
            color: #000;
            background: #fff;
        }

        .print-document {
            max-width: 100%;
            margin: auto;
        }

        .print-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            text-align: center;
        }

        .print-logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .print-header p {
            margin: 0;
            font-size: 11px;
        }

        .print-header h1 {
            margin: 3px 0;
            font-size: 18px;
        }

        .print-header h2 {
            margin-top: 8px;
            font-size: 16px;
        }

        .divider {
            margin: 15px 0;
            border-top: 2px solid black;
        }

        section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        section h3 {
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid black;
            font-size: 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 20px;
        }

        .full {
            grid-column: 1 / -1;
        }

        .party {
            border: 1px solid #999;
            padding: 10px;
            margin-bottom: 10px;
        }

        .hearing {
            border: 1px solid #999;
            padding: 10px;
            margin-bottom: 10px;
        }

        .attachments {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .attachment img {
            width: 100%;
            max-height: 180px;
            object-fit: contain;
            border: 1px solid #999;
        }

        .signature-area {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            margin-top: 60px;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid black;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>


<body>

<div class="print-document">

    {{-- Header --}}
    <div class="print-header">

        <div class="print-logo">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="Barangay Logo"
            >
        </div>

        <div>
            <p>REPUBLIC OF THE PHILIPPINES</p>
            <p>PROVINCE OF LAGUNA</p>
            <p>CITY OF SAN PEDRO</p>

            <h1>
                BARANGAY SAN LORENZO RUIZ
            </h1>

            <h2>
                BARANGAY BLOTTER RECORD
            </h2>
        </div>

    </div>


    <div class="divider"></div>


    {{-- Case information --}}
    <section>

        <h3>CASE INFORMATION</h3>

        <div class="grid">

            <div>
                <strong>Blotter Number:</strong>
                {{ $blotter->blotter_number }}
            </div>

            <div>
                <strong>Status:</strong>
                {{ $blotter->status }}
            </div>

            <div>
                <strong>Incident Type:</strong>
                {{ $blotter->incident_type }}
            </div>

            <div>
                <strong>Incident Date:</strong>
                {{ $blotter->incident_date->format('F j, Y') }}
            </div>

            <div>
                <strong>Incident Time:</strong>
                {{
                    $blotter->incident_time
                        ? \Carbon\Carbon::parse($blotter->incident_time)->format('g:i A')
                        : 'N/A'
                }}
            </div>

            <div>
                <strong>Location:</strong>
                {{ $blotter->incident_location }}
            </div>

        </div>

    </section>


    {{-- People --}}
    <section>

        <h3>PEOPLE INVOLVED</h3>
            @php
                $roleOrder = [
                    'Complainant' => 1,
                    'Respondent' => 2,
                    'Witness' => 3,
                ];

                $parties = $blotter->parties
                    ->sortBy(fn ($party) => $roleOrder[$party->role] ?? 999);
            @endphp

            @foreach($parties as $party)

            <div class="party">

                <strong>
                    {{ $party->role }}
                </strong>

                <div class="grid" style="margin-top: 8px;">
                    <div>
                        <div>
                            <strong>Name:</strong>

                            {{ $party->last_name }},
                            {{ $party->first_name }}
                            {{ $party->middle_name }}
                            {{ $party->extension_name }}
                        </div>

                        <div>
                            <strong>Contact:</strong>
                            {{ $party->contact_number ?: 'N/A' }}
                        </div>
                    </div>

                    <div>
                        <strong>Address:</strong>
                        {{ $party->address ?: 'N/A' }}
                    </div>

                </div>

            </div>

        @endforeach

    </section>


    {{-- Incident --}}
    <section>

        <h3>INCIDENT DESCRIPTION</h3>

        <p style="white-space: pre-line;">
            {{ $blotter->incident_description }}
        </p>

    </section>


    {{-- Action --}}
    @if($blotter->action_taken)

        <section>

            <h3>ACTION TAKEN</h3>

            <p style="white-space: pre-line;">
                {{ $blotter->action_taken }}
            </p>

        </section>

    @endif


    {{-- Remarks --}}
    @if($blotter->remarks)

        <section>

            <h3>REMARKS</h3>

            <p style="white-space: pre-line;">
                {{ $blotter->remarks }}
            </p>

        </section>

    @endif


    {{-- Hearings --}}
    <section>

        <h3>HEARING RECORD</h3>

        @forelse($blotter->hearings->sortBy('hearing_date') as $hearing)

            <div class="hearing">

                <strong>
                    {{ $hearing->hearing_date->format('F j, Y') }}
                </strong>

                at

                {{ \Carbon\Carbon::parse($hearing->hearing_time)->format('g:i A') }}

                <br>

                <strong>Type:</strong>
                {{ $hearing->hearing_type ?: 'Hearing' }}

                <br>

                <strong>Status:</strong>
                {{ $hearing->status }}

                @if($hearing->notes)

                    <br>

                    <strong>Notes:</strong>
                    {{ $hearing->notes }}

                @endif

            </div>

        @empty

            <p>
                No hearing records.
            </p>

        @endforelse

    </section>


    {{-- Attachments --}}
    @if($blotter->attachments->count())

        <section>

            <h3>CASE ATTACHMENTS</h3>

            <div class="attachments">

                @foreach($blotter->attachments as $attachment)

                    <div class="attachment">

                        <img
                            src="{{ asset('storage/' . $attachment->file_path) }}"
                            alt="{{ $attachment->original_name }}"
                        >

                        <p style="font-size: 9px; text-align: center;">
                            {{ $attachment->original_name }}
                        </p>

                    </div>

                @endforeach

            </div>

        </section>

    @endif


    {{-- Signatures --}}
    <div class="signature-area">

        <div>

            <p>Recorded by:</p>

            <div class="signature-line"></div>

            <strong>
                {{ $blotter->creator?->name ?? '________________' }}
            </strong>

            <p>
                Barangay Personnel
            </p>

        </div>


        <div>

            <p>Date Recorded:</p>

            <div class="signature-line"></div>

            <strong>
                {{ $blotter->created_at->format('F j, Y') }}
            </strong>

        </div>

    </div>

</div>


<script>
    window.addEventListener('load', () => {
        window.print();
    });
</script>

</body>
</html>