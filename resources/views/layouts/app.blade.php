<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Страницы памяти')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('brand/memory-icon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('brand/memory-icon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('brand/memory-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('brand/memory-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Quill Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
</head>
<body class="bg-white">
    @include('partials.header')
    
    <main class="flex-1">
        @yield('content')
    </main>
    
    @include('partials.footer')
</body>
</html>
