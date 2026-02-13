<!-- Форма добавления воспоминания -->
@auth
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-4">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
        <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Поделитесь воспоминаниями
        </h3>
        
        <!-- Поле выбора связи (только если связи еще нет) -->
        @if(!$userRelationship)
        <div class="flex-shrink-0">
            <select name="relationship_type" id="relationship_type" form="memory-form" required class="px-4 py-2 text-sm border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent bg-blue-100">
                <option value="">Кем вам приходится {{ is_object($memorial) ? $memorial->first_name : 'этот человек' }}?</option>
                <optgroup label="Семья">
                    <option value="spouse">Супруг/Супруга</option>
                    <option value="parent">Родитель</option>
                    <option value="child">Ребенок (сын/дочь)</option>
                    <option value="sibling">Брат/Сестра</option>
                    <option value="grandparent">Дедушка/Бабушка</option>
                    <option value="grandchild">Внук/Внучка</option>
                    <option value="uncle_aunt">Дядя/Тетя</option>
                    <option value="nephew_niece">Племянник/Племянница</option>
                    <option value="cousin">Двоюродный брат/сестра</option>
                </optgroup>
                <optgroup label="Другие связи">
                    <option value="friend">Друг/Подруга</option>
                    <option value="colleague">Коллега</option>
                    <option value="neighbor">Сосед/Соседка</option>
                    <option value="classmate">Одноклассник/Однокурсник</option>
                    <option value="other">Другое</option>
                </optgroup>
                <option value="not_specified">Не хочу указывать</option>
            </select>
        </div>
        @endif
    </div>
    
    <form id="memory-form" action="{{ route('memory.store', ['id' => is_object($memorial) && isset($memorial->id) ? $memorial->id : 1]) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <!-- Поле для "Другое" -->
        @if(!$userRelationship)
        <div x-data="{ showCustom: false }" class="mb-4">
            <div x-show="document.getElementById('relationship_type')?.value === 'other'" x-init="$watch('document.getElementById(\'relationship_type\')?.value', value => showCustom = value === 'other')">
                <input 
                    type="text" 
                    name="relationship_custom" 
                    placeholder="Укажите вашу связь"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                >
            </div>
        </div>
        @endif
        
        <textarea
            name="content"
            required
            placeholder="Напишите ваше воспоминание..."
            class="w-full p-4 border border-gray-300 rounded-lg resize-vertical min-h-[120px] focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors"
        ></textarea>
        
        <div class="flex justify-between items-center mt-4">
            <label class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Добавить фото/видео
                <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
            </label>
            
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Опубликовать
            </button>
        </div>
    </form>
</div>
@else
<!-- Блок для неавторизованных пользователей -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-4">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Поделитесь воспоминаниями
        </h3>
    </div>
    
    <div class="p-6 text-center">
        <div class="mb-4">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <h4 class="text-lg font-medium text-slate-700 mb-2">
                Войдите, чтобы оставить воспоминание
            </h4>
            <p class="text-gray-500 mb-4">
                Поделитесь своими воспоминаниями об этом человеке. Войдите в систему или зарегистрируйтесь.
            </p>
        </div>
        
        <div class="flex justify-center">
            <a href="{{ route('login') }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                Вход
            </a>
        </div>
    </div>
</div>
@endauth

<!-- Список воспоминаний -->
<div class="space-y-3">
    @foreach($memories as $memory)
    <div x-data="{ showComments{{ $memory['id'] }}: false }" class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
        <!-- Заголовок воспоминания -->
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.show', ['id' => 'id1']) }}" class="flex-shrink-0">
                    <img 
                        src="{{ $memory['author_avatar'] }}" 
                        alt="{{ $memory['author_name'] }}"
                        class="w-16 h-16 rounded-lg object-cover hover:opacity-80 transition-opacity cursor-pointer"
                    />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('user.show', ['id' => 'id1']) }}" class="hover:underline">
                            <h4 class="font-semibold text-slate-700 text-base">{{ $memory['author_name'] }}</h4>
                        </a>
                        @if(isset($memory['author_relationship']))
                            <span class="text-sm text-blue-600 font-medium">• {{ $memory['author_relationship'] }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($memory['created_at'])->locale('ru')->isoFormat('D MMMM YYYY в HH:mm') }}</p>
                </div>
            </div>
        </div>

        <!-- Контент воспоминания -->
        <div class="px-4 pb-3">
            <p class="text-slate-700 text-sm leading-relaxed">{{ $memory['content'] }}</p>
        </div>

        <!-- Фото воспоминания -->
        @if($memory['photo_url'])
        <div class="px-4 pb-3">
            <img 
                src="{{ $memory['photo_url'] }}" 
                alt="Фото воспоминания"
                class="w-full rounded-lg"
            />
        </div>
        @endif

        <!-- Действия -->
        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button class="flex items-center gap-1.5 text-gray-500 hover:text-red-500 transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span>{{ $memory['likes'] }}</span>
                    </button>
                    <button 
                        @click="showComments{{ $memory['id'] }} = !showComments{{ $memory['id'] }}"
                        class="flex items-center gap-1.5 text-gray-500 hover:text-blue-500 transition-colors text-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span>{{ is_array($memory['comments']) ? count($memory['comments']) : $memory['comments'] }}</span>
                    </button>
                </div>
                
                <div class="flex items-center gap-1.5 text-gray-400 text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span>{{ $memory['views'] }}</span>
                </div>
            </div>
        </div>

        <!-- Комментарии -->
        @if(is_array($memory['comments']) && count($memory['comments']) > 0)
        <div x-show="showComments{{ $memory['id'] }}" class="border-t border-gray-100 px-4 py-3 bg-gray-50 space-y-3">
            @foreach($memory['comments'] as $comment)
            <div class="flex gap-3">
                <a href="{{ route('user.show', ['id' => 'id1']) }}" class="flex-shrink-0">
                    <img 
                        src="{{ $comment['author_avatar'] }}" 
                        alt="{{ $comment['author_name'] }}"
                        class="w-12 h-12 rounded-md object-cover hover:opacity-80 transition-opacity cursor-pointer"
                    />
                </a>
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-lg px-3 py-2 border border-gray-200">
                        <a href="{{ route('user.show', ['id' => 'id1']) }}" class="hover:underline">
                            <h5 class="font-semibold text-slate-700 text-sm mb-1">{{ $comment['author_name'] }}</h5>
                        </a>
                        <p class="text-slate-600 text-xs leading-relaxed">{{ $comment['content'] }}</p>
                    </div>
                    <div class="flex items-center gap-3 mt-1 px-2">
                        <button class="flex items-center gap-1 text-gray-400 hover:text-red-500 transition-colors text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span>{{ $comment['likes'] }}</span>
                        </button>
                        <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($comment['created_at'])->locale('ru')->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Форма добавления комментария -->
            @auth
            <div class="flex gap-3 pt-2">
                <img 
                    src="{{ auth()->user()->avatar ? \Storage::disk('s3')->url(auth()->user()->avatar) : 'https://via.placeholder.com/48' }}" 
                    alt="{{ auth()->user()->name }}"
                    class="w-12 h-12 rounded-md object-cover flex-shrink-0"
                />
                <form class="flex-1 flex gap-2">
                    <input 
                        type="text" 
                        placeholder="Написать комментарий..."
                        class="flex-1 px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    />
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition-colors"
                    >
                        Отправить
                    </button>
                </form>
            </div>
            @endauth
        </div>
        @endif
    </div>
    @endforeach
</div>
