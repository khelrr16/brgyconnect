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
        p { color: #6b7280; font-size: 13px; margin: 0; }
        button { background: #2563eb; border: 0; border-radius: 6px; color: white; cursor: pointer; font-size: 13px; font-weight: 600; padding: 10px 14px; }
        table { border-collapse: collapse; font-size: 14px; margin-top: 28px; max-width: 640px; width: 100%; }
        th { background: #f3f4f6; text-align: left; }
        th, td { border: 1px solid #d1d5db; padding: 12px; }
        td:last-child, th:last-child { text-align: right; width: 140px; }
        @media (max-width: 700px) {
            body { padding: 16px; }
            .toolbar { align-items: flex-start; gap: 16px; }
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
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

    <table>
        <thead>
            <tr>
                <th>Immunization item</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Vaccine doses used</td><td>{{ number_format($vaccineDoseCount) }}</td></tr>
            <tr><td>Vitamin A given</td><td>{{ number_format($vitaminACount) }}</td></tr>
            <tr><td>MNP 90 sachets given</td><td>{{ number_format($mnp90Count) }}</td></tr>
            <tr><td>MNP completed</td><td>{{ number_format($mnpCompletedCount) }}</td></tr>
            <tr><td>FIC recorded</td><td>{{ number_format($ficCount) }}</td></tr>
            <tr><td>CIC recorded</td><td>{{ number_format($cicCount) }}</td></tr>
            <tr><td>Total recorded</td><td>{{ number_format($totalRecorded) }}</td></tr>
            <tr><td>Low birth weight</td><td>{{ number_format($lowBirthWeightCount) }}</td></tr>
        </tbody>
    </table>
</body>
</html>
