<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Близкие люди
        </h3>
    </div>
    
    <div class="p-6">
        <!-- Ваша связь с усопшим (только для авторизованных) -->
        @auth
        @if(!$userRelationship)
        <div class="mb-6 p-6 bg-blue-50 border border-blue-200 rounded-lg">
            <h4 class="text-lg font-semibold text-slate-700 mb-4">Укажите вашу связь</h4>
            <form action="{{ route('memory.store', ['id' => is_object($memorial) && isset($memorial->id) ? $memorial->id : 1]) }}" method="POST">
                @csrf
                <input type="hidden" name="content" value="[Связь установлена через вкладку Близкие люди]">
                
                <div class="flex gap-3">
                    <select name="relationship_type" id="relationship_type_people" required class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
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
                    
                    <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors font-medium whitespace-nowrap">
                        Сохранить
                    </button>
                </div>
                
                <!-- Поле для "Другое" -->
                <div x-data="{ showCustom: false }" class="mt-3">
                    <div x-show="document.getElementById('relationship_type_people')?.value === 'other'" x-init="$watch('document.getElementById(\'relationship_type_people\')?.value', value => showCustom = value === 'other')">
                        <input 
                            type="text" 
                            name="relationship_custom" 
                            id="relationship_custom_people"
                            placeholder="Укажите вашу связь (например: учитель, наставник, сват...)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        >
                    </div>
                </div>
                
                <p class="mt-3 text-xs text-gray-500">
                    После указания связи вы сможете оставлять воспоминания. Если не хотите указывать связь - выберите "Не хочу указывать"
                </p>
            </form>
        </div>
        @else
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-slate-700">Ваша связь: <span class="text-green-600">{{ $userRelationship->relationship_type === 'other' ? $userRelationship->custom_relationship : '' }}</span></p>
                    @if($userRelationship->relationship_type !== 'other')
                        <p class="text-sm text-gray-600">
                            @php
                                $labels = [
                                    'spouse' => 'Супруг/Супруга',
                                    'parent' => 'Родитель',
                                    'child' => 'Ребенок',
                                    'sibling' => 'Брат/Сестра',
                                    'grandparent' => 'Дедушка/Бабушка',
                                    'grandchild' => 'Внук/Внучка',
                                    'uncle_aunt' => 'Дядя/Тетя',
                                    'nephew_niece' => 'Племянник/Племянница',
                                    'cousin' => 'Двоюродный брат/сестра',
                                    'friend' => 'Друг',
                                    'colleague' => 'Коллега',
                                    'neighbor' => 'Сосед',
                                    'classmate' => 'Одноклассник',
                                ];
                                echo $labels[$userRelationship->relationship_type] ?? $userRelationship->relationship_type;
                            @endphp
                        </p>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endauth

        <!-- Список близких людей (будущий функционал) -->
        <div class="text-center py-12 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-sm">Управление другими близкими людьми будет доступно в будущих обновлениях</p>
        </div>
    </div>
</div>
