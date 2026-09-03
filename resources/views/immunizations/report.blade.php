<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Immunization Activity Report</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #1f2937; font-family: Arial, sans-serif; margin: 0; padding: 32px; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        h1 { font-size: 24px; margin: 0 0 6px; }
        h2 { font-size: 16px; margin: 0 0 4px; }
        p { color: #6b7280; font-size: 13px; margin: 0; }
        button { background: #2563eb; border: 0; border-radius: 6px; color: white; cursor: pointer; font-size: 13px; font-weight: 600; padding: 10px 14px; }
        .summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
        .summary div { border: 1px solid #dbeafe; border-radius: 8px; padding: 14px; }
        .summary strong { display: block; font-size: 22px; margin-top: 6px; }
        .summary span { color: #6b7280; font-size: 12px; }
        table { border-collapse: collapse; font-size: 12px; width: 100%; }
        th { background: #f3f4f6; text-align: left; }
        th, td { border: 1px solid #d1d5db; padding: 9px; vertical-align: top; }
        .muted { color: #6b7280; }
        .event { margin-bottom: 3px; }
        .event:last-child { margin-bottom: 0; }
        @media (max-width: 700px) {
            body { padding: 16px; }
            .summary { grid-template-columns: repeat(2, 1fr); }
            .toolbar { align-items: flex-start; gap: 16px; }
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .summary div { break-inside: avoid; }
            tr { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div>
            <h1>Immunization Activity Report</h1>
            <p>{{ $dateFrom->format('F d, Y') }} to {{ $dateTo->format('F d, Y') }}</p>
        </div>
        <button type="button" class="no-print" onclick="window.print()">Print report</button>
    </div>

    <div class="summary">
        <div><span>Vaccine doses used</span><strong>{{ number_format($vaccineDoseCount) }}</strong></div>
        <div><span>Vitamin A given</span><strong>{{ number_format($vitaminACount) }}</strong></div>
        <div><span>MNP 90 sachets given</span><strong>{{ number_format($mnp90Count) }}</strong></div>
        <div><span>MNP completed</span><strong>{{ number_format($mnpCompletedCount) }}</strong></div>
    </div>

    <h2>Recorded activity</h2>
    <p style="margin-bottom: 10px;">{{ number_format($rows->count()) }} infant record(s) had activity during this period.</p>

    <table>
        <thead>
            <tr>
                <th>Family ID</th>
                <th>Infant</th>
                <th>Vaccine doses</th>
                <th>Vitamin A / MNP</th>
                <th>Total events</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['immunization']->family_id }}</td>
                    <td>
                        <strong>{{ $row['immunization']->full_name }}</strong>
                        @if($row['immunization']->low_birth_weight)
                            <div class="muted">Low birth weight</div>
                        @endif
                    </td>
                    <td>
                        @forelse($row['doses'] as $dose)
                            <div class="event">{{ $dose->vaccine }} dose {{ $dose->dose_number }}: {{ $dose->date_given->format('M d, Y') }}</div>
                        @empty
                            <span class="muted">None</span>
                        @endforelse
                    </td>
                    <td>
                        @forelse($row['supplements'] as $label => $date)
                            <div class="event">{{ $label }}: {{ $date->format('M d, Y') }}</div>
                        @empty
                            <span class="muted">None</span>
                        @endforelse
                    </td>
                    <td>{{ $row['event_count'] }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center;">No activity was recorded during this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
