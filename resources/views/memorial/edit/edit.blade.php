@extends('layouts.app')

@section('title', $memorial->exists ? 'Редактировать мемориал' : 'Создать мемориал')

@section('content')
<div class="bg-gray-200 min-h-screen py-6" x-data="{ activeTab: 'basic' }">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Основной контент -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h1 class="text-2xl font-bold text-slate-700 mb-6">
                        {{ $memorial->exists ? 'Редактировать мемориал' : 'Создать мемориал' }}
                    </h1>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ $memorial->exists ? route('memorial.update', $memorial->id) : route('memorial.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @if($memorial->exists)
                            @method('PUT')
                        @endif

                        <!-- Вкладка: Основное -->
                        <div x-show="activeTab === 'basic'" class="space-y-6">
                            <!-- Фото -->
                            <div x-data="photoUpload()">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Фото *</label>
                                <div class="flex items-start gap-6">
                                    <div class="relative">
                                        <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                            <img x-show="previewUrl" :src="previewUrl" class="w-full h-full object-cover">
                                            <svg x-show="!previewUrl" class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input 
                                            type="file" 
                                            name="photo" 
                                            id="photo" 
                                            accept="image/*,.heic,.heif" 
                                            @change="handleFileSelect($event)"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                                        >
                                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, WEBP, HEIC до 10MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Фамилия -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Фамилия *</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $memorial->last_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>

                            <!-- Имя -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Имя *</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $memorial->first_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>

                            <!-- Отчество -->
                            <div>
                                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Отчество</label>
                                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $memorial->middle_name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>

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
                                    maxlength="100"
                                    @input="charCount = $event.target.value.length"
                                    placeholder="Любящий муж, отец и дедушка / Любящая жена, мать и бабушка..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                >
                                <p class="mt-1 text-xs text-gray-500">
                                    <span x-text="charCount"></span>/100 символов
                                </p>
                            </div>

                            <!-- Религия -->
                            <div>
                                <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Вероисповедание
                                    <span class="text-xs text-gray-500">(отображается символом в правом верхнем углу)</span>
                                </label>
                                <select name="religion" id="religion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
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

                            <!-- Дата рождения -->
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Дата рождения *</label>
                                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $memorial->birth_date?->format('Y-m-d')) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>

                            <!-- Дата смерти -->
                            <div>
                                <label for="death_date" class="block text-sm font-medium text-gray-700 mb-2">Дата смерти *</label>
                                <input type="date" name="death_date" id="death_date" value="{{ old('death_date', $memorial->death_date?->format('Y-m-d')) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>

                            <!-- Место рождения -->
                            <div x-data="birthPlaceAutocomplete()">
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
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    >
                                    <input type="hidden" name="birth_place" :value="selectedCity" x-init="console.log('Скрытое поле birth_place инициализировано, значение:', selectedCity)">
                                    
                                    <!-- Список подсказок -->
                                    <div x-show="showSuggestions && suggestions.length > 0" 
                                         @click.away="showSuggestions = false"
                                         class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="suggestion in suggestions" :key="suggestion.value">
                                            <div @click="selectCity(suggestion)" 
                                                 class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                                <div class="font-medium text-gray-900" x-text="suggestion.data.city"></div>
                                                <div class="text-sm text-gray-500" x-text="suggestion.data.region"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Вкладка: Биография -->
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

                        <!-- Вкладка: Захоронение -->
                        <div x-show="activeTab === 'burial'" class="space-y-6">
                            <!-- Город захоронения -->
                            <div x-data="burialCityAutocomplete()">
                                <label for="burial_city" class="block text-sm font-medium text-gray-700 mb-2">Город захоронения</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="burial_city_input"
                                        @input="searchCity($event.target.value); selectedCity = $event.target.value"
                                        @focus="showSuggestions = suggestions.length > 0"
                                        placeholder="Начните вводить город..."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    >
                                    <input type="hidden" name="burial_city" :value="selectedCity">
                                    
                                    <!-- Список подсказок -->
                                    <div x-show="showSuggestions && suggestions.length > 0" 
                                         @click.away="showSuggestions = false"
                                         class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="suggestion in suggestions" :key="suggestion.value">
                                            <div @click="selectCity(suggestion)" 
                                                 class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                                <div class="font-medium text-gray-900" x-text="suggestion.data.city"></div>
                                                <div class="text-sm text-gray-500" x-text="suggestion.data.region"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Место захоронения -->
                            <div x-data="burialMap()">
                                <label for="burial_place" class="block text-sm font-medium text-gray-700 mb-2">Место захоронения</label>
                                <input 
                                    type="text" 
                                    name="burial_place" 
                                    id="burial_place" 
                                    value="{{ old('burial_place', $memorial->burial_place) }}" 
                                    placeholder="Название кладбища" 
                                    @input="searchCemetery($event.target.value)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                >
                                
                                <!-- Кнопка показать карту -->
                                <div class="mt-4" x-show="!mapVisible">
                                    <button 
                                        type="button"
                                        @click="showMap()"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        Показать карту для точного указания места
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">Сначала укажите город захоронения выше</p>
                                </div>
                                
                                <!-- Карта -->
                                <div class="mt-4" x-show="mapVisible" x-transition>
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-700">Точное местоположение на карте</span>
                                        <p class="text-xs text-gray-500 mt-1">Кликните на карте для установки метки</p>
                                    </div>
                                    
                                    <div id="burial-map" class="w-full h-96 rounded-lg border-2 border-gray-300"></div>
                                    
                                    <button 
                                        type="button"
                                        @click="hideMap()"
                                        class="mt-2 px-4 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition-colors"
                                    >
                                        Скрыть карту
                                    </button>
                                </div>
                                
                                <!-- Скрытые поля для координат -->
                                <input type="hidden" name="burial_latitude" id="burial_latitude" :value="latitude">
                                <input type="hidden" name="burial_longitude" id="burial_longitude" :value="longitude">
                            </div>

                            <!-- Адрес -->
                            <div>
                                <label for="burial_address" class="block text-sm font-medium text-gray-700 mb-2">Адрес</label>
                                <input type="text" name="burial_address" id="burial_address" value="{{ old('burial_address', $memorial->burial_address) }}" placeholder="Полный адрес кладбища" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>

                            <!-- Расположение на кладбище -->
                            <div>
                                <label for="burial_location" class="block text-sm font-medium text-gray-700 mb-2">Расположение на кладбище</label>
                                <input type="text" name="burial_location" id="burial_location" value="{{ old('burial_location', $memorial->burial_location) }}" placeholder="Участок, ряд, место" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>

                            <!-- Фото места захоронения -->
                            <div x-data="burialPhotos()">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Фото места захоронения</label>
                                <p class="text-xs text-gray-500 mb-3">Фотографии могилы, памятника, надгробия</p>
                                
                                <div class="flex gap-3 overflow-x-auto pb-2">
                                    <!-- Кнопка добавления -->
                                    <div class="flex-shrink-0">
                                        <button type="button" @click="addPhoto()" class="w-32 h-40 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-red-500 hover:bg-red-50 transition-colors">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span class="text-xs text-gray-500">Добавить фото</span>
                                        </button>
                                    </div>

                                    <!-- Превью загруженных фото -->
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <div class="flex-shrink-0 relative group">
                                            <div class="w-32 h-40 bg-gray-100 rounded-xl overflow-hidden border-2 border-gray-200 relative">
                                                <!-- Превью изображения -->
                                                <div x-show="photo.preview" class="w-full h-full">
                                                    <img :src="photo.preview" class="w-full h-full object-cover">
                                                </div>

                                                <!-- Кнопка удаления -->
                                                <button type="button" @click="removePhoto(index)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>

                                                <!-- Скрытый input для файла (только для новых фото) -->
                                                <template x-if="!photo.existing">
                                                    <input 
                                                        type="file" 
                                                        :id="'burial_photo_' + index"
                                                        :name="'burial_photos[' + index + ']'" 
                                                        accept="image/*,.heic,.heif"
                                                        @change="handlePhotoPreview($event, index)"
                                                        class="hidden"
                                                    >
                                                </template>
                                                
                                                <!-- Скрытое поле для сохранения URL существующих фото -->
                                                <template x-if="photo.existing">
                                                    <input 
                                                        type="hidden" 
                                                        :name="'existing_burial_photos[' + index + ']'" 
                                                        :value="photo.url"
                                                    >
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <p class="mt-2 text-xs text-gray-500">JPG, PNG, WEBP, HEIC до 10MB каждое</p>
                            </div>
                        </div>

                        <!-- Вкладка: Медиа -->
                        <div x-show="activeTab === 'media'" class="space-y-6">
                            <div class="text-center py-12 text-gray-500">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p>Дополнительные фото и видео можно будет добавить после создания мемориала</p>
                            </div>
                        </div>

                        <!-- Вкладка: Близкие люди -->
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

                        <!-- Кнопки -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" name="action" value="publish" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                Опубликовать
                            </button>
                            <button type="submit" name="action" value="draft" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                Сохранить как черновик
                            </button>
                            <a href="{{ route('user.show', ['id' => Auth::id()]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg transition-colors font-medium">
                                Отмена
                            </a>
                        </div>
                    </form>
                </div>
            </main>

            <!-- Боковая панель справа с навигацией -->
            <aside class="lg:w-80 lg:sticky lg:top-4 lg:h-fit">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <nav class="p-4">
                        <button @click="activeTab = 'basic'" :class="activeTab === 'basic' ? 'text-gray-700 bg-red-50' : 'text-gray-600 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Основное
                        </button>
                        <button @click="activeTab = 'biography'" :class="activeTab === 'biography' ? 'text-gray-700 bg-red-50' : 'text-gray-600 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Биография
                        </button>
                        <button @click="activeTab = 'burial'" :class="activeTab === 'burial' ? 'text-gray-700 bg-red-50' : 'text-gray-600 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Захоронение
                        </button>
                        <button @click="activeTab = 'media'" :class="activeTab === 'media' ? 'text-gray-700 bg-red-50' : 'text-gray-600 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Медиа
                        </button>
                        <button @click="activeTab = 'people'" :class="activeTab === 'people' ? 'text-gray-700 bg-red-50' : 'text-gray-600 hover:bg-gray-50'" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Близкие люди
                        </button>
                    </nav>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
