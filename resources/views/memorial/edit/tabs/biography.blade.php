                        <div x-show="activeTab === 'biography'" class="space-y-6">
                            <!-- О человеке -->
                            <div>
                                <label for="full_biography" class="block text-sm font-medium text-gray-700 mb-2">О человеке</label>
                                <textarea name="full_biography" id="full_biography" rows="15" placeholder="Расскажите о жизни человека, его достижениях, характере, увлечениях..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('full_biography', $memorial->full_biography) }}</textarea>
                            </div>

                            <!-- Образование -->
                            <div x-data="educationList()">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Образование</label>
                                    <button type="button" @click="addEducation()" class="flex items-center gap-1 text-sm text-red-600 hover:text-red-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Добавить образование
                                    </button>
                                </div>
                                
                                <div class="space-y-4">
                                    <template x-for="(edu, index) in educations" :key="index">
                                        <div class="border border-gray-200 rounded-lg p-4 relative">
                                            <button type="button" @click="removeEducation(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            
                                            <div class="space-y-3">
                                                <div>
                                                    <input 
                                                        type="text" 
                                                        :name="'education[' + index + '][name]'" 
                                                        x-model="edu.name"
                                                        placeholder="Название учебного заведения"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                    >
                                                </div>
                                                <div>
                                                    <input 
                                                        type="text" 
                                                        :name="'education[' + index + '][details]'" 
                                                        x-model="edu.details"
                                                        placeholder="Годы обучения, специальность"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <div x-show="educations.length === 0" class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                                        Нажмите "Добавить образование" чтобы добавить информацию
                                    </div>
                                </div>
                            </div>

                            <!-- Карьера -->
                            <div x-data="careerList()">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Работа</label>
                                        <p class="text-xs text-gray-500 mt-1">Укажите значимые или последние места работы (не более 5)</p>
                                    </div>
                                    <button type="button" @click="addCareer()" :disabled="careers.length >= 5" :class="careers.length >= 5 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-1 text-sm text-red-600 hover:text-red-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Добавить место работы
                                    </button>
                                </div>
                                
                                <div class="space-y-4">
                                    <template x-for="(career, index) in careers" :key="index">
                                        <div class="border border-gray-200 rounded-lg p-4 relative">
                                            <button type="button" @click="removeCareer(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            
                                            <div class="space-y-3">
                                                <div>
                                                    <input 
                                                        type="text" 
                                                        :name="'career[' + index + '][position]'" 
                                                        x-model="career.position"
                                                        placeholder="Должность"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                    >
                                                </div>
                                                <div>
                                                    <input 
                                                        type="text" 
                                                        :name="'career[' + index + '][details]'" 
                                                        x-model="career.details"
                                                        placeholder="Место работы, годы работы"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <div x-show="careers.length === 0" class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                                        Нажмите "Добавить место работы" чтобы добавить информацию
                                    </div>
                                </div>
                            </div>

                            <!-- Военная служба -->
                            <div x-data="{ hasMilitary: false }">
                                <label class="flex items-center gap-2 cursor-pointer mb-3">
                                    <input 
                                        type="checkbox" 
                                        x-model="hasMilitary"
                                        class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                    >
                                    <span class="text-sm font-medium text-gray-700">Проходил военную службу</span>
                                </label>

                                <div x-show="hasMilitary" x-transition class="space-y-3 pl-6 border-l-2 border-gray-200">
                                    <div>
                                        <label for="military_service" class="block text-sm text-gray-700 mb-1">Место службы</label>
                                        <input 
                                            type="text" 
                                            name="military_service" 
                                            id="military_service" 
                                            value="{{ old('military_service', $memorial->military_service) }}" 
                                            placeholder="Часть, подразделение"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        >
                                    </div>
                                    <div>
                                        <label for="military_rank" class="block text-sm text-gray-700 mb-1">Звание</label>
                                        <input 
                                            type="text" 
                                            name="military_rank" 
                                            id="military_rank" 
                                            value="{{ old('military_rank', $memorial->military_rank) }}" 
                                            placeholder="Воинское звание"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        >
                                    </div>
                                    <div>
                                        <label for="military_years" class="block text-sm text-gray-700 mb-1">Годы службы</label>
                                        <input 
                                            type="text" 
                                            name="military_years" 
                                            id="military_years" 
                                            value="{{ old('military_years', $memorial->military_years) }}" 
                                            placeholder="Например: 1985-1987"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        >
                                    </div>

                                    <!-- Участие в военных конфликтах -->
                                    <div x-data="militaryConflicts()">
                                        <label class="block text-sm text-gray-700 mb-2">Участие в военных конфликтах</label>
                                        
                                        <div class="space-y-2 mb-3">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="military_conflicts[]" value="ww2" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                <span class="text-sm text-gray-700">Великая Отечественная война (1941-1945)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="military_conflicts[]" value="afghanistan" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                <span class="text-sm text-gray-700">Афганская война (1979-1989)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="military_conflicts[]" value="chechnya_1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                <span class="text-sm text-gray-700">Первая чеченская война (1994-1996)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="military_conflicts[]" value="chechnya_2" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                <span class="text-sm text-gray-700">Вторая чеченская война (1999-2009)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="military_conflicts[]" value="georgia" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                <span class="text-sm text-gray-700">Война в Южной Осетии (2008)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="military_conflicts[]" value="syria" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                <span class="text-sm text-gray-700">Сирийский конфликт (2015-н.в.)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="military_conflicts[]" value="ukraine" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                <span class="text-sm text-gray-700">Специальная военная операция (2022-н.в.)</span>
                                            </label>
                                            
                                            <!-- Другие конфликты -->
                                            <template x-for="(conflict, index) in customConflicts" :key="index">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" checked class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                    <input 
                                                        type="text" 
                                                        :name="'military_conflicts_custom[' + index + ']'"
                                                        x-model="conflict.name"
                                                        placeholder="Название конфликта"
                                                        class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-red-500 focus:border-transparent"
                                                    >
                                                    <button type="button" @click="removeConflict(index)" class="text-gray-400 hover:text-red-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <button type="button" @click="addCustomConflict()" class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Добавить другой конфликт
                                        </button>
                                    </div>

                                    <div>
                                        <label for="military_details" class="block text-sm text-gray-700 mb-1">Дополнительная информация</label>
                                        <textarea 
                                            name="military_details" 
                                            id="military_details" 
                                            rows="2" 
                                            placeholder="Особые заслуги, награды за участие в боевых действиях..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        >{{ old('military_details', $memorial->military_details) }}</textarea>
                                    </div>

                                    <!-- Загрузка документов военной службы -->
                                    <div x-data="militaryFilesList()">
                                        <label class="block text-sm text-gray-700 mb-2">Фото документов, наград, удостоверений</label>
                                        
                                        <div class="flex gap-3 overflow-x-auto pb-2">
                                            <!-- Кнопка добавления -->
                                            <div class="flex-shrink-0">
                                                <button type="button" @click="addFile()" class="w-24 h-32 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-red-500 hover:bg-red-50 transition-colors">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    <span class="text-xs text-gray-500">Добавить</span>
                                                </button>
                                            </div>

                                            <!-- Превью загруженных файлов -->
                                            <template x-for="(item, index) in files" :key="index">
                                                <div class="flex-shrink-0 relative group">
                                                    <div class="w-24 h-32 bg-gray-100 rounded-xl overflow-hidden border-2 border-gray-200 relative">
                                                        <!-- Превью изображения -->
                                                        <div x-show="item.preview" class="w-full h-full">
                                                            <img :src="item.preview" class="w-full h-full object-cover">
                                                        </div>
                                                        
                                                        <!-- Иконка PDF -->
                                                        <div x-show="!item.preview && item.isPdf" class="w-full h-full flex items-center justify-center bg-red-50">
                                                            <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                                                <path d="M14 2v6h6M10 13h4M10 17h4M10 9h1"/>
                                                            </svg>
                                                        </div>

                                                        <!-- Кнопка удаления -->
                                                        <button type="button" @click="removeFile(index)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>

                                                        <!-- Скрытый input для файла -->
                                                        <input 
                                                            type="file" 
                                                            :id="'military_file_' + index"
                                                            :name="'military_files[' + index + '][file]'" 
                                                            accept="image/*,.pdf"
                                                            @change="handleFilePreview($event, index)"
                                                            class="hidden"
                                                        >
                                                    </div>

                                                    <!-- Название под превью -->
                                                    <input 
                                                        type="text" 
                                                        :name="'military_files[' + index + '][title]'" 
                                                        x-model="item.title"
                                                        placeholder="Название"
                                                        class="mt-2 w-24 px-2 py-1 text-xs text-center border border-gray-300 rounded focus:ring-1 focus:ring-red-500 focus:border-transparent"
                                                    >
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <p class="mt-2 text-xs text-gray-500">Нажмите "+" чтобы добавить фото или PDF</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Увлечения -->
                            <div>
                                <label for="hobbies" class="block text-sm font-medium text-gray-700 mb-2">Увлечения</label>
                                <textarea name="hobbies" id="hobbies" rows="3" placeholder="Каждое увлечение с новой строки" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('hobbies', $memorial->hobbies) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Укажите каждое увлечение с новой строки</p>
                            </div>

                            <!-- Черты характера -->
                            <div>
                                <label for="character_traits" class="block text-sm font-medium text-gray-700 mb-2">Черты характера</label>
                                <textarea name="character_traits" id="character_traits" rows="3" placeholder="Каждая черта с новой строки" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('character_traits', $memorial->character_traits) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Укажите каждую черту характера с новой строки</p>
                            </div>

                            <!-- Достижения и награды -->
                            <div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Достижения и награды</label>
                                    <textarea name="achievements" id="achievements" rows="4" placeholder="Ордена, медали, звания, профессиональные награды..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('achievements', $memorial->achievements) }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Укажите значимые достижения и полученные награды</p>
                                </div>

                                <!-- Загрузка документов наград (формат сторис) -->
                                <div class="mt-4" x-data="achievementsList()">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Фото достижений, наград, орденов, заслуг, писем</label>
                                    
                                    <div class="flex gap-3 overflow-x-auto pb-2">
                                        <!-- Кнопка добавления -->
                                        <div class="flex-shrink-0">
                                            <button type="button" @click="addAchievementFile()" class="w-24 h-32 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-red-500 hover:bg-red-50 transition-colors">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                <span class="text-xs text-gray-500">Добавить</span>
                                            </button>
                                        </div>

                                        <!-- Превью загруженных файлов -->
                                        <template x-for="(item, index) in files" :key="index">
                                            <div class="flex-shrink-0 relative group">
                                                <div class="w-24 h-32 bg-gray-100 rounded-xl overflow-hidden border-2 border-gray-200 relative">
                                                    <!-- Превью изображения -->
                                                    <div x-show="item.preview" class="w-full h-full">
                                                        <img :src="item.preview" class="w-full h-full object-cover">
                                                    </div>
                                                    
                                                    <!-- Иконка PDF -->
                                                    <div x-show="!item.preview && item.isPdf" class="w-full h-full flex items-center justify-center bg-red-50">
                                                        <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                                            <path d="M14 2v6h6M10 13h4M10 17h4M10 9h1"/>
                                                        </svg>
                                                    </div>

                                                    <!-- Кнопка удаления -->
                                                    <button type="button" @click="removeFile(index)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Скрытый input для файла -->
                                                    <input 
                                                        type="file" 
                                                        :id="'achievement_file_' + index"
                                                        :name="'achievement_files[' + index + '][file]'" 
                                                        accept="image/*,.pdf"
                                                        @change="handleFilePreview($event, index)"
                                                        class="hidden"
                                                    >
                                                </div>

                                                <!-- Название под превью -->
                                                <input 
                                                    type="text" 
                                                    :name="'achievement_files[' + index + '][title]'" 
                                                    x-model="item.title"
                                                    placeholder="Название"
                                                    class="mt-2 w-24 px-2 py-1 text-xs text-center border border-gray-300 rounded focus:ring-1 focus:ring-red-500 focus:border-transparent"
                                                >
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <p class="mt-2 text-xs text-gray-500">Нажмите "+" чтобы добавить фото или PDF</p>
                                </div>
                            </div>
                        </div>

