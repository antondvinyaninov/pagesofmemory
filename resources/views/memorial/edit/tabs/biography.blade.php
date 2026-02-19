                        <div x-show="activeTab === 'biography'" class="space-y-6">
                            
                            <!-- Блок: О человеке -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">О человеке</h3>
                                    <button type="button" @click="$dispatch('toggle-fullscreen')" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div x-data="biographyEditor()" @toggle-fullscreen.window="toggleFullscreen()">
                                    <div id="biography-editor" class="bg-white border border-blue-200 rounded-lg" style="min-height: 300px;"></div>
                                    <textarea name="full_biography" id="full_biography" x-ref="textarea" class="hidden">{{ old('full_biography', $memorial->full_biography) }}</textarea>
                                    
                                    <!-- Fullscreen overlay -->
                                    <div x-show="isFullscreen" x-cloak class="fixed inset-0 z-50 bg-white flex flex-col" style="display: none;">
                                        <div class="flex items-center justify-between p-4 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                                            <h3 class="text-lg font-semibold text-gray-900">О человеке</h3>
                                            <button type="button" @click="toggleFullscreen()" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex-1 p-6 overflow-auto">
                                            <div id="biography-editor-fullscreen" class="bg-white border border-blue-200 rounded-lg" style="min-height: calc(100vh - 200px);"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Блок: Образование и карьера -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Образование и карьера</h3>
                                
                                <div class="space-y-6">
                                    <!-- Образование -->
                                    <div x-data="educationList()">
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Образование</label>
                                                <p class="text-xs text-gray-500 mt-1">Можно добавить до 5 учебных заведений</p>
                                            </div>
                                            <button type="button" @click="addEducation()" :disabled="educations.length >= 5" :class="educations.length >= 5 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Добавить образование
                                            </button>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <template x-for="(edu, index) in educations" :key="index">
                                                <div class="border border-blue-200 bg-white rounded-lg p-4 relative">
                                                    <button type="button" @click="removeEducation(index)" class="absolute top-2 right-2 text-gray-400 hover:text-blue-600">
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
                                                                class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                            >
                                                        </div>
                                                        <div>
                                                            <input 
                                                                type="text" 
                                                                :name="'education[' + index + '][details]'" 
                                                                x-model="edu.details"
                                                                placeholder="Годы обучения, специальность"
                                                                class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            
                                            <div x-show="educations.length === 0" class="text-center py-8 text-gray-400 border-2 border-dashed border-blue-200 rounded-lg bg-white">
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
                                            <button type="button" @click="addCareer()" :disabled="careers.length >= 5" :class="careers.length >= 5 ? 'opacity-50 cursor-not-allowed' : ''" class="flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Добавить место работы
                                            </button>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <template x-for="(career, index) in careers" :key="index">
                                                <div class="border border-blue-200 bg-white rounded-lg p-4 relative">
                                                    <button type="button" @click="removeCareer(index)" class="absolute top-2 right-2 text-gray-400 hover:text-blue-600">
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
                                                                class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                            >
                                                        </div>
                                                        <div>
                                                            <input 
                                                                type="text" 
                                                                :name="'career[' + index + '][details]'" 
                                                                x-model="career.details"
                                                                placeholder="Место работы, годы работы"
                                                                class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            
                                            <div x-show="careers.length === 0" class="text-center py-8 text-gray-400 border-2 border-dashed border-blue-200 rounded-lg bg-white">
                                                Нажмите "Добавить место работы" чтобы добавить информацию
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $militaryConflictOptions = [
                                    'ww2' => 'Великая Отечественная война (1941-1945)',
                                    'afghanistan' => 'Афганская война (1979-1989)',
                                    'chechnya_1' => 'Первая чеченская война (1994-1996)',
                                    'chechnya_2' => 'Вторая чеченская война (1999-2009)',
                                    'georgia' => 'Война в Южной Осетии (2008)',
                                    'syria' => 'Сирийский конфликт (2015-н.в.)',
                                    'ukraine' => 'Специальная военная операция (2022-н.в.)',
                                ];
                                $savedMilitaryConflicts = collect(old('military_conflicts', is_array($memorial->military_conflicts ?? null) ? $memorial->military_conflicts : []))
                                    ->filter(fn ($value) => is_string($value) && trim($value) !== '')
                                    ->map(fn ($value) => trim($value))
                                    ->values();
                                $savedCustomMilitaryConflicts = collect(old('military_conflicts_custom', $savedMilitaryConflicts->reject(fn ($value) => array_key_exists($value, $militaryConflictOptions))->all()))
                                    ->filter(fn ($value) => is_string($value) && trim($value) !== '')
                                    ->map(fn ($value) => trim($value))
                                    ->values();
                                $savedMilitaryFilesForJs = collect(old('existing_military_files', is_array($memorial->military_files ?? null) ? $memorial->military_files : []))
                                    ->filter(fn ($item) => is_array($item))
                                    ->map(function ($item) {
                                        $path = trim((string) ($item['path'] ?? ''));
                                        if ($path === '') {
                                            return null;
                                        }

                                        $isPdf = strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';

                                        return [
                                            'path' => $path,
                                            'title' => trim((string) ($item['title'] ?? '')),
                                            'url' => filter_var($path, FILTER_VALIDATE_URL) ? $path : s3_url($path),
                                            'isPdf' => $isPdf,
                                        ];
                                    })
                                    ->filter()
                                    ->values()
                                    ->all();
                                $savedAchievementFilesForJs = collect(old('existing_achievement_files', is_array($memorial->achievement_files ?? null) ? $memorial->achievement_files : []))
                                    ->filter(fn ($item) => is_array($item))
                                    ->map(function ($item) {
                                        $path = trim((string) ($item['path'] ?? ''));
                                        if ($path === '') {
                                            return null;
                                        }

                                        $isPdf = strtolower((string) pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';

                                        return [
                                            'path' => $path,
                                            'title' => trim((string) ($item['title'] ?? '')),
                                            'url' => filter_var($path, FILTER_VALIDATE_URL) ? $path : s3_url($path),
                                            'isPdf' => $isPdf,
                                        ];
                                    })
                                    ->filter()
                                    ->values()
                                    ->all();
                                $hasMilitaryData = (bool) old('military_service', $memorial->military_service)
                                    || (bool) old('military_rank', $memorial->military_rank)
                                    || (bool) old('military_years', $memorial->military_years)
                                    || (bool) old('military_details', $memorial->military_details)
                                    || $savedMilitaryConflicts->isNotEmpty()
                                    || $savedCustomMilitaryConflicts->isNotEmpty();
                            @endphp

                            <!-- Блок: Военная служба -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <div x-data="{ hasMilitary: @js($hasMilitaryData) }">
                                    <label class="flex items-center gap-2 cursor-pointer mb-4">
                                        <input 
                                            type="checkbox" 
                                            x-model="hasMilitary"
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        >
                                        <span class="text-lg font-semibold text-gray-900">Военная служба</span>
                                    </label>

                                    <div x-show="hasMilitary" x-transition class="space-y-4 pl-6 border-l-2 border-blue-200">
                                        <div>
                                            <label for="military_service" class="block text-sm font-medium text-gray-700 mb-2">Место службы</label>
                                            <input 
                                                type="text" 
                                                name="military_service" 
                                                id="military_service" 
                                                value="{{ old('military_service', $memorial->military_service) }}" 
                                                placeholder="Часть, подразделение"
                                                class="w-full px-3 py-2 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                        </div>
                                        <div>
                                            <label for="military_rank" class="block text-sm font-medium text-gray-700 mb-2">Звание</label>
                                            <input 
                                                type="text" 
                                                name="military_rank" 
                                                id="military_rank" 
                                                value="{{ old('military_rank', $memorial->military_rank) }}" 
                                                placeholder="Воинское звание"
                                                class="w-full px-3 py-2 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                        </div>
                                        <div>
                                            <label for="military_years" class="block text-sm font-medium text-gray-700 mb-2">Годы службы</label>
                                            <input 
                                                type="text" 
                                                name="military_years" 
                                                id="military_years" 
                                                value="{{ old('military_years', $memorial->military_years) }}" 
                                                placeholder="Например: 1985-1987"
                                                class="w-full px-3 py-2 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                        </div>

                                        <!-- Участие в военных конфликтах -->
                                        <div x-data="militaryConflicts(@js($savedCustomMilitaryConflicts->all()))">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Участие в военных конфликтах</label>
                                            
                                            <div class="space-y-2 mb-3 bg-white p-4 rounded-lg border border-blue-200">
                                                @foreach($militaryConflictOptions as $conflictCode => $conflictLabel)
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <input
                                                            type="checkbox"
                                                            name="military_conflicts[]"
                                                            value="{{ $conflictCode }}"
                                                            @checked($savedMilitaryConflicts->contains($conflictCode))
                                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                        >
                                                        <span class="text-sm text-gray-700">{{ $conflictLabel }}</span>
                                                    </label>
                                                @endforeach
                                                
                                                <!-- Другие конфликты -->
                                                <template x-for="(conflict, index) in customConflicts" :key="index">
                                                    <div class="flex items-center gap-2">
                                                        <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                        <input 
                                                            type="text" 
                                                            :name="'military_conflicts_custom[' + index + ']'"
                                                            x-model="conflict.name"
                                                            placeholder="Название конфликта"
                                                            class="flex-1 px-2 py-1 text-sm border border-blue-200 rounded focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                        >
                                                        <button type="button" @click="removeConflict(index)" class="text-gray-400 hover:text-blue-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                            
                                            <button type="button" @click="addCustomConflict()" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Добавить другой конфликт
                                            </button>
                                        </div>

                                        <div>
                                            <label for="military_details" class="block text-sm font-medium text-gray-700 mb-2">Дополнительная информация</label>
                                            <textarea 
                                                name="military_details" 
                                                id="military_details" 
                                                rows="2" 
                                                placeholder="Особые заслуги, награды за участие в боевых действиях..."
                                                class="w-full px-3 py-2 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >{{ old('military_details', $memorial->military_details) }}</textarea>
                                        </div>

                                        <!-- Загрузка документов военной службы -->
                                        <div x-data="militaryFilesList(@js($savedMilitaryFilesForJs))">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Фото документов, наград, удостоверений</label>
                                            
                                            <div class="flex gap-3 overflow-x-auto pb-2">
                                                <!-- Кнопка добавления -->
                                                <div class="flex-shrink-0">
                                                    <button type="button" @click="addFile()" class="w-24 h-32 border-2 border-dashed border-blue-300 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-blue-500 hover:bg-blue-50 transition-colors bg-white">
                                                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        <span class="text-xs text-gray-500">Добавить</span>
                                                    </button>
                                                </div>

                                                <!-- Превью загруженных файлов -->
                                                <template x-for="(item, index) in files" :key="index">
                                                    <div class="flex-shrink-0 relative group">
                                                        <div class="w-24 h-32 bg-gray-100 rounded-xl overflow-hidden border-2 border-blue-200 relative">
                                                            <!-- Превью изображения -->
                                                            <div x-show="item.preview && !item.isPdf" class="w-full h-full">
                                                                <img :src="item.preview" class="w-full h-full object-cover">
                                                            </div>
                                                            
                                                            <!-- Иконка PDF -->
                                                            <div x-show="!item.preview && item.isPdf" class="w-full h-full flex items-center justify-center bg-blue-50">
                                                                <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                                                    <path d="M14 2v6h6M10 13h4M10 17h4M10 9h1"/>
                                                                </svg>
                                                            </div>

                                                            <!-- Кнопка удаления -->
                                                            <button type="button" @click="removeFile(index)" class="absolute top-1 right-1 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
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

                                                            <input
                                                                type="hidden"
                                                                :name="item.path ? 'existing_military_files[' + index + '][path]' : ''"
                                                                :value="item.path || ''"
                                                            >
                                                        </div>

                                                        <!-- Название под превью -->
                                                        <input 
                                                            type="text" 
                                                            :name="item.path ? 'existing_military_files[' + index + '][title]' : 'military_files[' + index + '][title]'" 
                                                            x-model="item.title"
                                                            placeholder="Название"
                                                            class="mt-2 w-24 px-2 py-1 text-xs text-center border border-blue-200 rounded focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                        >
                                                    </div>
                                                </template>
                                            </div>
                                            
                                            <p class="mt-2 text-xs text-gray-500">Нажмите "+" чтобы добавить фото или PDF</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Блок: Личные качества -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Личные качества</h3>
                                
                                <div class="space-y-5">
                                    <!-- Увлечения -->
                                    <div>
                                        <label for="hobbies" class="block text-sm font-medium text-gray-700 mb-2">Увлечения</label>
                                        <textarea name="hobbies" id="hobbies" rows="3" placeholder="Каждое увлечение с новой строки" class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('hobbies', $memorial->hobbies) }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Укажите каждое увлечение с новой строки</p>
                                    </div>

                                    <!-- Черты характера -->
                                    <div>
                                        <label for="character_traits" class="block text-sm font-medium text-gray-700 mb-2">Черты характера</label>
                                        <textarea name="character_traits" id="character_traits" rows="3" placeholder="Каждая черта с новой строки" class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('character_traits', $memorial->character_traits) }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Укажите каждую черту характера с новой строки</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Блок: Достижения и награды -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Достижения и награды</h3>
                                
                                <div class="space-y-5">
                                    <div>
                                        <textarea name="achievements" id="achievements" rows="4" placeholder="Ордена, медали, звания, профессиональные награды..." class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('achievements', $memorial->achievements) }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Укажите значимые достижения и полученные награды</p>
                                    </div>

                                    <!-- Загрузка документов наград -->
                                    <div x-data="achievementsList(@js($savedAchievementFilesForJs))">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Фото достижений, наград, орденов, заслуг, писем</label>
                                        
                                        <div class="flex gap-3 overflow-x-auto pb-2">
                                            <!-- Кнопка добавления -->
                                            <div class="flex-shrink-0">
                                                <button type="button" @click="addAchievementFile()" class="w-24 h-32 border-2 border-dashed border-blue-300 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-blue-500 hover:bg-blue-50 transition-colors bg-white">
                                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    <span class="text-xs text-gray-500">Добавить</span>
                                                </button>
                                            </div>

                                            <!-- Превью загруженных файлов -->
                                            <template x-for="(item, index) in files" :key="index">
                                                <div class="flex-shrink-0 relative group">
                                                    <div class="w-24 h-32 bg-gray-100 rounded-xl overflow-hidden border-2 border-blue-200 relative">
                                                        <!-- Превью изображения -->
                                                        <div x-show="item.preview && !item.isPdf" class="w-full h-full">
                                                            <img :src="item.preview" class="w-full h-full object-cover">
                                                        </div>
                                                        
                                                        <!-- Иконка PDF -->
                                                        <div x-show="!item.preview && item.isPdf" class="w-full h-full flex items-center justify-center bg-blue-50">
                                                            <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                                                <path d="M14 2v6h6M10 13h4M10 17h4M10 9h1"/>
                                                            </svg>
                                                        </div>

                                                        <!-- Кнопка удаления -->
                                                        <button type="button" @click="removeFile(index)" class="absolute top-1 right-1 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
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

                                                        <input
                                                            type="hidden"
                                                            :name="item.path ? 'existing_achievement_files[' + index + '][path]' : ''"
                                                            :value="item.path || ''"
                                                        >
                                                    </div>

                                                    <!-- Название под превью -->
                                                    <input 
                                                        type="text" 
                                                        :name="item.path ? 'existing_achievement_files[' + index + '][title]' : 'achievement_files[' + index + '][title]'" 
                                                        x-model="item.title"
                                                        placeholder="Название"
                                                        class="mt-2 w-24 px-2 py-1 text-xs text-center border border-blue-200 rounded focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                                    >
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <p class="mt-2 text-xs text-gray-500">Нажмите "+" чтобы добавить фото или PDF</p>
                                    </div>
                                </div>
                            </div>
                        </div>
