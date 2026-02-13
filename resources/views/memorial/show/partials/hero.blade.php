<!-- Карточка мемориала -->
<div class="mb-4">
    <div class="container mx-auto px-4">
        <div class="relative bg-slate-700 text-white rounded-xl overflow-hidden shadow-xl">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-700/95 to-slate-800/85"></div>
            
            <!-- Кнопка редактирования (только для владельца) -->
            @auth
                @if(is_object($memorial) && isset($memorial->user_id) && $memorial->user_id === Auth::id())
                <div class="absolute top-6 left-6 z-10">
                    <a href="{{ route('memorial.edit', ['id' => $memorial->id]) }}" class="flex items-center gap-2 bg-white/90 hover:bg-white text-gray-700 px-4 py-2 rounded-lg transition-colors shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="font-medium">Редактировать</span>
                    </a>
                </div>
                @endif
            @endauth
            
            <!-- Религиозный символ -->
            @if(isset($memorial->religion) && $memorial->religion === 'orthodox')
            <div class="absolute top-6 right-6 z-10">
                <svg viewBox="0 0 24 24" class="w-12 h-12 fill-red-400 drop-shadow-lg">
                    <path d="M12 2v20M8 6h8M6 10h12M8 18h8" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M12 2L10 4h4l-2-2zM12 22l-2-2h4l-2 2z" fill="currentColor"/>
                </svg>
            </div>
            @endif
            
            <div class="relative p-6">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <!-- Фото -->
                    <div class="w-60 h-60 md:w-64 md:h-64 bg-gray-300 rounded-xl overflow-hidden flex-shrink-0 shadow-lg">
                        <img 
                            src="{{ $memorial->photo ? Storage::url($memorial->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($memorial->first_name . ' ' . $memorial->last_name) . '&size=300&background=e5e7eb&color=6b7280&bold=true' }}" 
                            alt="{{ $memorial->first_name }} {{ $memorial->last_name }}"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Информация -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 leading-tight">
                            {{ $memorial->first_name }} {{ $memorial->middle_name }} {{ $memorial->last_name }}
                        </h1>
                        
                        <!-- Даты жизни -->
                        <div class="mb-5">
                            <div class="text-xl md:text-2xl lg:text-3xl font-bold text-red-400">
                                {{ $memorial->birth_date->format('d.m.Y') }}
                                <span class="mx-4">—</span>
                                {{ $memorial->death_date->format('d.m.Y') }}
                            </div>
                        </div>
                        
                        <!-- Место рождения -->
                        @if($memorial->birth_place)
                        <div class="mb-5 flex items-center justify-center md:justify-start gap-3">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-base md:text-lg lg:text-xl opacity-90">{{ expand_region_abbreviations($memorial->birth_place) }}</span>
                        </div>
                        @endif
                        
                        @if($memorial->biography)
                        <div class="mb-4">
                            <div class="text-lg md:text-xl lg:text-2xl font-semibold text-white mb-2 italic">
                                {{ $memorial->biography }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