function photoUpload() {
    return {
        previewUrl: '',
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}

function educationList() {
    return {
        educations: [],
        
        addEducation() {
            this.educations.push({
                name: '',
                details: ''
            });
        },
        
        removeEducation(index) {
            this.educations.splice(index, 1);
        }
    }
}

function careerList() {
    return {
        careers: [],
        
        addCareer() {
            if (this.careers.length < 5) {
                this.careers.push({
                    position: '',
                    details: ''
                });
            }
        },
        
        removeCareer(index) {
            this.careers.splice(index, 1);
        }
    }
}

function achievementsList() {
    return {
        files: [],
        
        addAchievementFile() {
            const index = this.files.length;
            this.files.push({
                title: '',
                preview: null,
                isPdf: false
            });
            
            // Автоматически открываем диалог выбора файла
            this.$nextTick(() => {
                document.getElementById('achievement_file_' + index).click();
            });
        },
        
        removeFile(index) {
            this.files.splice(index, 1);
        },
        
        handleFilePreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                this.removeFile(index);
                return;
            }
            
            // Проверяем тип файла
            if (file.type === 'application/pdf') {
                this.files[index].isPdf = true;
                this.files[index].preview = null;
            } else if (file.type.startsWith('image/')) {
                this.files[index].isPdf = false;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.files[index].preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

function militaryConflicts() {
    return {
        customConflicts: [],
        
        addCustomConflict() {
            this.customConflicts.push({
                name: ''
            });
        },
        
        removeConflict(index) {
            this.customConflicts.splice(index, 1);
        }
    }
}

function militaryFilesList() {
    return {
        files: [],
        
        addFile() {
            const index = this.files.length;
            this.files.push({
                title: '',
                preview: null,
                isPdf: false
            });
            
            // Автоматически открываем диалог выбора файла
            this.$nextTick(() => {
                document.getElementById('military_file_' + index).click();
            });
        },
        
        removeFile(index) {
            this.files.splice(index, 1);
        },
        
        handleFilePreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                this.removeFile(index);
                return;
            }
            
            // Проверяем тип файла
            if (file.type === 'application/pdf') {
                this.files[index].isPdf = true;
                this.files[index].preview = null;
            } else if (file.type.startsWith('image/')) {
                this.files[index].isPdf = false;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.files[index].preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

function birthPlaceAutocomplete() {
    return {
        suggestions: [],
        showSuggestions: false,
        selectedCity: '{{ old('birth_place', $memorial->birth_place ?? '') }}',
        
        init() {
            console.log('=== birthPlaceAutocomplete ИНИЦИАЛИЗИРОВАН ===');
            const inputField = document.getElementById('birth_place_input');
            console.log('Поле birth_place_input найдено:', inputField);
            console.log('Начальное значение selectedCity:', this.selectedCity);
            
            // Инициализируем видимое поле значением из БД
            if (inputField && this.selectedCity) {
                inputField.value = this.selectedCity;
                console.log('Установлено значение из БД в видимое поле:', this.selectedCity);
            }
        },
        
        async searchCity(query) {
            console.log('searchCity вызван, query:', query);
            console.log('selectedCity перед поиском:', this.selectedCity);
            
            if (query.length < 2) {
                this.suggestions = [];
                console.log('Запрос слишком короткий, очищаем подсказки');
                return;
            }
            
            try {
                console.log('Отправляем запрос в DaData...');
                const response = await fetch('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Token 300ba9e25ef32f0d6ea7c41826b2255b138e19e2'
                    },
                    body: JSON.stringify({
                        query: query,
                        from_bound: { value: 'city' },
                        to_bound: { value: 'city' },
                        locations: [{ country: '*' }]
                    })
                });
                
                const data = await response.json();
                console.log('Получены подсказки от DaData:', data.suggestions);
                this.suggestions = data.suggestions;
                this.showSuggestions = true;
                console.log('showSuggestions установлен в true');
            } catch (error) {
                console.error('Ошибка поиска города:', error);
            }
        },
        
        selectCity(suggestion) {
            console.log('selectCity вызван, suggestion:', suggestion);
            const city = suggestion.data.city || suggestion.data.settlement;
            const region = suggestion.data.region_with_type;
            const fullAddress = `${city}, ${region}`;
            
            console.log('Формируем адрес:', fullAddress);
            document.getElementById('birth_place_input').value = fullAddress;
            this.selectedCity = fullAddress;
            this.showSuggestions = false;
            
            console.log('Значение установлено:');
            console.log('  - birth_place_input.value:', document.getElementById('birth_place_input').value);
            console.log('  - selectedCity:', this.selectedCity);
            console.log('  - скрытое поле birth_place:', document.querySelector('input[name="birth_place"]')?.value);
        }
    }
}

function burialPhotos() {
    return {
        photos: [],
        existingPhotos: @json($memorial->burial_photos ?? []),
        s3Endpoint: '{{ config('filesystems.disks.s3.endpoint') }}',
        s3Bucket: '{{ config('filesystems.disks.s3.bucket') }}',
        
        init() {
            console.log('burialPhotos инициализирован');
            console.log('Существующие фото из БД:', this.existingPhotos);
            console.log('S3 Endpoint:', this.s3Endpoint);
            console.log('S3 Bucket:', this.s3Bucket);
            
            // Загружаем существующие фото
            if (this.existingPhotos && this.existingPhotos.length > 0) {
                this.existingPhotos.forEach((photoPath) => {
                    // Формируем полный URL для S3
                    let photoUrl;
                    if (photoPath.startsWith('http')) {
                        photoUrl = photoPath;
                    } else {
                        // Убираем начальный слеш если есть
                        const cleanPath = photoPath.startsWith('/') ? photoPath.substring(1) : photoPath;
                        photoUrl = `${this.s3Endpoint}/${this.s3Bucket}/${cleanPath}`;
                    }
                    
                    console.log('Загружаем фото:', photoUrl);
                    
                    this.photos.push({
                        preview: photoUrl,
                        existing: true,
                        url: photoPath // Сохраняем путь, а не URL
                    });
                });
                console.log('Загружено фото:', this.photos.length);
            }
        },
        
        addPhoto() {
            const index = this.photos.length;
            this.photos.push({
                preview: null,
                existing: false
            });
            
            // Автоматически открываем диалог выбора файла
            this.$nextTick(() => {
                document.getElementById('burial_photo_' + index).click();
            });
        },
        
        removePhoto(index) {
            console.log('Удаление фото:', index, this.photos[index]);
            this.photos.splice(index, 1);
        },
        
        handlePhotoPreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                this.removePhoto(index);
                return;
            }
            
            // Проверяем размер файла (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер: 10MB');
                this.removePhoto(index);
                return;
            }
            
            console.log('Обработка нового фото:', file.name);
            
            // Создаем превью
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photos[index].preview = e.target.result;
                console.log('Превью создано для индекса:', index);
            };
            reader.readAsDataURL(file);
        }
    }
}

