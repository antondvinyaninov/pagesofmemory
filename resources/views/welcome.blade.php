@extends('layouts.app')

@section('title', 'Страницы памяти — цифровые страницы памяти о близких')
@section('meta_title', 'Страницы памяти — сервис цифровых памятных страниц о близких')
@section('meta_description', 'Создайте онлайн-страницу памяти близкого человека: фотографии, биография, воспоминания родных и друзей на одном сайте. Доступ для всей семьи из любой точки мира.')
@section('meta_type', 'website')
@section('meta')
    <link rel="canonical" href="{{ url('/') }}">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "{{ project_site_name() }}",
            "url": "{{ url('/') }}",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ url('/?q=') }}{search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }
    </script>
@endsection

@section('content')
<div class="bg-gray-200">
    <div class="container mx-auto px-3 sm:px-4 pt-4 sm:pt-6 pb-10 sm:pb-16">
        <!-- Приветствие -->
        <section class="mb-10 sm:mb-14">
            <div class="relative overflow-hidden rounded-2xl border border-slate-300 bg-slate-800 shadow-xl animate-fade-in">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.15),_transparent_44%),radial-gradient(circle_at_bottom_left,_rgba(148,163,184,0.2),_transparent_50%),linear-gradient(135deg,_#1e293b_0%,_#111827_60%,_#0b1120_100%)]"></div>
                <div class="relative z-10 px-5 py-8 sm:px-10 sm:py-12">
                    <div class="mx-auto max-w-4xl text-center">
                        <img src="{{ project_icon_url() }}" alt="{{ project_site_name() }}" class="mx-auto mb-4 h-10 w-10 object-contain sm:mb-5 sm:h-11 sm:w-11">
                        <h1 class="mb-3 text-3xl font-bold leading-tight text-white sm:text-5xl">Память о близких</h1>
                        <p class="mx-auto max-w-3xl text-base leading-relaxed text-slate-200 sm:text-xl">
                            Сохраните драгоценные воспоминания о ваших близких для будущих поколений
                        </p>
                    </div>

                    @auth
                        <div class="mx-auto mt-7 max-w-3xl rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm sm:mt-8 sm:p-5">
                            <div class="flex flex-col items-center gap-3 text-center sm:flex-row sm:items-center sm:justify-between sm:gap-6 sm:text-left">
                                <div class="sm:flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-300">Ваш профиль</p>
                                    <h2 class="mt-1 text-xl font-semibold text-white sm:text-2xl">С возвращением, {{ auth()->user()->name }}</h2>
                                    <p class="mt-1 text-sm text-slate-200 sm:text-base">Продолжите работу со страницами памяти.</p>
                                </div>
                                <a href="/profile" class="inline-flex items-center justify-center whitespace-nowrap rounded-lg bg-red-500 px-8 py-3 font-medium text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-red-600 hover:shadow-lg sm:shrink-0">
                                    Открыть профиль
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="mx-auto mt-7 max-w-3xl rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm sm:mt-8 sm:p-5">
                            <p class="mb-4 text-center text-sm text-slate-200 sm:text-base">Создайте страницу памяти и пригласите родных делиться воспоминаниями</p>
                            <div class="flex flex-col justify-center gap-3 sm:flex-row">
                                <a href="/login#register" class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-500 px-5 py-3 font-medium text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-red-600 hover:shadow-lg">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    Регистрация
                                </a>
                                <a href="/login" class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-400 bg-white/5 px-5 py-3 font-medium text-red-200 transition-all duration-300 hover:-translate-y-0.5 hover:bg-red-500 hover:text-white hover:shadow-lg">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Войти
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Статистика -->
        <section class="mb-10 sm:mb-16">
            <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
                <div class="flex items-center gap-3 border-b border-slate-700 bg-slate-700 px-5 py-4 sm:px-6">
                    <svg class="h-5 w-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V3m0 8c-1.657 0-3 1.343-3 3v7h6v-7c0-1.657-1.343-3-3-3zm0 0V6m7 8h-2m-8 0H6m12 4h-2m-8 0H6"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-white sm:text-xl">Статистика</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <svg class="mx-auto mb-4 h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="mb-2 text-sm text-gray-500">Фотографий</div>
                            <div class="text-xl font-semibold leading-none text-slate-700 sm:text-2xl">{{ number_format($stats['photos'], 0, ',', ' ') }}</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <svg class="mx-auto mb-4 h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <div class="mb-2 text-sm text-gray-500">Страниц памяти</div>
                            <div class="text-xl font-semibold leading-none text-slate-700 sm:text-2xl">{{ number_format($stats['memorials'], 0, ',', ' ') }}</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <svg class="mx-auto mb-4 h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <div class="mb-2 text-sm text-gray-500">Пользователей</div>
                            <div class="text-xl font-semibold leading-none text-slate-700 sm:text-2xl">{{ number_format($stats['users'], 0, ',', ' ') }}</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <svg class="mx-auto mb-4 h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <div class="mb-2 text-sm text-gray-500">Воспоминаний</div>
                            <div class="text-xl font-semibold leading-none text-slate-700 sm:text-2xl">{{ number_format($stats['memories'], 0, ',', ' ') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Как это работает -->
        <section class="mb-10 sm:mb-16">
            <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
                <div class="flex items-center gap-3 border-b border-slate-700 bg-slate-700 px-5 py-4 sm:px-6">
                    <svg class="h-5 w-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4-4h.01M6.938 4h10.124c1.657 0 3 1.343 3 3v10c0 1.657-1.343 3-3 3H6.938c-1.657 0-3-1.343-3-3V7c0-1.657 1.343-3 3-3z"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-white sm:text-xl">Как это работает</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <p class="mx-auto mb-6 max-w-2xl text-center text-sm text-gray-500 sm:mb-8 sm:text-base">
                        Простые шаги для создания страницы памяти о ваших близких
                    </p>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 sm:gap-5">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-50">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-slate-700">1. Регистрация</h3>
                            <p class="text-sm leading-relaxed text-gray-500">
                                Создайте аккаунт и получите доступ к созданию страниц памяти
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-50">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-slate-700">2. Загрузка</h3>
                            <p class="text-sm leading-relaxed text-gray-500">
                                Добавьте фотографии, видео и напишите историю жизни близкого человека
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-50">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-slate-700">3. Поделиться</h3>
                            <p class="text-sm leading-relaxed text-gray-500">
                                Пригласите родных и друзей делиться воспоминаниями и фотографиями
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5 text-center sm:mt-8">
                        <div class="mx-auto max-w-3xl">
                            <h3 class="mb-3 text-lg font-semibold text-slate-700 sm:text-xl">Сохраните память навсегда</h3>
                            <p class="mb-5 text-sm text-gray-500 sm:text-base">
                                Все данные надежно защищены и сохранены для будущих поколений.
                                Создайте цифровое наследие, которое останется с вашей семьей навсегда.
                            </p>
                            <div class="flex flex-wrap justify-center gap-4">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <div class="h-2 w-2 rounded-full bg-green-500"></div>
                                    Безопасное хранение
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <div class="h-2 w-2 rounded-full bg-green-500"></div>
                                    Неограниченные фото
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <div class="h-2 w-2 rounded-full bg-green-500"></div>
                                    Доступ всей семье
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Последние страницы памяти -->
        <section>
            <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
                <div class="flex items-center gap-3 border-b border-slate-700 bg-slate-700 px-5 py-4 sm:px-6">
                    <svg class="h-5 w-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-white sm:text-xl">Последние страницы памяти</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <p class="mb-5 text-sm text-gray-500 sm:mb-6 sm:text-base">
                        Здесь отображаются недавно созданные или обновлённые страницы памяти. Используйте поиск выше, чтобы быстро найти нужного человека.
                    </p>

                    @if(isset($recentMemorials) && $recentMemorials->count() > 0)
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                            @foreach($recentMemorials as $memorial)
                                @php
                                    $fullName = trim(implode(' ', array_filter([
                                        $memorial->last_name,
                                        $memorial->first_name,
                                        $memorial->middle_name,
                                    ])));

                                    if ($fullName === '') {
                                        $fullName = 'Без имени';
                                    }

                                    $birthYear = $memorial->birth_date?->format('Y');
                                    $deathYear = $memorial->death_date?->format('Y');

                                    if ($birthYear || $deathYear) {
                                        $lifeRange = ($birthYear ?: '—') . ' - ' . ($deathYear ?: '—');
                                    } else {
                                        $lifeRange = 'Даты не указаны';
                                    }

                                    $photoUrl = null;
                                    if (!empty($memorial->photo)) {
                                        $photoUrl = filter_var($memorial->photo, FILTER_VALIDATE_URL)
                                            ? $memorial->photo
                                            : s3_url($memorial->photo);
                                    }

                                    $memoriesCount = (int) ($memorial->memories_count ?? 0);
                                    $viewsCount = (int) ($memorial->views ?? 0);

                                    $memoriesWord = match (true) {
                                        $memoriesCount % 10 === 1 && $memoriesCount % 100 !== 11 => 'воспоминание',
                                        in_array($memoriesCount % 10, [2, 3, 4], true) && !in_array($memoriesCount % 100, [12, 13, 14], true) => 'воспоминания',
                                        default => 'воспоминаний',
                                    };
                                @endphp

                                <article class="relative overflow-hidden rounded-xl bg-slate-700 shadow-xl transition-shadow duration-300 hover:shadow-2xl group">
                                    <a href="{{ route('memorial.show', ['id' => $memorial->id]) }}" class="block">
                                        <div class="relative aspect-[4/3] bg-slate-600 overflow-hidden">
                                            @if($photoUrl)
                                                <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                                    <svg class="h-16 w-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/45 to-transparent"></div>

                                            <div class="absolute top-3 right-3 text-red-400">
                                                <svg class="w-8 h-8 drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </div>

                                            <div class="absolute bottom-4 left-4 right-4">
                                                <h4 class="text-lg font-bold leading-tight text-white break-words sm:text-xl">
                                                    {{ $fullName }}
                                                </h4>
                                                <p class="mt-1 text-sm font-semibold text-red-300 sm:text-base">{{ $lifeRange }}</p>
                                            </div>
                                        </div>

                                        <div class="bg-gradient-to-br from-slate-700 to-slate-800 p-4 text-white">
                                            @if($memorial->birth_place)
                                                <div class="mb-3 flex items-center gap-2 text-sm text-slate-200">
                                                    <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span class="truncate">{{ expand_region_abbreviations($memorial->birth_place) }}</span>
                                                </div>
                                            @endif

                                            <div class="flex items-center justify-between gap-2">
                                                <span class="inline-flex items-center rounded-full bg-red-500/90 px-2.5 py-1 text-xs font-semibold">
                                                    {{ $memoriesCount }} {{ $memoriesWord }}
                                                </span>
                                                <span class="inline-flex items-center gap-1 text-xs text-slate-200">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    {{ $viewsCount }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center text-gray-500">
                            Пока нет опубликованных страниц памяти.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
