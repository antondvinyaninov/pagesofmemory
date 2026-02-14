<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
        <h3 class="text-base sm:text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Фото и видео
        </h3>
    </div>
    
    <div class="p-4 sm:p-6">
        @if((isset($allPhotos) && count($allPhotos) > 0) || (isset($allVideos) && count($allVideos) > 0))
            <!-- Фотографии -->
            @if(isset($allPhotos) && count($allPhotos) > 0)
            <div class="mb-4 sm:mb-6">
                <h4 class="text-sm sm:text-md font-semibold text-slate-700 mb-2 sm:mb-3">Фотографии ({{ count($allPhotos) }})</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                    @foreach($allPhotos as $photo)
                    <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $photo['url'] }}')">
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $photo['url'] }}" 
                                alt="Фото от {{ $photo['author'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-white text-xs truncate">{{ $photo['author'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Видео -->
            @if(isset($allVideos) && count($allVideos) > 0)
            <div>
                <h4 class="text-sm sm:text-md font-semibold text-slate-700 mb-2 sm:mb-3">Видео ({{ count($allVideos) }})</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    @foreach($allVideos as $video)
                    <div class="relative aspect-video rounded-lg overflow-hidden bg-gray-900">
                        <video 
                            src="{{ $video['url'] }}" 
                            controls
                            preload="metadata"
                            class="w-full h-full object-cover"
                        ></video>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                            <p class="text-white text-xs truncate">{{ $video['author'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p>Фото и видео пока не добавлены</p>
            </div>
        @endif
    </div>
</div>
