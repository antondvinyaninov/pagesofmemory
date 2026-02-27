<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Страницы памяти')</title>
    @php
        $siteName = project_site_name();
        $defaultDescription = \App\Models\AppSetting::get('general.site_tagline', 'Цифровые страницы памяти о близких');
        $metaDescription = trim($__env->yieldContent('meta_description')) ?: $defaultDescription;
        $metaTitle = trim($__env->yieldContent('meta_title')) ?: trim($__env->yieldContent('title')) ?: $siteName;
        $metaImage = trim($__env->yieldContent('meta_image')) ?: project_icon_url();
        $metaType = trim($__env->yieldContent('meta_type')) ?: 'website';
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    @yield('meta')
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
