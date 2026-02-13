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
                                    <select name="creator_relationship" id="creator_relationship" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                        <option value="">Выберите связь</option>
                                        <optgroup label="Семья">
                                            <option value="spouse" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'spouse' ? 'selected' : '' }}>Супруг/Супруга</option>
                                            <option value="parent" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'parent' ? 'selected' : '' }}>Родитель</option>
                                            <option value="child" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'child' ? 'selected' : '' }}>Ребенок (сын/дочь)</option>
                                            <option value="sibling" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'sibling' ? 'selected' : '' }}>Брат/Сестра</option>
                                            <option value="grandparent" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'grandparent' ? 'selected' : '' }}>Дедушка/Бабушка</option>
                                            <option value="grandchild" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'grandchild' ? 'selected' : '' }}>Внук/Внучка</option>
                                            <option value="uncle_aunt" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'uncle_aunt' ? 'selected' : '' }}>Дядя/Тетя</option>
                                            <option value="nephew_niece" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'nephew_niece' ? 'selected' : '' }}>Племянник/Племянница</option>
                                            <option value="cousin" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'cousin' ? 'selected' : '' }}>Двоюродный брат/сестра</option>
                                        </optgroup>
                                        <optgroup label="Другие связи">
                                            <option value="friend" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'friend' ? 'selected' : '' }}>Друг/Подруга</option>
                                            <option value="colleague" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'colleague' ? 'selected' : '' }}>Коллега</option>
                                            <option value="neighbor" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'neighbor' ? 'selected' : '' }}>Сосед/Соседка</option>
                                            <option value="classmate" {{ old('creator_relationship', $userRelationship?->relationship_type) == 'classmate' ? 'selected' : '' }}>Одноклассник/Однокурсник</option>
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
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        >
                                    </div>
                                </div>
                                
                                <p class="mt-3 text-xs text-gray-500">
                                    Эта информация поможет другим пользователям понять вашу связь с усопшим и будет отображаться рядом с вашими воспоминаниями
                                </p>
                            </div>

                            <!-- Другие близкие люди (будущий функционал) -->
                            <div class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-sm">Управление другими близкими людьми будет доступно в будущих обновлениях</p>
                            </div>
                        </div>
