<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Фото и видео
        </h3>
    </div>
    
    <div class="p-6">
        <div class="space-y-8">
            <!-- Фотографии -->
            <div>
                <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Фотографии (8)
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @for($i = 1; $i <= 8; $i++)
                    <div class="group cursor-pointer">
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                            <img 
                                src="https://via.placeholder.com/400" 
                                alt="Фото {{ $i }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-slate-700">Фото {{ $i }}</p>
                            <p class="text-xs text-gray-500">{{ 1970 + $i * 5 }}</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Видео -->
            <div>
                <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Видео (4)
                </h4>
                <div class="grid md:grid-cols-2 gap-6">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="group cursor-pointer">
                        <div class="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                            <img 
                                src="https://via.placeholder.com/600x400" 
                                alt="Видео {{ $i }}"
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute inset-0 bg-black bg-opacity-30 group-hover:bg-opacity-40 transition-colors flex items-center justify-center">
                                <div class="w-12 h-12 bg-white bg-opacity-90 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-700 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                </div>
                            </div>
                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                {{ 10 + $i }}:{{ 20 + $i * 5 }}
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-slate-700">Видео {{ $i }}</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
