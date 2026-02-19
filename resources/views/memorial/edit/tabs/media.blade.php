                        <div x-show="activeTab === 'media'" class="space-y-6">
                            
                            <!-- Блок: Фотографии -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 sm:p-6 border border-blue-100" x-data="mediaPhotos()">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Фотографии</h3>
                                <p class="text-sm text-gray-600 mb-4 sm:mb-5">Дополнительные фотографии из жизни (до 5 штук)</p>
                                
                                <div class="flex gap-2 sm:gap-3 overflow-x-auto pb-2">
                                    <!-- Превью загруженных фото -->
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <div class="flex-shrink-0 relative group">
                                            <div class="w-24 h-24 sm:w-32 sm:h-32 bg-white rounded-xl overflow-hidden border-2 border-blue-200 relative">
                                                <!-- Превью изображения -->
                                                <div x-show="photo.preview" class="w-full h-full">
                                                    <img :src="photo.preview" class="w-full h-full object-cover">
                                                </div>

                                                <!-- Кнопка удаления -->
                                                <button type="button" @click="removePhoto(index)" class="absolute top-1 right-1 w-5 h-5 sm:w-6 sm:h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>

                                                <!-- Скрытый input для файла (только для новых фото) -->
                                                <template x-if="!photo.existing">
                                                    <input 
                                                        type="file" 
                                                        :id="'media_photo_' + index"
                                                        :name="'media_photos[' + index + ']'" 
                                                        accept="image/*,.heic,.heif"
                                                        @change="handlePhotoPreview($event, index)"
                                                        class="hidden"
                                                    >
                                                </template>

                                                <!-- Скрытое поле для сохранения уже существующих фото -->
                                                <template x-if="photo.existing">
                                                    <input 
                                                        type="hidden"
                                                        :name="'existing_media_photos[' + index + ']'"
                                                        :value="photo.url"
                                                    >
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Кнопка добавления -->
                                    <div class="flex-shrink-0" x-show="photos.length < 5">
                                        <button type="button" @click="addPhoto()" class="w-24 h-24 sm:w-32 sm:h-32 bg-white border-2 border-dashed border-blue-300 rounded-xl flex items-center justify-center hover:border-blue-500 hover:bg-blue-50 transition-colors">
                                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <p class="mt-3 text-xs text-gray-500">JPG, PNG, WEBP, HEIC до 10MB каждое. Максимум 5 фотографий</p>
                            </div>

                            <!-- Блок: Видео -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 sm:p-6 border border-blue-100" x-data="mediaVideos()">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Видео</h3>
                                <p class="text-sm text-gray-600 mb-4 sm:mb-5">Видеозаписи из жизни (до 2 штук)</p>
                                
                                <div class="flex gap-2 sm:gap-3 overflow-x-auto pb-2">
                                    <!-- Превью загруженных видео -->
                                    <template x-for="(video, index) in videos" :key="index">
                                        <div class="flex-shrink-0 relative group">
                                            <div class="w-36 h-24 sm:w-48 sm:h-32 bg-white rounded-xl overflow-hidden border-2 border-blue-200 relative">
                                                <!-- Превью видео -->
                                                <div x-show="video.preview" class="w-full h-full bg-gray-900 flex items-center justify-center">
                                                    <video :src="video.preview" class="w-full h-full object-cover"></video>
                                                    <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"></path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Кнопка удаления -->
                                                <button type="button" @click="removeVideo(index)" class="absolute top-1 right-1 w-5 h-5 sm:w-6 sm:h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity z-10">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>

                                                <!-- Скрытый input для файла (только для новых видео) -->
                                                <template x-if="!video.existing">
                                                    <input 
                                                        type="file" 
                                                        :id="'media_video_' + index"
                                                        :name="'media_videos[' + index + ']'" 
                                                        accept="video/*"
                                                        @change="handleVideoPreview($event, index)"
                                                        class="hidden"
                                                    >
                                                </template>

                                                <!-- Скрытое поле для сохранения уже существующих видео -->
                                                <template x-if="video.existing">
                                                    <input 
                                                        type="hidden"
                                                        :name="'existing_media_videos[' + index + ']'"
                                                        :value="video.url"
                                                    >
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Кнопка добавления -->
                                    <template x-if="videos.length < 2">
                                        <div class="flex-shrink-0">
                                            <button type="button" @click="addVideo()" class="w-36 h-24 sm:w-48 sm:h-32 bg-white border-2 border-dashed border-blue-300 rounded-xl flex flex-col items-center justify-center gap-1 sm:gap-2 hover:border-blue-500 hover:bg-blue-50 transition-colors">
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-xs text-gray-600 font-medium hidden sm:block">Добавить видео</span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                
                                <p class="mt-3 text-xs text-gray-500">MP4, MOV, AVI до 100MB каждое. Максимум 2 видео</p>
                            </div>
                        </div>
