                        <div x-show="activeTab === 'people'" class="space-y-6">
                            <!-- Ваша связь с усопшим -->
                            @php
                                $userRelationship = $memorial->relationships()->where('user_id', auth()->id())->first();
                            @endphp
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-700 mb-4">Ваша связь</h3>
                                <div>
                                    <label for="creator_relationship" class="block text-sm font-medium text-gray-700 mb-2">
                                        Кем вам приходится {{ $memorial->first_name }}?
                                    </label>
                                    <select name="creator_relationship" id="creator_relationship" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Выберите связь</option>
                                        <optgroup label="Семья">
                                            <option value="husband" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'husband' ? 'selected' : '' }}>Муж</option>
                                            <option value="wife" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'wife' ? 'selected' : '' }}>Жена</option>
                                            <option value="father" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'father' ? 'selected' : '' }}>Отец</option>
                                            <option value="mother" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'mother' ? 'selected' : '' }}>Мать</option>
                                            <option value="son" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'son' ? 'selected' : '' }}>Сын</option>
                                            <option value="daughter" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'daughter' ? 'selected' : '' }}>Дочь</option>
                                            <option value="brother" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'brother' ? 'selected' : '' }}>Брат</option>
                                            <option value="sister" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'sister' ? 'selected' : '' }}>Сестра</option>
                                            <option value="grandfather" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'grandfather' ? 'selected' : '' }}>Дедушка</option>
                                            <option value="grandmother" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'grandmother' ? 'selected' : '' }}>Бабушка</option>
                                            <option value="grandson" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'grandson' ? 'selected' : '' }}>Внук</option>
                                            <option value="granddaughter" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'granddaughter' ? 'selected' : '' }}>Внучка</option>
                                            <option value="uncle" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'uncle' ? 'selected' : '' }}>Дядя</option>
                                            <option value="aunt" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'aunt' ? 'selected' : '' }}>Тетя</option>
                                            <option value="nephew" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'nephew' ? 'selected' : '' }}>Племянник</option>
                                            <option value="niece" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'niece' ? 'selected' : '' }}>Племянница</option>
                                            <option value="relative" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'relative' ? 'selected' : '' }}>Родственник</option>
                                        </optgroup>
                                        <optgroup label="Другие связи">
                                            <option value="friend_male" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'friend_male' ? 'selected' : '' }}>Друг</option>
                                            <option value="friend_female" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'friend_female' ? 'selected' : '' }}>Подруга</option>
                                            <option value="colleague" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'colleague' ? 'selected' : '' }}>Коллега</option>
                                            <option value="neighbor" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'neighbor' ? 'selected' : '' }}>Сосед</option>
                                            <option value="classmate" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'classmate' ? 'selected' : '' }}>Одноклассник</option>
                                            <option value="coursemate" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'coursemate' ? 'selected' : '' }}>Однокурсник</option>
                                            <option value="other" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'other' ? 'selected' : '' }}>Другое</option>
                                        </optgroup>
                                    </select>
                                </div>
                                
                                <!-- Поле для "Другое" -->
                                <div x-data="{ showCustom: {{ $userRelationship?->relationship_type == 'other' ? 'true' : 'false' }} }" class="mt-4">
                                    <div x-show="document.getElementById('creator_relationship')?.value === 'other' || showCustom" x-init="$watch('document.getElementById(\'creator_relationship\')?.value', value => showCustom = value === 'other')">
                                        <label for="creator_relationship_custom" class="block text-sm font-medium text-gray-700 mb-2">
                                            Укажите вашу связь
                                        </label>
                                        <input 
                                            type="text" 
                                            name="creator_relationship_custom" 
                                            id="creator_relationship_custom"
                                            value="{{ old('creator_relationship_custom', $userRelationship?->custom_relationship) }}"
                                            placeholder="Например: учитель, наставник, сват..."
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>
                                </div>
                                
                                <p class="mt-3 text-xs text-gray-500">
                                    Эта информация поможет другим пользователям понять вашу связь с усопшим и будет отображаться рядом с вашими воспоминаниями
                                </p>
                            </div>

                            <!-- Другие близкие люди -->
                            @php
                                $allRelationships = $memorial->relationships()
                                    ->with('user')
                                    ->where('user_id', '!=', auth()->id())
                                    ->where('visible', true)
                                    ->get();
                            @endphp
                            
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-700 mb-4">Близкие люди ({{ $allRelationships->count() }})</h3>
                                
                                @if($allRelationships->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($allRelationships as $relationship)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <img 
                                                    src="{{ $relationship->user->avatar ? \Storage::disk('s3')->url($relationship->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($relationship->user->name) . '&size=128&background=e3f2fd&color=1976d2&bold=true' }}" 
                                                    alt="{{ $relationship->user->name }}"
                                                    class="w-12 h-12 rounded-lg object-cover"
                                                />
                                                <div>
                                                    <h4 class="font-semibold text-slate-700">{{ $relationship->user->name }}</h4>
                                                    <p class="text-sm text-gray-600">
                                                        @php
                                                            $relationshipLabels = [
                                                                'husband' => 'Муж',
                                                                'wife' => 'Жена',
                                                                'father' => 'Отец',
                                                                'mother' => 'Мать',
                                                                'son' => 'Сын',
                                                                'daughter' => 'Дочь',
                                                                'brother' => 'Брат',
                                                                'sister' => 'Сестра',
                                                                'grandfather' => 'Дедушка',
                                                                'grandmother' => 'Бабушка',
                                                                'grandson' => 'Внук',
                                                                'granddaughter' => 'Внучка',
                                                                'uncle' => 'Дядя',
                                                                'aunt' => 'Тетя',
                                                                'nephew' => 'Племянник',
                                                                'niece' => 'Племянница',
                                                                'relative' => 'Родственник',
                                                                'friend_male' => 'Друг',
                                                                'friend_female' => 'Подруга',
                                                                'colleague' => 'Коллега',
                                                                'neighbor' => 'Сосед',
                                                                'classmate' => 'Одноклассник',
                                                                'coursemate' => 'Однокурсник',
                                                            ];
                                                        @endphp
                                                        {{ $relationship->relationship_type === 'other' ? $relationship->custom_relationship : ($relationshipLabels[$relationship->relationship_type] ?? $relationship->relationship_type) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs px-2 py-1 rounded {{ $relationship->confirmed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ $relationship->confirmed ? 'Подтверждено' : 'Ожидает подтверждения' }}
                                                </span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-400">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-sm">Пока нет других близких людей</p>
                                        <p class="text-xs mt-2">Люди могут добавить свою связь оставив воспоминание</p>
                                    </div>
                                @endif
                            </div>
                        </div>
