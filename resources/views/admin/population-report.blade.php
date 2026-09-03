<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Population Profile Report</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #1f2937; font-family: Arial, sans-serif; margin: 0; padding: 28px; }
        .toolbar { align-items: center; display: flex; justify-content: space-between; margin-bottom: 24px; }
        h1 { font-size: 24px; margin: 0 0 6px; }
        p { color: #6b7280; font-size: 13px; margin: 0; }
        button { background: #4f46e5; border: 0; border-radius: 6px; color: white; cursor: pointer; font-size: 13px; font-weight: 600; padding: 10px 14px; }
        table { border-collapse: collapse; font-size: 13px; margin-top: 28px; width: 100%; }
        th { background: #f3f4f6; text-align: left; }
        th, td { border: 1px solid #d1d5db; padding: 10px 12px; }
        td:last-child, th:last-child { text-align: right; width: 120px; }
        @media (max-width: 700px) { body { padding: 16px; } .toolbar { align-items: flex-start; gap: 12px; } }
        @media print { body { padding: 0; } .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <div>
            <h1>Population Profile Report</h1>
            <p>Generated {{ now()->format('F d, Y') }}</p>
        </div>
        <button type="button" class="no-print" onclick="window.print()">Print report</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Group</th>
                <th>Category</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ageLists as $label => $residents)
                <tr>
                    <td>Age</td>
                    <td>{{ $label }}</td>
                    <td>{{ number_format($residents->count()) }}</td>
                </tr>
            @endforeach
            @foreach($categoryLists as $groupLabel => $categories)
                @foreach($categories as $label => $residents)
                    <tr>
                        <td>{{ $groupLabel }}</td>
                        <td>{{ $label }}</td>
                        <td>{{ number_format($residents->count()) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