function burialCityAutocomplete() {
    return {
        suggestions: [],
        showSuggestions: false,
        selectedCity: '{{ old('burial_city', $memorial->burial_city ?? '') }}',
        
        init() {
            // Инициализируем видимое поле значением из БД
            const inputField = document.getElementById('burial_city_input');
            if (inputField && this.selectedCity) {
                inputField.value = this.selectedCity;
            }
        },
        
        async searchCity(query) {
            if (query.length < 2) {
                this.suggestions = [];
                return;
            }
            
            try {
                const response = await fetch('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Token 300ba9e25ef32f0d6ea7c41826b2255b138e19e2'
                    },
                    body: JSON.stringify({
                        query: query,
                        from_bound: { value: 'city' },
                        to_bound: { value: 'city' },
                        locations: [{ country: '*' }]
                    })
                });
                
                const data = await response.json();
                this.suggestions = data.suggestions;
                this.showSuggestions = true;
            } catch (error) {
                console.error('Ошибка поиска города:', error);
            }
        },
        
        selectCity(suggestion) {
            const city = suggestion.data.city || suggestion.data.settlement;
            const region = suggestion.data.region_with_type;
            
            document.getElementById('burial_city_input').value = `${city}, ${region}`;
            this.selectedCity = `${city}, ${region}`;
            this.showSuggestions = false;
        }
    }
}
</script>
@endsection


