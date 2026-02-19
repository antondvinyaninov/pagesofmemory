@php
    $hasBurialTextInfo = $memorial->burial_city || $memorial->burial_place || $memorial->burial_address || $memorial->burial_location;
    $hasBurialPhotos = $memorial->burial_photos && count($memorial->burial_photos) > 0;
    $hasBurialMap = $memorial->burial_latitude && $memorial->burial_longitude;
    $hasBurialData = $hasBurialTextInfo || $hasBurialPhotos || $hasBurialMap;
@endphp

<section class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-md">
    <header class="border-b border-slate-700 bg-slate-700 px-4 py-4 sm:px-6">
        <h3 class="flex items-center gap-2 text-base font-semibold text-white sm:text-lg">
            <svg class="h-4 w-4 text-sky-200 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            Место захоронения
        </h3>
    </header>
</section>

<div class="mt-4 space-y-5">
    @if($hasBurialTextInfo)
    <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
        <h4 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            </span>
            Информация о захоронении
        </h4>
        <dl class="grid gap-3 sm:grid-cols-2">
            @if($memorial->burial_city)
            <div class="px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Город</dt>
                <dd class="mt-1 text-sm font-medium text-slate-700">{{ expand_region_abbreviations($memorial->burial_city) }}</dd>
            </div>
            @endif

            @if($memorial->burial_place)
            <div class="px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Кладбище</dt>
                <dd class="mt-1 text-sm font-medium text-slate-700">{{ $memorial->burial_place }}</dd>
            </div>
            @endif

            @if($memorial->burial_address)
            <div class="px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Адрес</dt>
                <dd class="mt-1 text-sm font-medium text-slate-700">{{ $memorial->burial_address }}</dd>
            </div>
            @endif

            @if($memorial->burial_location)
            <div class="px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Расположение</dt>
                <dd class="mt-1 text-sm font-medium text-slate-700">{{ $memorial->burial_location }}</dd>
            </div>
            @endif
        </dl>
    </section>
    @endif

    @if($hasBurialPhotos)
    <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
        <h4 class="mb-4 flex items-center gap-2 text-lg font-semibold text-slate-800">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </span>
            Фотографии места
        </h4>
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-4 sm:gap-3">
            @foreach($memorial->burial_photos as $photo)
            @php
                $photoUrl = \Storage::disk('s3')->url($photo);
            @endphp
            <button type="button" class="group relative block cursor-pointer" onclick="openPhotoModal('{{ $photoUrl }}')">
                <div class="aspect-square overflow-hidden rounded-lg border border-slate-300 bg-white">
                    <img
                        src="{{ $photoUrl }}"
                        alt="Фото места захоронения"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                </div>
            </button>
            @endforeach
        </div>
    </section>
    @endif

    @if($hasBurialMap)
    <section class="rounded-2xl border border-slate-300 bg-white p-4 shadow-md sm:p-5">
        <h4 class="mb-3 flex items-center gap-2 text-lg font-semibold text-slate-800">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            </span>
            Местоположение на карте
        </h4>
        <div id="burial-map-view" class="h-64 w-full rounded-xl border border-slate-300 bg-white sm:h-80 md:h-96"></div>
        <p class="mt-2 text-xs text-gray-500">Координаты: {{ $memorial->burial_latitude }}, {{ $memorial->burial_longitude }}</p>
    </section>
    @endif

    @if(!$hasBurialData)
    <section class="rounded-2xl border border-slate-300 bg-white p-8 text-center shadow-md sm:p-10">
        <svg class="mx-auto mb-4 h-14 w-14 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
        </svg>
        <p class="text-sm text-gray-500 sm:text-base">Информация о месте захоронения пока не заполнена</p>
    </section>
    @endif
</div>

@if($hasBurialMap)
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
            preset: 'islands#redDotIconWithCaption'
        });

        burialMap.geoObjects.add(placemark);
    }
</script>
@endif
