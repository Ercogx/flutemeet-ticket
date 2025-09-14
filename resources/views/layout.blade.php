<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen">
    @yield('content')

    <div class="text-center mx-auto max-w-xs scale-100 hover:scale-110 ease-in duration-500 mb-6">
        <a href="https://flutemeet.org/" class="text-gray-600 hover:text-gray-800 transition-colors">
            ← Return To Main Site
        </a>
    </div>
</body>
</html>
