<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Report')</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            background: #fff;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .pdf-container {
            padding: 32px 32px 24px 32px;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #1a202c;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            font-size: 13px;
        }
        th {
            background: #f7fafc;
            font-weight: bold;
        }
        .section {
            margin-bottom: 32px;
        }
    </style>
    @yield('pdf-head')
</head>
<body>
    <div class="pdf-container">
        @yield('content')
    </div>
</body>
</html>
