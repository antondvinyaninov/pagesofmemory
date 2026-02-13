                        <div x-show="activeTab === 'basic'" class="space-y-6">
                            
                            <!-- Блок: Фото и ФИО -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Фото и ФИО</h3>

                                <div class="flex gap-6" x-data="photoUpload()">
                                    <!-- Фото слева -->
                                    <div class="flex-shrink-0">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Фото *</label>
                                        <div class="w-56 h-56 bg-white rounded-2xl flex items-center justify-center overflow-hidden border-2 border-dashed border-blue-200 hover:border-blue-300 transition-colors cursor-pointer" onclick="document.getElementById('photo').click()">
                                            <img x-show="previewUrl" :src="previewUrl" class="w-full h-full object-cover">
                                            <svg x-show="!previewUrl" class="w-20 h-20 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <input 
                                            type="file" 
                                            name="photo" 
                                            id="photo" 
                                            accept="image/*,.heic,.heif" 
                                            @change="handleFileSelect($event)"
                                            class="hidden"
                                        >
                                        <p class="mt-2 text-xs text-gray-500 text-center">JPG, PNG, WEBP<br>до 10MB</p>
                                    </div>

                                    <!-- ФИО справа -->
                                    <div class="flex-1 space-y-4">
                                        <!-- Фамилия -->
                                        <div>
                                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1.5">Фамилия *</label>
                                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $memorial->last_name) }}" required class="w-full px-4 py-2.5 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>

                                        <!-- Имя -->
                                        <div>
                                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">Имя *</label>
                                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $memorial->first_name) }}" required class="w-full px-4 py-2.5 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>

                                        <!-- Отчество -->
                                        <div>
                                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1.5">Отчество</label>
                                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $memorial->middle_name) }}" class="w-full px-4 py-2.5 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Блок: Даты жизни -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Даты жизни</h3>

                                <div class="grid grid-cols-12 gap-5">
                                    <!-- Дата рождения -->
                                    <div class="col-span-3">
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Дата рождения *</label>
                                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $memorial->birth_date?->format('Y-m-d')) }}" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <!-- Дата смерти -->
                                    <div class="col-span-3">
                                        <label for="death_date" class="block text-sm font-medium text-gray-700 mb-2">Дата смерти *</label>
                                        <input type="date" name="death_date" id="death_date" value="{{ old('death_date', $memorial->death_date?->format('Y-m-d')) }}" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <!-- Место рождения -->
                                    <div class="col-span-6" x-data="birthPlaceAutocomplete()">
                                        <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">Место рождения</label>
                                        <div class="relative">
                                            <input 
                                                type="text" 
                                                id="birth_place_input"
                                                @input="searchCity($event.target.value); console.log('INPUT событие, значение:', $event.target.value, 'selectedCity:', selectedCity)"
                                                @focus="showSuggestions = suggestions.length > 0; console.log('FOCUS на birth_place_input, selectedCity:', selectedCity)"
                                                @blur="console.log('BLUR с birth_place_input, selectedCity:', selectedCity, 'значение поля:', $event.target.value)"
                                                @change="console.log('CHANGE на birth_place_input, значение:', $event.target.value)"
                                                placeholder="Начните вводить город..."
                                                class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                            <input type="hidden" name="birth_place" :value="selectedCity" x-init="console.log('Скрытое поле birth_place инициализировано, значение:', selectedCity)">
                                            
                                            <!-- Список подсказок -->
                                            <div x-show="showSuggestions && suggestions.length > 0" 
                                                 @click.away="showSuggestions = false"
                                                 class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                                <template x-for="suggestion in suggestions" :key="suggestion.value">
                                                    <div @click="selectCity(suggestion)" 
                                                         class="px-4 py-2 hover:bg-blue-50 cursor-pointer">
                                                        <div class="font-medium text-gray-900" x-text="suggestion.data.city"></div>
                                                        <div class="text-sm text-gray-500" x-text="suggestion.data.region"></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Блок: Дополнительная информация -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Дополнительная информация</h3>

                                <div class="space-y-5">
                                    <!-- Краткая надпись -->
                                    <div x-data="{ charCount: {{ old('biography', $memorial->biography) ? strlen(old('biography', $memorial->biography)) : 0 }} }">
                                        <label for="biography" class="block text-sm font-medium text-gray-700 mb-2">
                                            Краткая надпись
                                            <span class="text-xs text-gray-500">(отображается на главной карточке)</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            name="biography" 
                                            id="biography" 
                                            value="{{ old('biography', $memorial->biography) }}" 
                                            maxlength="80"
                                            @input="charCount = $event.target.value.length"
                                            placeholder="Любящий муж, отец и дедушка..."
                                            class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                        <p class="mt-1 text-xs text-gray-500">
                                            <span x-text="charCount"></span>/80 символов
                                        </p>
                                    </div>

                                    <!-- Религия -->
                                    <div>
                                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">
                                            Вероисповедание
                                            <span class="text-xs text-gray-500">(отображается символом в правом верхнем углу)</span>
                                        </label>
                                        <select name="religion" id="religion" class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="none" {{ old('religion', $memorial->religion) == 'none' ? 'selected' : '' }}>Не указано</option>
                                            <option value="orthodox" {{ old('religion', $memorial->religion) == 'orthodox' ? 'selected' : '' }}>Православие</option>
                                            <option value="catholic" {{ old('religion', $memorial->religion) == 'catholic' ? 'selected' : '' }}>Католицизм</option>
                                            <option value="islam" {{ old('religion', $memorial->religion) == 'islam' ? 'selected' : '' }}>Ислам</option>
                                            <option value="judaism" {{ old('religion', $memorial->religion) == 'judaism' ? 'selected' : '' }}>Иудаизм</option>
                                            <option value="buddhism" {{ old('religion', $memorial->religion) == 'buddhism' ? 'selected' : '' }}>Буддизм</option>
                                            <option value="hinduism" {{ old('religion', $memorial->religion) == 'hinduism' ? 'selected' : '' }}>Индуизм</option>
                                            <option value="other" {{ old('religion', $memorial->religion) == 'other' ? 'selected' : '' }}>Другое</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

