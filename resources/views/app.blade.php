@php
    $config = [
        'appName' => config('app.name'),
        'baseUrl' => url('/'),
        'apiUrl' => url('api'),
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('/favicon.png')}}" type="image/png">

    <title>SendPulse</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;600&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style></style>
    @endif
</head>
<body>
    <div id="app">
        <p>{{ $message }}</p>
    </div>

    <!-- Scripts -->
    <script>
        window.config = @json($config);
        console.log('%c%s', 'color: white; background:#009FC1; letter-spacing:20px; font-size:20px;', ' SendPulse ');
    </script>
</body>
