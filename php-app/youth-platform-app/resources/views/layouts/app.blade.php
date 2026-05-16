<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Youth Safety Platform' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {font-family: Arial, Helvetica, sans-serif; background:#f9f9f9; margin:0; padding:0;}
        .container {max-width: 800px; margin: 2rem auto; background:#fff; padding:2rem; box-shadow:0 2px 8px rgba(0,0,0,0.1);}
        header {margin-bottom:2rem;}
        nav a {margin-right:1rem; text-decoration:none; color:#2c3e50;}
        nav a:hover {text-decoration:underline;}
        .error {color:#e74c3c; margin-bottom:1rem;}
        table {width:100%; border-collapse:collapse; margin-top:1rem;}
        th, td {border:1px solid #ddd; padding:8px; text-align:left;}
        th {background:#f2f2f2;}
        tr.priority {background:#ffebee;}
        .btn {display:inline-block; padding:0.5rem 1rem; background:#3498db; color:#fff; text-decoration:none; border-radius:4px;}
        .btn:hover {background:#2980b9;}
        .footer {margin-top:2rem; text-align:center; font-size:0.9rem; color:#777;}
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Youth Safety Reporting Platform</h1>
            <nav>
                <a href="{{ route('reports.create') }}">Submit Report</a>
                <a href="{{ route('reports.index') }}">View Reports</a>
            </nav>
        </header>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        {{ $slot }}

        <div class="footer">
            &copy; {{ date('Y') }} Youth Safety Platform
        </div>
    </div>
</body>
</html>
