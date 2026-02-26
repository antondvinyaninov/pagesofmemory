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

    @php
        $gtmId = \App\Models\AppSetting::get('analytics.gtm_id', '');
    @endphp
    
    @if($gtmId)
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    <!-- End Google Tag Manager -->
    @endif
</head>
<body class="bg-white">
    @if($gtmId)
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif

    @include('partials.header')
    
    <main class="flex-1">
        @yield('content')
    </main>
    
    @include('partials.footer')
</body>
</html>
