@php
    $fullName = trim(implode(' ', array_filter([$memorial->first_name, $memorial->middle_name, $memorial->last_name])));
    $memoryCount = isset($memories) && is_countable($memories)
        ? count($memories)
        : (int) ($memorial->memories_count ?? 0);
    $viewsCount = (int) ($memorial->views ?? 0);
@endphp

<div class="mb-5 sm:mb-6">
    <div class="container mx-auto px-4">
        <div class="relative overflow-hidden rounded-2xl border border-slate-700 bg-slate-800 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.16),_transparent_46%),radial-gradient(circle_at_bottom_left,_rgba(100,116,139,0.22),_transparent_50%),linear-gradient(135deg,_#1e293b_0%,_#111827_55%,_#0b1120_100%)]"></div>
            <div class="absolute -top-28 -left-20 w-72 h-72 rounded-full bg-slate-500/25 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-16 w-80 h-80 rounded-full bg-sky-500/20 blur-3xl"></div>

            @auth
                @if(is_object($memorial) && isset($memorial->user_id) && $memorial->user_id === Auth::id())
                <div class="absolute top-4 left-4 sm:top-6 sm:left-6 z-20">
                    <a href="{{ route('memorial.edit', ['id' => $memorial->id]) }}" class="inline-flex items-center gap-2 rounded-lg border border-white/40 bg-slate-700/60 px-3 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-600/70">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Редактировать
                    </a>
                </div>
                @endif
            @endauth

            @if(isset($memorial->religion) && $memorial->religion === 'orthodox')
            <div class="absolute top-5 right-5 sm:top-7 sm:right-7 z-20">
                <svg viewBox="0 0 24 24" class="w-10 h-10 sm:w-12 sm:h-12 text-white drop-shadow">
                    <path d="M12 2v20M8 6h8M6 10h12M8 18h8" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M12 2L10 4h4l-2-2zM12 22l-2-2h4l-2 2z" fill="currentColor"/>
                </svg>
            </div>
            @endif

            <div class="relative z-10 px-4 pb-5 pt-16 sm:px-7 sm:pb-7 sm:pt-20 lg:px-8 lg:py-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:gap-10">
                    <div class="mx-auto w-full max-w-sm overflow-hidden rounded-2xl border border-white/20 bg-slate-500/20 shadow-lg aspect-square sm:h-[22rem] sm:w-[22rem] lg:mx-0 lg:h-64 lg:w-64">
                        <img
                            src="{{ $memorial->photo ? Storage::url($memorial->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($memorial->first_name . ' ' . $memorial->last_name) . '&size=300&background=e5e7eb&color=6b7280&bold=true' }}"
                            alt="{{ $memorial->first_name }} {{ $memorial->last_name }}"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <div class="min-w-0 flex-1 text-center lg:text-left">
                        <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl">{{ $fullName }}</h1>

                        <div class="mt-4 inline-flex flex-wrap items-center justify-center gap-2 rounded-xl border border-white/45 bg-slate-900/45 px-3 py-2 text-sm sm:text-base lg:justify-start">
                            <span class="font-semibold text-white">{{ $memorial->birth_date->format('d.m.Y') }}</span>
                            <span class="text-white">—</span>
                            <span class="font-semibold text-white">{{ $memorial->death_date->format('d.m.Y') }}</span>
                        </div>

                        @if($memorial->birth_place)
                        <div class="mt-4 flex items-center justify-center gap-2 text-slate-100 lg:justify-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm sm:text-base">{{ expand_region_abbreviations($memorial->birth_place) }}</span>
                        </div>
                        @endif

                        @if($memorial->biography)
                        <p class="mt-4 max-w-3xl text-base italic text-slate-100 sm:text-lg lg:mt-5">{{ $memorial->biography }}</p>
                        @endif

                        <div class="mt-5 flex flex-wrap items-center justify-center gap-2 lg:justify-start">
                            <span class="inline-flex items-center gap-1 rounded-full border border-white/45 bg-slate-900/55 px-3 py-1 text-xs font-semibold text-white">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ $memoryCount }} воспоминаний
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full border border-white/45 bg-slate-900/55 px-3 py-1 text-xs font-semibold text-white">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $viewsCount }} просмотров
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
