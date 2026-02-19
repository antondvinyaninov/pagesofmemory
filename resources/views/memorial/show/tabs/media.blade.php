<div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
    <div class="border-b border-slate-700 bg-slate-700 px-4 py-4 sm:px-6">
        <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Фото и видео
        </h3>
    </div>
    
    <div class="p-4 sm:p-6">
        @php
            $photos = collect($allPhotos ?? []);
            $videos = collect($allVideos ?? []);

            $memorialPhotos = $photos->filter(fn ($item) => is_null($item['memory_id'] ?? null))->values();
            $memorialVideos = $videos->filter(fn ($item) => is_null($item['memory_id'] ?? null))->values();

            $memoryPhotos = $photos->filter(fn ($item) => !is_null($item['memory_id'] ?? null))->values();
            $memoryVideos = $videos->filter(fn ($item) => !is_null($item['memory_id'] ?? null))->values();

            $hasMedia = $photos->isNotEmpty() || $videos->isNotEmpty();
        @endphp

        @if($hasMedia)
            @if($memorialPhotos->isNotEmpty() || $memorialVideos->isNotEmpty())
            <div class="mb-6 rounded-xl border border-slate-300 bg-white p-4 sm:mb-8 sm:p-5">
                <h4 class="text-sm sm:text-md font-semibold text-slate-700 mb-3 sm:mb-4">Галерея мемориала</h4>

                @if($memorialPhotos->isNotEmpty())
                <div class="mb-4 sm:mb-6">
                    <h5 class="text-sm font-medium text-slate-600 mb-2 sm:mb-3">Фотографии ({{ $memorialPhotos->count() }})</h5>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                        @foreach($memorialPhotos as $photo)
                        <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $photo['url'] }}')">
                            <div class="aspect-square rounded-lg overflow-hidden border border-slate-300 bg-gray-100">
                                <img
                                    src="{{ $photo['url'] }}"
                                    alt="Фото из галереи мемориала"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($memorialVideos->isNotEmpty())
                <div>
                    <h5 class="text-sm font-medium text-slate-600 mb-2 sm:mb-3">Видео ({{ $memorialVideos->count() }})</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($memorialVideos as $video)
                        <div class="relative aspect-video rounded-lg overflow-hidden bg-gray-900">
                            <video
                                src="{{ $video['url'] }}"
                                controls
                                preload="metadata"
                                class="w-full h-full object-cover"
                            ></video>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            @if($memoryPhotos->isNotEmpty() || $memoryVideos->isNotEmpty())
            <div class="rounded-xl border border-slate-300 bg-white p-4 sm:p-5">
                <h4 class="text-sm sm:text-md font-semibold text-slate-700 mb-3 sm:mb-4">Из воспоминаний</h4>

                @if($memoryPhotos->isNotEmpty())
                <div class="mb-4 sm:mb-6">
                    <h5 class="text-sm font-medium text-slate-600 mb-2 sm:mb-3">Фотографии ({{ $memoryPhotos->count() }})</h5>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                        @foreach($memoryPhotos as $photo)
                        <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $photo['url'] }}')">
                            <div class="aspect-square rounded-lg overflow-hidden border border-slate-300 bg-gray-100">
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

                @if($memoryVideos->isNotEmpty())
                <div>
                    <h5 class="text-sm font-medium text-slate-600 mb-2 sm:mb-3">Видео ({{ $memoryVideos->count() }})</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($memoryVideos as $video)
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
            </div>
            @endif
        @else
            <div class="rounded-xl border border-slate-300 bg-white py-12 text-center text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p>Фото и видео пока не добавлены</p>
            </div>
        @endif
    </div>
</div>
