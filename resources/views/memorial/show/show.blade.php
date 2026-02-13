@extends('layouts.app')

@section('title', $memorial->first_name . ' ' . $memorial->last_name . ' - Страница памяти')

@section('content')
<div class="min-h-screen bg-gray-200 pt-6" x-data="{ activeTab: 'memories' }">
    <!-- Hero блок -->
    @include('memorial.show.partials.hero', ['memorial' => $memorial])

    <!-- Основной контент -->
    <div class="container mx-auto px-4 pb-16">
        <div class="grid lg:grid-cols-[280px_1fr] gap-4">
            <!-- Сайдбар с меню -->
            @include('memorial.show.partials.sidebar')

            <!-- Основной контент с вкладками -->
            <main class="space-y-4">
                <!-- Вкладка: Воспоминания -->
                <div x-show="activeTab === 'memories'" x-transition>
                    @include('memorial.show.tabs.memories', ['memories' => $memories])
                </div>

                <!-- Вкладка: О человеке -->
                <div x-show="activeTab === 'about'" x-transition>
                    @include('memorial.show.tabs.about', ['memorial' => $memorial])
                </div>

                <!-- Вкладка: Захоронение -->
                <div x-show="activeTab === 'burial'" x-transition>
                    @include('memorial.show.tabs.burial', ['memorial' => $memorial])
                </div>

                <!-- Вкладка: Медиа -->
                <div x-show="activeTab === 'media'" x-transition>
                    @include('memorial.show.tabs.media')
                </div>

                <!-- Вкладка: Близкие люди -->
                <div x-show="activeTab === 'people'" x-transition>
                    @include('memorial.show.tabs.people')
                </div>

                @auth
                <!-- Вкладка: Статистика -->
                <div x-show="activeTab === 'statistics'" x-transition>
                    @include('memorial.show.tabs.statistics')
                </div>

                <!-- Вкладка: Настройки -->
                <div x-show="activeTab === 'settings'" x-transition>
                    @include('memorial.show.tabs.settings')
                </div>
                @endauth
            </main>
        </div>
    </div>
</div>
@endsection
