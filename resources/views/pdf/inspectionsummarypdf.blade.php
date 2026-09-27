<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #212529; }
        h2, h3 { margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { font-size: 16px; }
        .header h3 { font-size: 12px; font-weight: normal; margin-top: 2px; }
        .meta { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
        .meta td { padding: 3px 6px; font-size: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #333; padding: 4px; font-size: 9px; text-align: left; }
        table.data th { background-color: #e9ecef; font-weight: bold; }
        .text-muted { color: #6c757d; }
        .footer { margin-top: 10px; font-size: 8px; color: #6c757d; text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <h2>B &amp; R SPICES NIGERIA LTD</h2>
    <h3>INSPECTION SUMMARY REPORT</h3>
</div>

<table class="meta">
    <tr>
        <td><b>Season:</b> {{ $season }}</td>
        <td><b>Report Name:</b> {{ $reportname->reportname }}</td>
        <td><b>Report State:</b> {{ $state }}</td>
        <td><b>Total Records:</b> {{ $internalinspection->count() }}</td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            @foreach ($selectedColumns as $key)
                <th>{{ $availableColumns[$key]['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($internalinspection as $inspection)
            <tr>
                @foreach ($selectedColumns as $key)
                    <td>{{ ($availableColumns[$key]['value'])($inspection, $season) }}</td>
                @endforeach
            </tr>
        @empty
            <tr><td colspan="{{ max(count($selectedColumns), 1) }}" class="text-muted">No records found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">Generated {{ now()->format('Y-m-d H:i') }}</div>

</body>
</html>
