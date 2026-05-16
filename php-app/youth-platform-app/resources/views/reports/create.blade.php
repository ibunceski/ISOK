<!DOCTYPE html>
<html>
<head>
    <title>Submit Report</title>
    <meta charset="utf-8">
    <style>
        body {font-family: Arial, sans-serif; margin: 2rem;}
        .error {color: red;}
    </style>
</head>
<body>
    <h1>Submit a Safety Report</h1>

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
            <label for="content">Report Details:</label><br>
            <textarea id="content" name="content" rows="6" cols="60" required>{{ old('content') }}</textarea>
        </div>
        <br>
        <button type="submit">Submit Report</button>
    </form>

    <br>
    <a href="{{ route('reports.index') }}">View All Reports</a>
</body>
</html>
