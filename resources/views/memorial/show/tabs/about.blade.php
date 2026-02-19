@php
    $religionLabels = [
        'orthodox' => 'Православие',
        'catholic' => 'Католицизм',
        'islam' => 'Ислам',
        'judaism' => 'Иудаизм',
        'buddhism' => 'Буддизм',
        'hinduism' => 'Индуизм',
        'other' => 'Другое',
    ];
    $religionLabel = $religionLabels[$memorial->religion ?? ''] ?? null;

    $militaryConflictLabels = [
        'ww2' => 'Великая Отечественная война (1941-1945)',
        'afghanistan' => 'Афганская война (1979-1989)',
        'chechnya_1' => 'Первая чеченская война (1994-1996)',
        'chechnya_2' => 'Вторая чеченская война (1999-2009)',
        'georgia' => 'Война в Южной Осетии (2008)',
        'syria' => 'Сирийский конфликт (2015-н.в.)',
        'ukraine' => 'Специальная военная операция (2022-н.в.)',
    ];

    $militaryConflicts = collect($memorial->military_conflicts ?? [])
        ->filter(fn ($value) => is_string($value) && trim($value) !== '')
        ->map(fn ($value) => trim($value))
        ->unique()
        ->map(fn ($value) => $militaryConflictLabels[$value] ?? $value)
        ->values();

    $militaryFiles = collect($memorial->military_files ?? [])
        ->filter(fn ($item) => is_array($item))
        ->map(function ($item) {
            $path = trim((string) ($item['path'] ?? ''));
            if ($path === '') {
                return null;
            }

            $isPdf = strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
            $url = filter_var($path, FILTER_VALIDATE_URL) ? $path : s3_url($path);
            if (!is_string($url) || trim($url) === '') {
                return null;
            }

            return [
                'title' => trim((string) ($item['title'] ?? '')),
                'url' => $url,
                'is_pdf' => $isPdf,
            ];
        })
        ->filter()
        ->values();

    $achievementFiles = collect($memorial->achievement_files ?? [])
        ->filter(fn ($item) => is_array($item))
        ->map(function ($item) {
            $path = trim((string) ($item['path'] ?? ''));
            if ($path === '') {
                return null;
            }

            $isPdf = strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
            $url = filter_var($path, FILTER_VALIDATE_URL) ? $path : s3_url($path);
            if (!is_string($url) || trim($url) === '') {
                return null;
            }

            return [
                'title' => trim((string) ($item['title'] ?? '')),
                'url' => $url,
                'is_pdf' => $isPdf,
            ];
        })
        ->filter()
        ->values();

    $fullBiographyText = trim((string) strip_tags(str_ireplace(
        ['</p>', '<br>', '<br/>', '<br />'],
        ["\n\n", "\n", "\n", "\n"],
        (string) ($memorial->full_biography ?? '')
    )));
    $hasBiography = $fullBiographyText !== '';

    $hasFacts = $memorial->birth_date || $memorial->education || $memorial->career || $religionLabel;
    $hasMilitary = $memorial->military_service || $memorial->military_rank || $memorial->military_years || $memorial->military_details || $militaryConflicts->isNotEmpty() || $militaryFiles->isNotEmpty();
    $hasMemorialMedia = (isset($memorialGalleryPhotos) && count($memorialGalleryPhotos) > 0) || (isset($memorialGalleryVideos) && count($memorialGalleryVideos) > 0);

    $achievementsText = trim((string) strip_tags(str_ireplace(
        ['</p>', '<br>', '<br/>', '<br />'],
        ["\n\n", "\n", "\n", "\n"],
        (string) ($memorial->achievements ?? '')
    )));
    $hasAchievements = $achievementsText !== '' || $achievementFiles->isNotEmpty();
@endphp

<section class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
    <header class="border-b border-slate-700 bg-slate-700 px-4 py-4 sm:px-6">
        <h2 class="flex items-center gap-2 text-base font-semibold text-white sm:text-lg">
            <svg class="h-4 w-4 text-sky-200 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            О человеке
        </h2>
    </header>
</section>

