<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Админ-панель</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('brand/memory-icon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('brand/memory-icon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('brand/memory-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('brand/memory-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Боковое меню -->
        <aside class="w-64 bg-slate-800 text-white hidden lg:block">
            <div class="p-6">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <img src="{{ project_icon_url() }}" alt="{{ project_site_name() }}" class="w-7 h-7 rounded-md object-contain">
                    <span>Админ-панель</span>
                </h2>
                <p class="text-sm text-gray-400 mt-1">{{ auth()->user()->name }}</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.index') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.index') ? 'bg-slate-700 border-l-4 border-red-500' : 'hover:bg-slate-700' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Главная
                </a>
                
                <a href="{{ route('admin.users') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users') ? 'bg-slate-700 border-l-4 border-red-500' : 'hover:bg-slate-700' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Пользователи
                </a>
                
                <a href="{{ route('admin.memorials') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.memorials') ? 'bg-slate-700 border-l-4 border-red-500' : 'hover:bg-slate-700' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Мемориалы
                </a>
                
                <a href="{{ route('admin.analytics') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.analytics') ? 'bg-slate-700 border-l-4 border-red-500' : 'hover:bg-slate-700' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Метрики
                </a>
                
                <a href="{{ route('admin.seo') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.seo') ? 'bg-slate-700 border-l-4 border-red-500' : 'hover:bg-slate-700' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    SEO
                </a>
                
                <a href="{{ route('admin.newsletter') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.newsletter') ? 'bg-slate-700 border-l-4 border-red-500' : 'hover:bg-slate-700' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Рассылки
                </a>
                
                <a href="{{ route('admin.settings') }}" class="flex items-center px-6 py-3 {{ request()->routeIs('admin.settings') ? 'bg-slate-700 border-l-4 border-red-500' : 'hover:bg-slate-700' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Настройки
                </a>
                
                <div class="border-t border-slate-700 my-4"></div>
                
                <a href="{{ route('profile') }}" class="flex items-center px-6 py-3 hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Мой профиль
                </a>
                
                <a href="/" class="flex items-center px-6 py-3 hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    На сайт
                </a>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center px-6 py-3 hover:bg-slate-700 transition-colors w-full text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Выход
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Мобильное меню -->
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-slate-800 text-white z-50 border-t border-slate-700">
            <nav class="flex justify-around py-2 overflow-x-auto">
                <a href="{{ route('admin.index') }}" class="flex flex-col items-center px-3 py-2 {{ request()->routeIs('admin.index') ? 'text-red-500' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs mt-1">Главная</span>
                </a>
                
                <a href="{{ route('admin.users') }}" class="flex flex-col items-center px-3 py-2 {{ request()->routeIs('admin.users') ? 'text-red-500' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-xs mt-1">Пользователи</span>
                </a>
                
                <a href="{{ route('admin.memorials') }}" class="flex flex-col items-center px-3 py-2 {{ request()->routeIs('admin.memorials') ? 'text-red-500' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-xs mt-1">Мемориалы</span>
                </a>
                
                <a href="{{ route('admin.analytics') }}" class="flex flex-col items-center px-3 py-2 {{ request()->routeIs('admin.analytics') ? 'text-red-500' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-xs mt-1">Метрики</span>
                </a>
                
                <a href="{{ route('admin.settings') }}" class="flex flex-col items-center px-3 py-2 {{ request()->routeIs('admin.settings') ? 'text-red-500' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs mt-1">Настройки</span>
                </a>
            </nav>
        </div>

        <!-- Основной контент -->
        <main class="flex-1 overflow-y-auto pb-20 lg:pb-0">
            <!-- Верхний хедер -->
            <div class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">@yield('title')</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile') }}" class="flex-shrink-0">
                            <img 
                                src="{{ avatar_url(auth()->user()) }}" 
                                alt="{{ auth()->user()->name }}" 
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-gray-200 hover:border-red-500 transition-colors">
                        </a>
                    </div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>
</body>
</html>
