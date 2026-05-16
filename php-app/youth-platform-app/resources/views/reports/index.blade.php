<!DOCTYPE html>
<html>
<head>
    <title>Report List</title>
    <meta charset="utf-8">
    <style>
        body {font-family: Arial, sans-serif; margin: 2rem;}
        table {width: 100%; border-collapse: collapse;}
        th, td {border: 1px solid #ddd; padding: 8px; text-align: left;}
        tr.priority {background-color: #ffdddd;}
    </style>
</head>
<body>
    <h1>Submitted Reports</h1>
    <a href="{{ route('reports.create') }}">Submit New Report</a>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Content</th>
                <th>Risk Level</th>
                <th>Category</th>
                <th>Urgency Score</th>
                <th>Priority</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                @php
                    $rowClass = in_array($report->risk_level, ['HIGH', 'CRITICAL']) ? 'priority' : '';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $report->id }}</td>
                    <td>{{ Str::limit($report->content, 50) }}</td>
                    <td>{{ $report->risk_level }}</td>
                    <td>{{ $report->category ?? '-' }}</td>
                    <td>{{ $report->urgency_score ?? '-' }}</td>
                    <td>{{ $report->is_priority ? 'YES' : 'NO' }}</td>
                    <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reports->links() }}
</body>
</html>