<div class="mt-4 space-y-5">
        @if($hasBiography)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h7l7 7v9a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v4a2 2 0 002 2h4"></path></svg>
                </span>
                История жизни
            </h3>
            <div class="px-1 sm:px-1">
                <p class="whitespace-pre-line text-base leading-8 text-slate-700">{{ $fullBiographyText }}</p>
            </div>
        </section>
        @endif

        @if(isset($memorialGalleryPhotos) && count($memorialGalleryPhotos) > 0)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
                Фотографии
                <span class="ml-auto rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ count($memorialGalleryPhotos) }}</span>
            </h3>
            
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                @foreach($memorialGalleryPhotos as $photo)
                <button type="button" class="group relative block cursor-pointer overflow-hidden rounded-xl shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" onclick="openPhotoModal('{{ $photo['url'] }}')">
                    <div class="aspect-square overflow-hidden bg-gray-100">
                        <img src="{{ $photo['url'] }}" alt="Фото мемориала" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    <div class="absolute bottom-2 right-2 rounded-full bg-white/90 p-2 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        <svg class="h-4 w-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </button>
                @endforeach
            </div>
        </section>
        @endif

        @if(isset($memorialGalleryVideos) && count($memorialGalleryVideos) > 0)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </span>
                Видео
                <span class="ml-auto rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ count($memorialGalleryVideos) }}</span>
            </h3>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($memorialGalleryVideos as $video)
                <div class="group relative overflow-hidden rounded-xl bg-gray-900 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="aspect-video">
                        <video src="{{ $video['url'] }}" controls preload="metadata" class="h-full w-full object-cover"></video>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if($memorial->education_details)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </span>
                Образование
            </h3>
            
            @php
                $educations = collect(explode(';', $memorial->education_details))
                    ->map(function($item) {
                        $parts = explode(':', $item, 2);
                        return [
                            'name' => trim($parts[0] ?? ''),
                            'details' => trim($parts[1] ?? ''),
                        ];
                    })
                    ->filter(fn($item) => $item['name'] !== '' || $item['details'] !== '')
                    ->values();
            @endphp
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @foreach($educations as $education)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-800">{{ $education['name'] }}</p>
                    @if($education['details'] !== '')
                    <p class="mt-1 text-sm text-gray-600">{{ $education['details'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if($memorial->career_details)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </span>
                Карьера
            </h3>
            
            @php
                $workplaces = collect(explode(';', $memorial->career_details))
                    ->map(function($item) {
                        $parts = explode(':', $item, 2);
                        return [
                            'position' => trim($parts[0] ?? ''),
                            'details' => trim($parts[1] ?? ''),
                        ];
                    })
                    ->filter(fn($item) => $item['position'] !== '' || $item['details'] !== '')
                    ->values();
            @endphp
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @foreach($workplaces as $workplace)
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-800">{{ $workplace['position'] }}</p>
                    @if($workplace['details'] !== '')
                    <p class="mt-1 text-sm text-gray-600">{{ $workplace['details'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if($hasMilitary)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4l7 3.5v5c0 4.2-2.9 8.1-7 9.5-4.1-1.4-7-5.3-7-9.5v-5L12 4z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 12l1.8 1.8L14.5 10.6"></path>
                </svg>
                </span>
                Военная служба
            </h3>

            <dl class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @if($memorial->military_service)
                <div class="px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Место службы</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-700">{{ $memorial->military_service }}</dd>
                </div>
                @endif

                @if($memorial->military_rank)
                <div class="px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Звание</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-700">{{ $memorial->military_rank }}</dd>
                </div>
                @endif

                @if($memorial->military_years)
                <div class="px-4 py-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Годы службы</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-700">{{ $memorial->military_years }}</dd>
                </div>
                @endif
            </dl>

            @if($militaryConflicts->isNotEmpty())
            <div class="px-4 py-3">
                <h4 class="mb-2 text-sm font-semibold text-slate-700">Участие в военных конфликтах</h4>
                <ul class="flex flex-wrap gap-2">
                    @foreach($militaryConflicts as $conflict)
                    <li><span class="inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1 text-xs text-gray-700 sm:text-sm">{{ $conflict }}</span></li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($memorial->military_details)
            <div class="px-4 py-3">
                <h4 class="mb-2 text-sm font-semibold text-slate-700">Дополнительная информация</h4>
                <p class="whitespace-pre-line text-sm text-gray-700">{{ $memorial->military_details }}</p>
            </div>
            @endif

            @if($militaryFiles->isNotEmpty())
            <div class="space-y-2">
                <h4 class="text-sm font-semibold text-slate-700">Документы и награды</h4>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($militaryFiles as $file)
                    <a href="{{ $file['url'] }}" target="_blank" rel="noopener" class="group block">
                        <div class="aspect-[3/4] overflow-hidden rounded-lg border border-slate-300 bg-white shadow-md">
                            @if(!$file['is_pdf'])
                            <img src="{{ $file['url'] }}" alt="{{ $file['title'] ?: 'Документ' }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            @else
                            <div class="flex h-full w-full flex-col items-center justify-center bg-gray-50 text-sky-400">
                                <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                    <path d="M14 2v6h6M9 13h6M9 17h6M9 9h2" fill="#fff"/>
                                </svg>
                                <span class="mt-2 text-xs font-semibold">PDF</span>
                            </div>
                            @endif
                        </div>
                        @if($file['title'] !== '')
                        <p class="mt-1 line-clamp-2 text-xs text-gray-600">{{ $file['title'] }}</p>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </section>
        @endif

        @if($hasAchievements)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.2 0-4 1.8-4 4 0 2.21 1.79 4 4 4s4-1.79 4-4c0-2.2-1.8-4-4-4z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2m10-10h-2M4 12H2m16.95 6.95l-1.41-1.41M6.46 6.46L5.05 5.05m13.9 0l-1.41 1.41M6.46 17.54l-1.41 1.41"></path></svg>
                </span>
                Достижения
            </h3>

            @if($achievementsText !== '')
            <p class="whitespace-pre-line px-1 text-sm text-gray-700">{{ $achievementsText }}</p>
            @endif

            @if($achievementFiles->isNotEmpty())
            <div class="space-y-2">
                <h4 class="text-sm font-semibold text-slate-700">Фото и документы достижений</h4>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($achievementFiles as $file)
                    <a href="{{ $file['url'] }}" target="_blank" rel="noopener" class="group block">
                        <div class="aspect-[3/4] overflow-hidden rounded-lg border border-slate-300 bg-white shadow-md">
                            @if(!$file['is_pdf'])
                            <img src="{{ $file['url'] }}" alt="{{ $file['title'] ?: 'Документ' }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                            @else
                            <div class="flex h-full w-full flex-col items-center justify-center bg-gray-50 text-sky-400">
                                <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                    <path d="M14 2v6h6M9 13h6M9 17h6M9 9h2" fill="#fff"/>
                                </svg>
                                <span class="mt-2 text-xs font-semibold">PDF</span>
                            </div>
                            @endif
                        </div>
                        @if($file['title'] !== '')
                        <p class="mt-1 line-clamp-2 text-xs text-gray-600">{{ $file['title'] }}</p>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </section>
        @endif

        @if($memorial->hobbies || $memorial->character_traits)
        <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-6.518 6.518a2.121 2.121 0 01-3 0 2.121 2.121 0 010-3l6.518-6.518M16 5l3 3m0 0l-9 9"></path></svg>
                </span>
                Характер и увлечения
            </h3>
            <div class="grid gap-3 sm:grid-cols-2">
                @if($memorial->hobbies)
                <div class="px-4 py-3">
                    <h4 class="mb-2 text-sm font-semibold text-slate-700">Увлечения</h4>
                    <ul class="space-y-1.5">
                        @foreach(explode("\n", $memorial->hobbies) as $hobby)
                            @if(trim($hobby))
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                <span>{{ trim($hobby) }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($memorial->character_traits)
                <div class="px-4 py-3">
                    <h4 class="mb-2 text-sm font-semibold text-slate-700">Черты характера</h4>
                    <ul class="space-y-1.5">
                        @foreach(explode("\n", $memorial->character_traits) as $trait)
                            @if(trim($trait))
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <span class="mt-1.5 h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                <span>{{ trim($trait) }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </section>
        @endif
</div>
