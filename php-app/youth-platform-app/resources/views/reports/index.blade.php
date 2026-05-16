<x-layouts.app title="Report List">
    <h2>Submitted Reports</h2>

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
            @forelse ($reports as $report)
                @php
                    $rowClass = in_array($report->risk_level, ['HIGH', 'CRITICAL']) ? 'priority' : '';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>{{ $report->id }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($report->content, 60) }}</td>
                    <td>{{ $report->risk_level }}</td>
                    <td>{{ $report->category ?? '-' }}</td>
                    <td>{{ $report->urgency_score !== null ? number_format($report->urgency_score, 2) : '-' }}</td>
                    <td>{{ $report->is_priority ? 'YES' : 'NO' }}</td>
                    <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No reports found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:1rem;">
        {{ $reports->links() }}
    </div>
</x-layouts.app>
