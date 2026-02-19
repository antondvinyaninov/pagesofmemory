                        <div x-show="activeTab === 'burial'" class="space-y-6">
                            
                            <!-- Блок: Местоположение -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Местоположение</h3>

                                <div class="space-y-5">
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
                                                class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                            <input type="hidden" name="burial_city" :value="selectedCity">
                                            
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

                                    <!-- Место захоронения -->
                                    <div>
                                        <label for="burial_place" class="block text-sm font-medium text-gray-700 mb-2">Место захоронения</label>
                                        <input 
                                            type="text" 
                                            name="burial_place" 
                                            id="burial_place" 
                                            value="{{ old('burial_place', $memorial->burial_place) }}" 
                                            placeholder="Название кладбища" 
                                            class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>

                                    <!-- Адрес -->
                                    <div>
                                        <label for="burial_address" class="block text-sm font-medium text-gray-700 mb-2">Адрес</label>
                                        <input 
                                            type="text" 
                                            name="burial_address" 
                                            id="burial_address" 
                                            value="{{ old('burial_address', $memorial->burial_address) }}" 
                                            placeholder="Полный адрес кладбища" 
                                            class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>

                                    <!-- Расположение на кладбище -->
                                    <div>
                                        <label for="burial_location" class="block text-sm font-medium text-gray-700 mb-2">Расположение на кладбище</label>
                                        <input 
                                            type="text" 
                                            name="burial_location" 
                                            id="burial_location" 
                                            value="{{ old('burial_location', $memorial->burial_location) }}" 
                                            placeholder="Участок, ряд, место" 
                                            class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Блок: Карта -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100" x-data="burialMap()">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Точное местоположение на карте</h3>
                                <p class="text-sm text-gray-600 mb-4">Укажите точное расположение на карте для удобства посетителей</p>
                                
                                <!-- Кнопка показать карту -->
                                <div x-show="!mapVisible">
                                    <button 
                                        type="button"
                                        @click="showMap()"
                                        class="px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        Показать карту
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">Сначала укажите город захоронения выше</p>
                                </div>
                                
                                <!-- Карта -->
                                <div x-show="mapVisible" x-transition>
                                    <p class="text-sm text-gray-600 mb-3">Кликните на карте для установки метки</p>
                                    
                                    <div id="burial-map" class="w-full h-96 rounded-lg border-2 border-blue-200 bg-white"></div>
                                    
                                    <button 
                                        type="button"
                                        @click="hideMap()"
                                        class="mt-3 px-4 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition-colors"
                                    >
                                        Скрыть карту
                                    </button>
                                </div>
                                
                                <!-- Скрытые поля для координат -->
                                <input type="hidden" name="burial_latitude" id="burial_latitude" :value="latitude">
                                <input type="hidden" name="burial_longitude" id="burial_longitude" :value="longitude">
                            </div>

                            <!-- Блок: Фото места захоронения -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100" x-data="burialPhotos()">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Фото места захоронения</h3>
                                <p class="text-sm text-gray-600 mb-5">Фотографии могилы, памятника, надгробия</p>
                                
                                <div class="flex gap-3 overflow-x-auto pb-2">
                                    <!-- Кнопка добавления -->
                                    <div class="flex-shrink-0">
                                        <button type="button" @click="addPhoto()" class="w-32 h-40 bg-white border-2 border-dashed border-blue-300 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-blue-500 hover:bg-blue-50 transition-colors">
                                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span class="text-xs text-gray-600 font-medium">Добавить фото</span>
                                        </button>
                                    </div>

                                    <!-- Превью загруженных фото -->
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <div class="flex-shrink-0 relative group">
                                            <div class="w-32 h-40 bg-white rounded-xl overflow-hidden border-2 border-blue-200 relative">
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
                                
                                <p class="mt-3 text-xs text-gray-500">JPG, PNG, WEBP, HEIC до 10MB каждое</p>
                            </div>
                        </div>
