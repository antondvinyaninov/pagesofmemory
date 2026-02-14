<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
        <h3 class="text-base sm:text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            Место захоронения
        </h3>
    </div>
    
    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
        @if($memorial->burial_place || $memorial->burial_address || $memorial->burial_location || ($memorial->burial_photos && count($memorial->burial_photos) > 0))
        <!-- Информация о захоронении и фото -->
        <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- Левая колонка: Информация -->
            <div class="space-y-2 sm:space-y-3">
                @if($memorial->burial_city)
                <p class="flex items-start gap-2 text-sm sm:text-base text-gray-700">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    <span><span class="font-medium">Город:</span> {{ expand_region_abbreviations($memorial->burial_city) }}</span>
                </p>
                @endif
                
                @if($memorial->burial_place)
                <p class="flex items-center gap-2 text-gray-700">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="font-medium">Кладбище:</span> {{ $memorial->burial_place }}
                </p>
                @endif
                
                @if($memorial->burial_address)
                <p class="flex items-center gap-2 text-gray-700">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-medium">Адрес:</span> {{ $memorial->burial_address }}
                </p>
                @endif
                
                @if($memorial->burial_location)
                <p class="flex items-center gap-2 text-gray-700">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="font-medium">Расположение:</span> {{ $memorial->burial_location }}
                </p>
                @endif
            </div>

            <!-- Правая колонка: Фото -->
            @if($memorial->burial_photos && count($memorial->burial_photos) > 0)
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                @foreach($memorial->burial_photos as $photo)
                @php
                    $photoUrl = \Storage::disk('s3')->url($photo);
                @endphp
                <div class="relative group cursor-pointer" onclick="openPhotoModal('{{ $photoUrl }}')">
                    <div class="rounded-lg overflow-hidden">
                        <img 
                            src="{{ $photoUrl }}" 
                            alt="Фото места захоронения" 
                            class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        <!-- Карта -->
        @if($memorial->burial_latitude && $memorial->burial_longitude)
        <div>
            <h4 class="text-sm sm:text-base font-semibold text-slate-700 mb-2 sm:mb-3">Местоположение на карте</h4>
            <div id="burial-map-view" class="w-full h-64 sm:h-80 md:h-96 rounded-lg border-2 border-gray-200"></div>
        </div>
        @endif
        
        @if(!$memorial->burial_place && !$memorial->burial_address && !$memorial->burial_location && (!$memorial->burial_photos || count($memorial->burial_photos) === 0) && !$memorial->burial_latitude)
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            </svg>
            <p>Информация о месте захоронения не указана</p>
        </div>
        @endif
    </div>
</div>

@if($memorial->burial_latitude && $memorial->burial_longitude)
<!-- Яндекс.Карты API -->
<script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_MAPS_API_KEY') }}&lang=ru_RU" type="text/javascript"></script>

<script type="text/javascript">
    ymaps.ready(initBurialMap);
    
    function initBurialMap() {
        const burialMap = new ymaps.Map("burial-map-view", {
            center: [{{ $memorial->burial_latitude }}, {{ $memorial->burial_longitude }}],
            zoom: 16,
            controls: ['zoomControl', 'fullscreenControl']
        });

        const placemark = new ymaps.Placemark([{{ $memorial->burial_latitude }}, {{ $memorial->burial_longitude }}], {
            iconCaption: '{{ $memorial->last_name }} {{ $memorial->first_name }} {{ $memorial->middle_name }}',
            balloonContent: `
                <div class="p-2">
                    <strong>{{ $memorial->last_name }} {{ $memorial->first_name }} {{ $memorial->middle_name }}</strong><br>
                    @if($memorial->burial_place){{ $memorial->burial_place }}<br>@endif
                    @if($memorial->burial_location){{ $memorial->burial_location }}@endif
                </div>
            `
        }, {
            preset: 'islands#violetDotIconWithCaption'
        });

        burialMap.geoObjects.add(placemark);
    }
</script>
@endif
