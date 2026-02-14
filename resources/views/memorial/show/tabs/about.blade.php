<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
        <h3 class="text-base sm:text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            О человеке
        </h3>
    </div>
    
    <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">
        <!-- Памятные слова (некролог) -->
        @if($memorial->necrologue)
        <div>
            <h4 class="text-lg sm:text-xl font-semibold text-slate-700 mb-3 sm:mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Памятные слова
            </h4>
            <div class="bg-gray-50 rounded-lg p-4 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-700 mb-3 sm:mb-4">
                    {{ $memorial->first_name }} {{ $memorial->middle_name }} {{ $memorial->last_name }}
                </h2>
                <p class="text-base sm:text-lg text-gray-600 mb-4 sm:mb-6">
                    {{ $memorial->birth_date->format('d.m.Y') }} — {{ $memorial->death_date->format('d.m.Y') }}
                </p>
                <div class="prose max-w-none text-slate-700 leading-relaxed">
                    <p class="italic text-base sm:text-lg">
                        {{ $memorial->necrologue }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Основные факты -->
        @if($memorial->birth_date || $memorial->education || $memorial->career)
        <div>
            <h4 class="text-lg sm:text-xl font-semibold text-slate-700 mb-3 sm:mb-4">Основные факты</h4>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6">
                @if($memorial->birth_date)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="font-semibold text-slate-700 mb-2">Родился</h5>
                    <p class="text-gray-600">{{ $memorial->birth_date->format('d.m.Y') }}</p>
                    @if($memorial->birth_place)
                    <p class="text-sm text-gray-500 mt-1">{{ expand_region_abbreviations($memorial->birth_place) }}</p>
                    @endif
                </div>
                @endif
                
                @if($memorial->education)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="font-semibold text-slate-700 mb-2">Образование</h5>
                    <p class="text-gray-600">{{ $memorial->education }}</p>
                    @if($memorial->education_details)
                    <p class="text-sm text-gray-500 mt-1">{{ $memorial->education_details }}</p>
                    @endif
                </div>
                @endif
                
                @if($memorial->career)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="font-semibold text-slate-700 mb-2">Карьера</h5>
                    <p class="text-gray-600">{{ $memorial->career }}</p>
                    @if($memorial->career_details)
                    <p class="text-sm text-gray-500 mt-1">{{ $memorial->career_details }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- История жизни -->
        @if($memorial->full_biography)
        <div>
            <h4 class="text-lg sm:text-xl font-semibold text-slate-700 mb-3 sm:mb-4">История жизни</h4>
            <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-3 sm:space-y-4">
                <p>{{ $memorial->full_biography }}</p>
            </div>
        </div>
        @endif

        <!-- Характер и увлечения -->
        @if($memorial->hobbies || $memorial->character_traits)
        <div>
            <h4 class="text-lg sm:text-xl font-semibold text-slate-700 mb-3 sm:mb-4">Характер и увлечения</h4>
            <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                @if($memorial->hobbies)
                <div class="space-y-3">
                    <h5 class="font-semibold text-slate-700">Увлечения</h5>
                    @foreach(explode("\n", $memorial->hobbies) as $hobby)
                        @if(trim($hobby))
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-gray-700">{{ trim($hobby) }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
                
                @if($memorial->character_traits)
                <div class="space-y-3">
                    <h5 class="font-semibold text-slate-700">Черты характера</h5>
                    @foreach(explode("\n", $memorial->character_traits) as $trait)
                        @if(trim($trait))
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="text-gray-700">{{ trim($trait) }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
