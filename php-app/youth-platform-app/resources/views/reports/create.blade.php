<x-layouts.app title="Submit a Report">
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reports.store') }}">
        @csrf
        <div>
            <label for="content"><strong>Report Details:</strong></label><br>
            <textarea id="content" name="content" rows="8" style="width:100%;" required>{{ old('content') }}</textarea>
        </div>
        <br>
        <button type="submit" class="btn">Submit Report</button>
    </form>
</x-layouts.app>
