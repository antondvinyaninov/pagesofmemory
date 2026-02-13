@extends('layouts.app')

@section('title', 'Настройки профиля')

@section('head')
@endsection

@section('content')
<div class="bg-gray-200 min-h-screen py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Основной контент -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h1 class="text-2xl font-bold text-slate-700 mb-6">Основная информация</h1>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Аватар -->
                        <div x-data="avatarUpload()">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Фото профиля</label>
                            <div class="flex items-start gap-6">
                                <div class="relative">
                                    <div class="w-24 h-24 bg-red-100 rounded-2xl flex items-center justify-center text-3xl font-bold text-red-600 overflow-hidden">
                                        <img x-show="previewUrl" :src="previewUrl" class="w-full h-full object-cover">
                                        <span x-show="!previewUrl">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <input 
                                        type="file" 
                                        name="avatar" 
                                        id="avatar" 
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
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Фамилия</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', explode(' ', Auth::user()->name)[0] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <!-- Имя -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', explode(' ', Auth::user()->name)[1] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <!-- Отчество -->
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Отчество</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', explode(' ', Auth::user()->name)[2] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <!-- Город -->
                        <div x-data="cityAutocomplete()">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Город</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="city_display" 
                                    id="city_display" 
                                    x-model="query"
                                    @input.debounce.300ms="search()"
                                    @focus="showSuggestions = true"
                                    @click.away="showSuggestions = false"
                                    placeholder="Начните вводить название города"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                >
                                <input type="hidden" name="country" x-model="country">
                                <input type="hidden" name="region" x-model="region">
                                <input type="hidden" name="city" x-model="city">
                                <div 
                                    x-show="showSuggestions && suggestions.length > 0"
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                                >
                                    <template x-for="suggestion in suggestions" :key="suggestion.value">
                                        <div 
                                            @click="selectCity(suggestion)"
                                            class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                        >
                                            <div class="font-medium text-gray-900" x-text="suggestion.value"></div>
                                            <div class="text-sm text-gray-500" x-text="getFullRegion(suggestion)"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <!-- Кнопки -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                Сохранить изменения
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
                        <a href="{{ route('user.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 bg-red-50 rounded-lg font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Основная информация
                        </a>
                        <a href="{{ route('user.security') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Безопасность
                        </a>
                        <a href="{{ route('user.privacy') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Приватность
                        </a>
                    </nav>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
function avatarUpload() {
    return {
        previewUrl: '{{ Auth::user()->avatar ? Storage::disk("s3")->url(Auth::user()->avatar) : "" }}',
        
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

function cityAutocomplete() {
    return {
        query: '@if(Auth::user()->city){{ Auth::user()->city }}, {{ Auth::user()->region }}@endif',
        country: '{{ old('country', Auth::user()->country ?? '') }}',
        region: '{{ old('region', Auth::user()->region ?? '') }}',
        city: '{{ old('city', Auth::user()->city ?? '') }}',
        suggestions: [],
        showSuggestions: false,
        
        async search() {
            if (this.query.length < 2) {
                this.suggestions = [];
                return;
            }
            
            try {
                const response = await fetch('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Token 300ba9e25ef32f0d6ea7c41826b2255b138e19e2'
                    },
                    body: JSON.stringify({
                        query: this.query,
                        from_bound: { value: 'city' },
                        to_bound: { value: 'city' },
                        locations: [{ country: '*' }]
                    })
                });
                
                const data = await response.json();
                this.suggestions = data.suggestions || [];
                this.showSuggestions = true;
            } catch (error) {
                console.error('Ошибка при получении городов:', error);
            }
        },
        
        getFullRegion(suggestion) {
            const data = suggestion.data;
            
            // Используем полное название из region_type_full + region
            // Например: region_type_full = "Республика", region = "Удмуртская"
            let regionName = '';
            
            if (data.region_type_full && data.region) {
                // Для республик: "Удмуртская Республика"
                if (data.region_type_full === 'Республика') {
                    regionName = data.region + ' ' + data.region_type_full;
                } else {
                    // Для краев и областей: "Пермский край", "Московская область"
                    regionName = data.region + ' ' + data.region_type_full;
                }
            } else if (data.region_with_type) {
                regionName = data.region_with_type;
            }
            
            return regionName;
        },
        
        selectCity(suggestion) {
            const data = suggestion.data;
            
            // Сохраняем страну
            this.country = data.country || '';
            
            // Формируем полное название региона
            let regionName = '';
            if (data.region_type_full && data.region) {
                if (data.region_type_full === 'Республика') {
                    regionName = data.region + ' ' + data.region_type_full;
                } else {
                    regionName = data.region + ' ' + data.region_type_full;
                }
            } else if (data.region_with_type) {
                regionName = data.region_with_type;
            }
            
            this.region = regionName;
            
            // Сохраняем город с типом (г Сарапул)
            this.city = data.city_with_type || data.settlement_with_type || '';
            
            // Показываем: город, регион (без страны)
            let displayParts = [];
            if (this.city) displayParts.push(this.city);
            if (this.region) displayParts.push(this.region);
            
            this.query = displayParts.join(', ');
            this.showSuggestions = false;
        }
    }
}
</script>
@endsection
