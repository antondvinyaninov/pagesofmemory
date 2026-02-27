@extends('layouts.admin')

@section('title', 'Метрики')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-slate-700">Google Tag Manager</h2>
            <p class="text-sm text-gray-500 mt-1">Подключите GTM для управления всеми счетчиками аналитики</p>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.analytics.update') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- GTM ID -->
            <div>
                <label for="gtm_id" class="block text-sm font-medium text-gray-700 mb-2">
                    GTM Container ID
                </label>
                <input 
                    type="text" 
                    name="gtm_id" 
                    id="gtm_id"
                    value="{{ old('gtm_id', \App\Models\AppSetting::get('analytics.gtm_id', '')) }}"
                    placeholder="GTM-XXXXXXX"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <p class="mt-1 text-xs text-gray-500">
                    Например: GTM-XXXXXXX
                </p>
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <button 
                    type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium"
                >
                    Сохранить настройки
                </button>
            </div>
        </form>

        <!-- Инструкция -->
        <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Как настроить Google Tag Manager:</h3>
            
            <div class="space-y-3 text-sm text-gray-600">
                <div>
                    <p class="font-medium text-gray-700 mb-1">1. Создайте аккаунт GTM</p>
                    <p>Перейдите на <a href="https://tagmanager.google.com" target="_blank" class="text-blue-600 hover:underline">tagmanager.google.com</a> и создайте новый контейнер для вашего сайта</p>
                </div>
                
                <div>
                    <p class="font-medium text-gray-700 mb-1">2. Скопируйте Container ID</p>
                    <p>После создания контейнера скопируйте ID в формате GTM-XXXXXXX и вставьте в поле выше</p>
                </div>
                
                <div>
                    <p class="font-medium text-gray-700 mb-1">3. Настройте теги в GTM</p>
                    <p>Через интерфейс GTM вы сможете добавить:</p>
                    <ul class="list-disc list-inside ml-4 mt-1 space-y-1">
                        <li>Google Analytics (GA4)</li>
                        <li>Яндекс.Метрику</li>
                        <li>Facebook Pixel</li>
                        <li>VK Pixel</li>
                        <li>Любые другие счетчики и скрипты</li>
                    </ul>
                </div>

                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                    <p class="font-medium text-blue-900">💡 Преимущества GTM:</p>
                    <ul class="list-disc list-inside ml-4 mt-1 space-y-1 text-blue-800">
                        <li>Управление всеми счетчиками из одного места</li>
                        <li>Не нужно менять код сайта для добавления новых счетчиков</li>
                        <li>Отслеживание событий (клики, формы, скроллинг)</li>
                        <li>Версионность и откат изменений</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