<!-- Яндекс.Карты API -->
<script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_MAPS_API_KEY') }}&lang=ru_RU" type="text/javascript"></script>

<script type="text/javascript">
    var myMap, myPlacemark;
    
    function burialMap() {
        return {
            mapVisible: false,
            mapInitialized: false,
            latitude: {{ old('burial_latitude', $memorial->burial_latitude ?? 55.751244) }},
            longitude: {{ old('burial_longitude', $memorial->burial_longitude ?? 37.618423) }},
            
            init() {
                // Карта всегда скрыта по умолчанию
                console.log('burialMap инициализирован, карта скрыта');
            },
            
            showMap() {
                this.mapVisible = true;
                
                // Инициализируем карту только один раз
                if (!this.mapInitialized) {
                    ymaps.ready(() => {
                        this.initMap();
                    });
                } else {
                    // Если карта уже инициализирована, центрируем по городу
                    this.centerMapByCity();
                }
            },
            
            hideMap() {
                this.mapVisible = false;
            },
            
            initMap() {
                const self = this;
                const hasCoords = {{ $memorial->burial_latitude ? 'true' : 'false' }};
                
                this.mapInitialized = true;
                
                myMap = new ymaps.Map("burial-map", {
                    center: [this.latitude, this.longitude],
                    zoom: 12,
                    controls: ['zoomControl', 'searchControl', 'typeSelector', 'fullscreenControl']
                });

                // Добавляем кнопку геолокации
                const geolocationButton = new ymaps.control.Button({
                    data: {
                        content: '📍 Где я',
                        title: 'Определить моё местоположение'
                    },
                    options: {
                        selectOnClick: false,
                        maxWidth: 150
                    }
                });
                
                geolocationButton.events.add('click', function() {
                    console.log('Кнопка геолокации нажата');
                    if (navigator.geolocation) {
                        console.log('Запрашиваем геолокацию...');
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const coords = [position.coords.latitude, position.coords.longitude];
                                console.log('Геолокация получена:', coords);
                                
                                // Центрируем карту на текущем местоположении
                                myMap.setCenter(coords, 16);
                                
                                // Ставим метку
                                self.latitude = coords[0];
                                self.longitude = coords[1];
                                
                                if (myPlacemark) {
                                    myPlacemark.geometry.setCoordinates(coords);
                                } else {
                                    myPlacemark = createPlacemark(coords);
                                    myMap.geoObjects.add(myPlacemark);
                                }
                                
                                console.log('Метка установлена на текущем местоположении');
                            },
                            function(error) {
                                console.error('Ошибка геолокации:', error);
                                console.log('Код ошибки:', error.code, 'Сообщение:', error.message);
                                
                                // Пробуем определить местоположение через Яндекс.Карты
                                console.log('Пытаемся определить местоположение через Яндекс.Карты...');
                                ymaps.geolocation.get({
                                    provider: 'yandex',
                                    mapStateAutoApply: true
                                }).then(function(result) {
                                    const coords = result.geoObjects.position;
                                    console.log('Местоположение определено через Яндекс:', coords);
                                    
                                    myMap.setCenter(coords, 16);
                                    
                                    self.latitude = coords[0];
                                    self.longitude = coords[1];
                                    
                                    if (myPlacemark) {
                                        myPlacemark.geometry.setCoordinates(coords);
                                    } else {
                                        myPlacemark = createPlacemark(coords);
                                        myMap.geoObjects.add(myPlacemark);
                                    }
                                    
                                    console.log('Метка установлена (через Яндекс)');
                                }).catch(function(err) {
                                    console.error('Ошибка определения через Яндекс:', err);
                                    alert('Не удалось определить ваше местоположение. Попробуйте:\n1. Разрешить доступ к геолокации в настройках браузера\n2. Включить службы геолокации в системе\n3. Использовать HTTPS соединение');
                                });
                            },
                            {
                                enableHighAccuracy: false,
                                timeout: 5000,
                                maximumAge: 60000
                            }
                        );
                    } else {
                        console.error('Геолокация не поддерживается браузером');
                        alert('Ваш браузер не поддерживает геолокацию');
                    }
                });
                
                myMap.controls.add(geolocationButton, {
                    float: 'right'
                });

                // Если есть сохраненные координаты, создаем метку
                if (hasCoords) {
                    myPlacemark = createPlacemark([this.latitude, this.longitude]);
                    myMap.geoObjects.add(myPlacemark);
                } else {
                    // Центрируем по городу захоронения
                    this.centerMapByCity();
                }

                myMap.events.add('click', function (e) {
                    var coords = e.get('coords');
                    
                    // Обновляем координаты в Alpine
                    self.latitude = coords[0];
                    self.longitude = coords[1];
                    
                    // Если метка уже создана – просто передвигаем ее
                    if (myPlacemark) {
                        myPlacemark.geometry.setCoordinates(coords);
                    }
                    // Если нет – создаем.
                    else {
                        myPlacemark = createPlacemark(coords);
                        myMap.geoObjects.add(myPlacemark);
                    }
                });
            },
            
            centerMapByCity() {
                console.log('=== centerMapByCity вызван ===');
                const burialCityInput = document.getElementById('burial_city_input')?.value || '';
                const burialCityHidden = document.querySelector('input[name="burial_city"]')?.value || '';
                
                console.log('burial_city_input значение:', burialCityInput);
                console.log('burial_city (скрытое поле) значение:', burialCityHidden);
                
                const burialCity = burialCityInput || burialCityHidden;
                console.log('Используем город для центрирования:', burialCity);
                
                if (burialCity.length > 0) {
                    console.log('Отправляем запрос геокодирования для:', burialCity);
                    ymaps.geocode(burialCity, {
                        results: 1
                    }).then(function(res) {
                        const firstGeoObject = res.geoObjects.get(0);
                        console.log('Результат геокодирования:', firstGeoObject);
                        if (firstGeoObject) {
                            const coords = firstGeoObject.geometry.getCoordinates();
                            console.log('Координаты найдены:', coords);
                            myMap.setCenter(coords, 12);
                            console.log('Карта центрирована');
                        } else {
                            console.log('Геообъект не найден');
                        }
                    }).catch(function(error) {
                        console.error('Ошибка геокодирования:', error);
                    });
                } else {
                    console.log('Город не указан, центрирование пропущено');
                }
            },
            
            async searchCemetery(query) {
                if (query.length < 3) return;
                if (!this.mapInitialized) return;
                
                const self = this;
                
                try {
                    ymaps.geocode(query + ' кладбище', {
                        results: 1
                    }).then(function(res) {
                        const firstGeoObject = res.geoObjects.get(0);
                        if (firstGeoObject) {
                            const coords = firstGeoObject.geometry.getCoordinates();
                            self.latitude = coords[0];
                            self.longitude = coords[1];
                            myMap.setCenter(coords, 16);
                            
                            // Ставим метку
                            if (myPlacemark) {
                                myPlacemark.geometry.setCoordinates(coords);
                            } else {
                                myPlacemark = createPlacemark(coords);
                                myMap.geoObjects.add(myPlacemark);
                            }
                        }
                    });
                } catch (error) {
                    console.error('Ошибка поиска кладбища:', error);
                }
            }
        }
    }
    
    function createPlacemark(coords) {
        // Формируем текст для метки
        const fullName = '{{ $memorial->last_name }} {{ $memorial->first_name }} {{ $memorial->middle_name }}';
        const burialPlace = document.getElementById('burial_place')?.value || '';
        const burialLocation = document.getElementById('burial_location')?.value || '';
        
        let caption = fullName;
        if (burialPlace) {
            caption += '\n' + burialPlace;
        }
        if (burialLocation) {
            caption += '\n' + burialLocation;
        }
        
        console.log('Создаем метку с текстом:', caption);
        
        return new ymaps.Placemark(coords, {
            iconCaption: caption,
            balloonContent: `<strong>${fullName}</strong><br>${burialPlace}<br>${burialLocation}`
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        });
    }
</script>
