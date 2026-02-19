@php
    $commentsAllowed = (bool) data_get($memorial, 'allow_comments', true);
    $memoriesLocked = (bool) data_get($memorial, 'moderate_memories', false)
        && (!auth()->check() || (int) auth()->id() !== (int) data_get($memorial, 'user_id', 0));
    $selectedSort = $memorySort ?? request('memory_sort', 'new');
    $memorialIdForSort = is_object($memorial) && isset($memorial->id) ? str_replace('id', '', (string) $memorial->id) : 1;
    $sortOptions = [
        'new' => 'Новые',
        'popular' => 'Популярные',
        'media' => 'С медиа',
    ];
@endphp

<!-- Форма добавления воспоминания -->
@auth
@if($memoriesLocked)
<div class="mb-4 overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
    <div class="border-b border-slate-700 bg-slate-700 px-4 py-4 sm:px-6">
        <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            Добавление воспоминаний ограничено
        </h3>
    </div>
    <div class="p-4 sm:p-6">
        <p class="text-sm text-gray-600">Владелец включил модерацию. Новые воспоминания временно недоступны.</p>
    </div>
</div>
@else
<div class="mb-4 overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
    <div class="border-b border-slate-700 bg-slate-700 px-4 py-4 sm:px-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
        <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="hidden sm:inline">Поделитесь воспоминаниями</span>
            <span class="sm:hidden">Добавить воспоминание</span>
        </h3>
        
        <!-- Поле выбора связи (только если связи еще нет) -->
        @if(!$userRelationship)
        <div class="w-full sm:w-auto flex-shrink-0">
            <select name="relationship_type" id="relationship_type" form="memory-form" required class="w-full sm:w-auto rounded-lg border border-sky-200 bg-white px-3 py-2 text-xs sm:px-4 sm:text-sm focus:border-transparent focus:ring-2 focus:ring-sky-200">
                <option value="">Кем вам приходится {{ is_object($memorial) ? $memorial->first_name : 'этот человек' }}?</option>
                <optgroup label="Семья">
                    <option value="husband">Муж</option>
                    <option value="wife">Жена</option>
                    <option value="father">Отец</option>
                    <option value="mother">Мать</option>
                    <option value="son">Сын</option>
                    <option value="daughter">Дочь</option>
                    <option value="brother">Брат</option>
                    <option value="sister">Сестра</option>
                    <option value="grandfather">Дедушка</option>
                    <option value="grandmother">Бабушка</option>
                    <option value="grandson">Внук</option>
                    <option value="granddaughter">Внучка</option>
                    <option value="uncle">Дядя</option>
                    <option value="aunt">Тетя</option>
                    <option value="nephew">Племянник</option>
                    <option value="niece">Племянница</option>
                    <option value="relative">Родственник</option>
                </optgroup>
                <optgroup label="Другие связи">
                    <option value="friend_male">Друг</option>
                    <option value="friend_female">Подруга</option>
                    <option value="colleague">Коллега</option>
                    <option value="neighbor">Сосед</option>
                    <option value="classmate">Одноклассник</option>
                    <option value="coursemate">Однокурсник</option>
                    <option value="other">Другое</option>
                </optgroup>
                <option value="not_specified">Не хочу указывать</option>
            </select>
        </div>
        @endif
    </div>
    
    <form id="memory-form" action="{{ route('memory.store', ['id' => is_object($memorial) && isset($memorial->id) ? str_replace('id', '', $memorial->id) : 1]) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
        @csrf
        
        <!-- Поле для "Другое" -->
        @if(!$userRelationship)
        <div x-data="{ showCustom: false }" class="mb-4">
            <div x-show="document.getElementById('relationship_type')?.value === 'other'" x-init="$watch('document.getElementById(\'relationship_type\')?.value', value => showCustom = value === 'other')">
                <input 
                    type="text" 
                    name="relationship_custom" 
                    placeholder="Укажите вашу связь"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-sky-200 sm:px-4"
                >
            </div>
        </div>
        @endif
        
        <textarea
            name="content"
            required
            placeholder="Напишите ваше воспоминание..."
            class="min-h-[100px] w-full resize-vertical rounded-lg border border-gray-300 p-3 text-sm transition-colors focus:border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-200/70 sm:min-h-[120px] sm:p-4 sm:text-base"
        ></textarea>
        
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mt-4">
            <label class="flex items-center justify-center gap-2 px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="hidden sm:inline">Добавить фото/видео</span>
                <span class="sm:hidden">Фото/видео</span>
                <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
            </label>
            
            <button type="submit" class="flex items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm text-white transition-colors hover:bg-red-600 sm:px-6 sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Опубликовать
            </button>
        </div>
    </form>
