@extends('layouts.app')

@section('title', $user->name . ' - Профиль')

@section('content')
<div class="bg-gray-200 min-h-screen py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Основной контент -->
            <main class="flex-1 space-y-6">
                <!-- Созданные мемориалы -->
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl font-semibold text-slate-700 mb-4">Созданные мемориалы</h2>
                    
                    @if($user->memorials && $user->memorials->count() > 0)
                        <div class="space-y-6">
                            @foreach($user->memorials as $memorial)
                                <div class="flex flex-col xl:flex-row gap-4">
                                    <!-- Hero блок мемориала -->
                                    <div class="flex-1 min-w-0 relative bg-slate-700 text-white rounded-xl overflow-hidden shadow-xl">
                                        <div class="absolute inset-0 bg-gradient-to-br from-slate-700/95 to-slate-800/85"></div>
                                        
                                        <!-- Религиозный символ -->
                                        @if($memorial->religion === 'orthodox')
                                        <div class="absolute top-6 right-6 z-10">
                                            <svg viewBox="0 0 24 24" class="w-12 h-12 fill-red-400 drop-shadow-lg">
                                                <path d="M12 2v20M8 6h8M6 10h12M8 18h8" stroke="currentColor" stroke-width="2" fill="none"/>
                                                <path d="M12 2L10 4h4l-2-2zM12 22l-2-2h4l-2 2z" fill="currentColor"/>
                                            </svg>
                                        </div>
                                        @endif
                                        
                                        <!-- Статус черновика -->
                                        @if($memorial->status === 'draft')
                                        <div class="absolute top-6 right-6 z-10">
                                            <span class="px-3 py-1.5 bg-yellow-400 text-yellow-900 text-sm font-semibold rounded-lg shadow-lg">
                                                Черновик
                                            </span>
                                        </div>
                                        @endif
                                        
                                        <a href="{{ route('memorial.show', ['id' => $memorial->id]) }}" class="block relative p-4 sm:p-6 hover:bg-slate-600/20 transition-colors">
                                            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                                                <!-- Фото -->
                                                <div class="w-full max-w-[260px] sm:max-w-[280px] md:w-64 md:h-64 aspect-square bg-gray-300 rounded-xl overflow-hidden flex-shrink-0 shadow-lg">
                                                    @if($memorial->photo)
                                                        <img src="{{ Storage::disk('s3')->url($memorial->photo) }}" alt="{{ $memorial->first_name }} {{ $memorial->last_name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                                            <svg class="w-24 h-24 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Информация -->
                                                <div class="flex-1 min-w-0 text-center md:text-left">
                                                    <h3 class="text-2xl md:text-3xl font-bold mb-3 leading-tight break-words">
                                                        {{ $memorial->last_name }} {{ $memorial->first_name }} {{ $memorial->middle_name }}
                                                    </h3>
                                                    
                                                    <!-- Даты жизни -->
                                                    <div class="mb-4">
                                                        <div class="text-lg md:text-xl font-bold text-red-400">
                                                            {{ $memorial->birth_date->format('d.m.Y') }}
                                                            <span class="mx-3">—</span>
                                                            {{ $memorial->death_date->format('d.m.Y') }}
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Место рождения -->
                                                    @if($memorial->birth_place)
                                                    <div class="mb-4 flex items-center justify-center md:justify-start gap-2">
                                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <span class="text-sm md:text-base opacity-90 break-words">{{ expand_region_abbreviations($memorial->birth_place) }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($memorial->biography)
                                                    <div class="mb-3">
                                                        <div class="text-base md:text-lg font-semibold text-white italic break-words">
                                                            {{ $memorial->biography }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <!-- Блок управления (только для владельца) -->
                                    @auth
                                        @if(Auth::id() === $user->id)
                                        <div class="w-full xl:w-72 flex-shrink-0">
                                            <div class="bg-white rounded-xl shadow-md overflow-hidden xl:sticky xl:top-4">
                                                <!-- Заголовок -->
                                                <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-5 py-4">
                                                    <h4 class="text-base font-bold text-white flex items-center gap-2">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                                        </svg>
                                                        Управление
                                                    </h4>
                                                </div>
                                                
                                                <!-- Кнопки -->
                                                <div class="p-4 space-y-3">
                                                    <a href="{{ route('memorial.show', ['id' => $memorial->id]) }}" class="flex items-center gap-3 w-full bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-4 py-3 rounded-lg transition-all shadow-md hover:shadow-lg text-sm font-semibold group">
                                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        <span>Просмотр</span>
                                                    </a>
                                                    
                                                    <a href="{{ route('memorial.edit', ['id' => $memorial->id]) }}" class="flex items-center gap-3 w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg transition-all shadow-md hover:shadow-lg text-sm font-semibold group">
                                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        <span>Редактировать</span>
                                                    </a>
                                                    
                                                    <!-- Статистика -->
                                                    <div class="pt-3 mt-3 border-t border-gray-200">
                                                        <div class="space-y-2 text-sm">
                                                            <div class="flex items-center justify-between text-gray-600">
                                                                <span class="flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                    </svg>
                                                                    Просмотры
                                                                </span>
                                                                <span class="font-semibold text-gray-800">{{ $memorial->views ?? 0 }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between text-gray-600">
                                                                <span class="flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                                    </svg>
                                                                    Воспоминания
                                                                </span>
                                                                <span class="font-semibold text-gray-800">{{ $memorial->memories->count() ?? 0 }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                            
                            <!-- Добавить новый мемориал (только для владельца) -->
                            @auth
                                @if(Auth::id() === $user->id)
                                <a href="{{ route('memorial.create') }}" class="block border-2 border-dashed border-red-300 hover:border-red-500 rounded-xl p-5 sm:p-8 transition-colors group bg-red-50/50 hover:bg-red-50">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <div class="w-12 h-12 bg-red-100 group-hover:bg-red-200 rounded-full flex items-center justify-center mb-3 transition-colors">
                                            <svg class="w-6 h-6 text-red-500 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-semibold text-gray-700 group-hover:text-red-600 mb-1 transition-colors">Создать новый мемориал</h3>
                                        <p class="text-sm text-gray-500">Нажмите, чтобы добавить страницу памяти</p>
                                    </div>
                                </a>
                                @endif
                            @endauth
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">Пока нет созданных мемориалов</p>
                            @auth
                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('memorial.create') }}" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Создать мемориал
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- Оставленные воспоминания -->
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl font-semibold text-slate-700 mb-6">Оставленные воспоминания</h2>
                    @if($user->memories && $user->memories->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->memories as $memory)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all">
                                    <div class="flex items-start gap-4">
                                        <!-- Фото мемориала -->
                                        @if($memory->memorial && $memory->memorial->photo)
                                        <a href="{{ route('memorial.show', ['id' => $memory->memorial->id]) }}" class="flex-shrink-0">
                                            <div class="w-20 h-20 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                                                <img src="{{ Storage::disk('s3')->url($memory->memorial->photo) }}" alt="" class="w-full h-full object-cover">
                                            </div>
                                        </a>
                                        @endif
                                        
                                        <div class="flex-1 min-w-0">
                                            <!-- Ссылка на мемориал -->
                                            @if($memory->memorial)
                                            <a href="{{ route('memorial.show', ['id' => $memory->memorial->id]) }}" class="inline-flex items-center gap-2 text-base font-semibold text-slate-700 hover:text-red-600 mb-2 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $memory->memorial->first_name }} {{ $memory->memorial->last_name }}
                                            </a>
                                            @endif
                                            
                                            <!-- Текст воспоминания -->
                                            <div class="bg-white rounded-lg p-4 mb-3 shadow-sm">
                                                <p class="text-gray-700 leading-relaxed mb-3">{{ $memory->content }}</p>
                                                
                                                <!-- Медиа воспоминания (поддержка и старого, и нового формата) -->
                                                @php
                                                    $photoUrls = [];
                                                    $videoUrls = [];
                                                    $mediaItems = is_array($memory->media) ? $memory->media : [];

                                                    $resolveMediaUrl = function ($value) {
                                                        if (!is_string($value) || $value === '') {
                                                            return null;
                                                        }

                                                        return filter_var($value, FILTER_VALIDATE_URL)
                                                            ? $value
                                                            : \Storage::disk('s3')->url($value);
                                                    };

                                                    foreach ($mediaItems as $item) {
                                                        $type = null;
                                                        $urlValue = null;

                                                        if (is_array($item)) {
                                                            $type = $item['type'] ?? null;
                                                            $urlValue = $item['url'] ?? null;
                                                        } elseif (is_string($item)) {
                                                            $urlValue = $item;
                                                        }

                                                        $resolvedUrl = $resolveMediaUrl($urlValue);
                                                        if (!$resolvedUrl) {
                                                            continue;
                                                        }

                                                        if (!$type && is_string($urlValue)) {
                                                            $path = parse_url($urlValue, PHP_URL_PATH) ?: $urlValue;
                                                            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                                            $type = in_array($extension, ['mp4', 'mov', 'webm', 'm4v', 'avi', 'mkv'], true) ? 'video' : 'image';
                                                        }

                                                        if ($type === 'video') {
                                                            $videoUrls[] = $resolvedUrl;
                                                        } else {
                                                            $photoUrls[] = $resolvedUrl;
                                                        }
                                                    }
                                                @endphp

                                                @if(count($photoUrls) > 0)
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-3">
                                                    @foreach($photoUrls as $photoUrl)
                                                    <div class="aspect-square rounded-lg overflow-hidden">
                                                        <img src="{{ $photoUrl }}" alt="" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif

                                                @if(count($videoUrls) > 0)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3">
                                                    @foreach($videoUrls as $videoUrl)
                                                    <div class="rounded-lg overflow-hidden bg-gray-900">
                                                        <video src="{{ $videoUrl }}" controls preload="metadata" class="w-full h-full object-cover"></video>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Дата и статистика -->
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $memory->created_at->format('d.m.Y в H:i') }}
                                                </div>
                                                @if($memory->comments_count > 0)
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                    </svg>
                                                    {{ $memory->comments_count }} {{ $memory->comments_count == 1 ? 'комментарий' : 'комментариев' }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p>Пока нет оставленных воспоминаний</p>
                        </div>
                    @endif
                </div>
            </main>

            <!-- Боковая панель справа -->
            <aside class="lg:w-96 lg:sticky lg:top-4 lg:h-fit space-y-6">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <!-- Аватар и основная информация -->
                    <div class="p-6">
                        <div class="text-center">
                            @if($user->avatar)
                                <div class="w-full aspect-square rounded-2xl mx-auto overflow-hidden mb-4">
                                    <img src="{{ Storage::disk('s3')->url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-full aspect-square bg-red-100 rounded-2xl mx-auto flex items-center justify-center text-6xl font-bold text-red-600 mb-4">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                            <h1 class="text-2xl font-bold text-slate-700 mb-2">{{ $user->name }}</h1>
                            <p class="text-gray-500 text-sm mb-4">{{ $user->email }}</p>
                            
                            <!-- Кнопки действий -->
                            @auth
                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('user.edit') }}" class="block w-full px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors text-center font-medium">
                                        Редактировать профиль
                                    </a>
                                @else
                                    <button class="block w-full px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors font-medium">
                                        Написать сообщение
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- О себе -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-slate-700 mb-4">О себе</h2>
                    <div class="space-y-3">
                        @if($user->city || $user->region)
                            <div class="flex items-center gap-3 text-gray-600">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>
                                    @php
                                        $location = array_filter([$user->city, $user->region]);
                                        echo implode(', ', $location);
                                    @endphp
                                </span>
                            </div>
                        @endif
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>На сайте с {{ $user->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