</div>
@endif
@else
<!-- Блок для неавторизованных пользователей -->
<div class="mb-4 overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
    <div class="border-b border-slate-700 bg-slate-700 px-4 py-4 sm:px-6">
        <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span class="hidden sm:inline">Поделитесь воспоминаниями</span>
            <span class="sm:hidden">Добавить воспоминание</span>
        </h3>
    </div>
    
    <div class="p-4 sm:p-6 text-center">
        <div class="mb-4">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <h4 class="text-base sm:text-lg font-medium text-slate-700 mb-2">
                Войдите, чтобы оставить воспоминание
            </h4>
            <p class="text-sm sm:text-base text-gray-500 mb-4">
                Поделитесь своими воспоминаниями об этом человеке. Войдите в систему или зарегистрируйтесь.
            </p>
        </div>
        
        <div class="flex justify-center">
            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 rounded-lg bg-red-500 px-6 py-2 text-sm text-white transition-colors hover:bg-red-600 sm:text-base">
                Вход
            </a>
        </div>
    </div>
</div>
@endauth

<div class="mb-3 rounded-xl border border-slate-300 bg-white px-3 py-3 shadow-md sm:px-4">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="text-sm font-semibold text-slate-700">Воспоминания ({{ count($memories) }})</div>
        <div class="flex flex-wrap gap-1.5">
            @foreach($sortOptions as $sortKey => $sortLabel)
                @php
                    $isActiveSort = $selectedSort === $sortKey;
                @endphp
                <a
                    href="{{ route('memorial.show', ['id' => $memorialIdForSort, 'memory_sort' => $sortKey]) }}"
                    class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $isActiveSort ? 'bg-red-500 text-white shadow-sm border border-red-500' : 'bg-gray-100 text-slate-700 hover:bg-slate-100 border border-transparent' }}"
                >
                    {{ $sortLabel }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Список воспоминаний -->
<div class="space-y-3">
    @if(count($memories) === 0)
    <div class="rounded-xl border border-slate-300 bg-white px-4 py-10 text-center text-sm text-gray-500 shadow-md">
        По выбранному фильтру воспоминаний пока нет.
    </div>
    @endif
    @foreach($memories as $memory)
    @php
        $memoryContent = (string) ($memory['content'] ?? '');
        $collapsedContent = mb_strlen($memoryContent) > 260 ? mb_substr($memoryContent, 0, 260) . '…' : $memoryContent;
        $hasLongContent = mb_strlen($memoryContent) > 260;
        $memoryComments = is_array($memory['comments'] ?? null) ? $memory['comments'] : [];
        $memoryCommentsCount = count($memoryComments);
    @endphp
    <div x-data="{ showComments: false, showAllComments: false, expanded: false }" data-memory-id="{{ $memory['id'] }}" class="overflow-hidden rounded-lg border border-slate-300 bg-white shadow-md transition-shadow hover:shadow-lg">
        <!-- Заголовок воспоминания -->
        <div class="flex items-center justify-between px-3 sm:px-4 py-3">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <a href="{{ route('user.show', ['id' => $memory['author_id']]) }}" class="flex-shrink-0">
                    <img 
                        src="{{ $memory['author_avatar'] }}" 
                        alt="{{ $memory['author_name'] }}"
                        class="w-12 h-12 sm:w-16 sm:h-16 rounded-lg object-cover hover:opacity-80 transition-opacity cursor-pointer"
                    />
                </a>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                        <a href="{{ route('user.show', ['id' => $memory['author_id']]) }}" class="hover:underline min-w-0">
                            <h4 class="font-semibold text-slate-700 text-sm sm:text-base truncate">{{ $memory['author_name'] }}</h4>
                        </a>
                        @if(isset($memory['author_relationship']))
                            <span class="text-xs sm:text-sm text-sky-600 font-medium whitespace-nowrap">• {{ $memory['author_relationship'] }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($memory['created_at'])->locale('ru')->isoFormat('D MMMM YYYY в HH:mm') }}</p>
                </div>
            </div>
        </div>

        <!-- Контент воспоминания -->
        <div class="px-3 sm:px-4 pb-3">
            <p x-show="!expanded" class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $collapsedContent }}</p>
            @if($hasLongContent)
                <p x-show="expanded" x-cloak class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $memoryContent }}</p>
                <button @click="expanded = !expanded" class="mt-2 text-xs font-medium text-sky-600 hover:text-sky-700">
                    <span x-show="!expanded">Показать полностью</span>
                    <span x-show="expanded" x-cloak>Свернуть</span>
                </button>
            @endif
        </div>

        <!-- Фото воспоминания -->
        @if(isset($memory['photos']) && is_array($memory['photos']) && count($memory['photos']) > 0)
        <div class="px-3 sm:px-4 pb-3">
            @php
                $photoCount = count($memory['photos']);
            @endphp
            
            @if($photoCount === 1)
                <!-- 1 фото - на всю ширину на мобильном, 50% на десктопе -->
                <div class="relative group cursor-pointer sm:max-w-md" onclick="openPhotoModal('{{ $memory['photos'][0] }}')">
                    <div class="aspect-[4/3] rounded-lg overflow-hidden bg-gray-100">
                        <img 
                            src="{{ $memory['photos'][0] }}" 
                            alt="Фото воспоминания"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />
                    </div>
                </div>
            @elseif($photoCount === 2)
                <!-- 2 фото - вертикально на мобильном, горизонтально на десктопе -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 sm:gap-2">
                    @foreach($memory['photos'] as $photo)
                    <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $photo }}')">
                        <div class="aspect-[4/3] rounded-lg overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $photo }}" 
                                alt="Фото воспоминания"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- 3+ фото - сетка 2 колонки -->
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2">
                    @foreach($memory['photos'] as $photo)
                    <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $photo }}')">
                        <div class="aspect-[4/3] rounded-lg overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $photo }}" 
                                alt="Фото воспоминания"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        <!-- Видео воспоминания -->
        @if(isset($memory['videos']) && is_array($memory['videos']) && count($memory['videos']) > 0)
        <div class="px-3 sm:px-4 pb-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($memory['videos'] as $video)
                <div class="relative aspect-[4/3] rounded-lg overflow-hidden bg-gray-900">
                    <video 
                        src="{{ $video }}" 
                        controls
                        preload="metadata"
                        class="w-full h-full object-cover"
                    ></video>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/90 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Действия -->
        <div class="px-3 sm:px-4 py-2 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 sm:gap-4">
                    @auth
                    @php
                        $isLiked = isset($memory['user_liked']) && $memory['user_liked'];
                    @endphp
                    <button onclick="likeMemory({{ $memory['id'] }}, this)" class="flex items-center gap-1.5 {{ $isLiked ? 'text-red-500' : 'text-gray-500' }} hover:text-red-600 transition-colors text-sm">
                        @if($isLiked)
                            <svg class="w-4 h-4" fill="#ef4444" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="#6b7280" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        @endif
                        <span>{{ $memory['likes'] }}</span>
                    </button>
                    @else
                    <div class="flex items-center gap-1.5 text-gray-500 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="#6b7280" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span>{{ $memory['likes'] }}</span>
                    </div>
                    @endauth
                    <button 
                        @click="showComments = !showComments"
                        class="flex items-center gap-1.5 text-gray-500 hover:text-sky-500 transition-colors text-sm"
                    >
                        @if(isset($memory['user_has_comment']) && $memory['user_has_comment'])
                            <svg class="w-4 h-4" fill="#60a5fa" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="#6b7280" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        @endif
                        <span>{{ $memoryCommentsCount }}</span>
                    </button>
                </div>
                
                <div class="flex items-center gap-1.5 text-gray-400 text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span>{{ $memory['views'] }}</span>
                </div>
            </div>
        </div>

        <!-- Комментарии -->
        <div x-show="showComments" class="border-t border-gray-100 px-3 sm:px-4 py-3 bg-gray-50">
            <!-- Форма добавления комментария -->
            @auth
            @if($commentsAllowed)
                <div class="flex gap-2 sm:gap-3 pb-3 border-b border-gray-200 mb-3">
                    <img 
                        src="{{ auth()->user()->avatar ? \Storage::disk('s3')->url(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=128&background=e3f2fd&color=1976d2&bold=true' }}" 
                        alt="{{ auth()->user()->name }}"
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-md object-cover flex-shrink-0"
                    />
                    <form onsubmit="submitComment({{ $memory['id'] }}, this); return false;" class="flex-1 flex flex-col sm:flex-row gap-2">
                        <input 
                            type="text" 
                            placeholder="Написать комментарий..."
                            class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-xs focus:border-transparent focus:ring-2 focus:ring-sky-200"
                        />
                        <button 
                            type="submit"
                            class="whitespace-nowrap rounded-lg bg-red-500 px-4 py-2 text-xs text-white transition-colors hover:bg-red-600"
                        >
                            Отправить
                        </button>
                    </form>
                </div>
            @else
                <div class="pb-3 border-b border-gray-200 mb-3">
                    <p class="text-xs text-gray-500">Комментарии отключены владельцем мемориала.</p>
                </div>
            @endif
            @endauth
            
            <div class="space-y-2 sm:space-y-3">
                @if($memoryCommentsCount > 0)
                @foreach($memoryComments as $commentIndex => $comment)
            <div x-show="showAllComments || {{ $commentIndex }} < 3" class="flex gap-3">
                <a href="{{ route('user.show', ['id' => $comment['author_id']]) }}" class="flex-shrink-0">
                    <img 
                        src="{{ $comment['author_avatar'] }}" 
                        alt="{{ $comment['author_name'] }}"
                        class="w-12 h-12 rounded-md object-cover hover:opacity-80 transition-opacity cursor-pointer"
                    />
                </a>
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-lg px-3 py-2 border border-gray-200">
                        <a href="{{ route('user.show', ['id' => $comment['author_id']]) }}" class="hover:underline">
                            <h5 class="font-semibold text-slate-700 text-sm mb-1">{{ $comment['author_name'] }}</h5>
                        </a>
                        <p class="text-slate-600 text-xs leading-relaxed">{{ $comment['content'] }}</p>
                    </div>
                    <div class="flex items-center gap-3 mt-1 px-2">
                        @auth
                        <button onclick="likeComment({{ $comment['id'] }}, this)" class="flex items-center gap-1 {{ isset($comment['user_liked']) && $comment['user_liked'] ? 'text-red-500' : 'text-gray-400' }} hover:text-red-600 transition-colors text-xs">
                            <svg class="w-3 h-3" {{ isset($comment['user_liked']) && $comment['user_liked'] ? 'fill="#ef4444"' : 'fill="none" stroke="#9ca3af"' }} viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span>{{ $comment['likes'] }}</span>
                        </button>
                        @else
                        <div class="flex items-center gap-1 text-gray-400 text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="#9ca3af" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span>{{ $comment['likes'] }}</span>
                        </div>
                        @endauth
                        <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($comment['created_at'])->locale('ru')->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
                @endforeach

                @if($memoryCommentsCount > 3)
                    <button
                        type="button"
                        @click="showAllComments = !showAllComments"
                        class="text-xs font-medium text-sky-600 hover:text-sky-700"
                    >
                        <span x-show="!showAllComments">Показать ещё комментарии ({{ $memoryCommentsCount - 3 }})</span>
                        <span x-show="showAllComments" x-cloak>Скрыть комментарии</span>
                    </button>
                @endif
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
