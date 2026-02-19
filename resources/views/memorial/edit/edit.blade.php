@extends('layouts.app')

@section('title', $memorial->exists ? 'Редактировать мемориал' : 'Создать мемориал')

@section('content')
<div class="bg-gray-50 min-h-screen py-6" x-data="{ activeTab: 'basic' }">
    <!-- Мобильное меню на всю ширину -->
    <div class="lg:hidden bg-white shadow-md mb-4">
        <div class="flex overflow-x-scroll gap-2 px-4 py-4" style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
            <button @click="activeTab = 'basic'" :class="activeTab === 'basic' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Основное
            </button>
            <button @click="activeTab = 'biography'" :class="activeTab === 'biography' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Биография
            </button>
            <button @click="activeTab = 'burial'" :class="activeTab === 'burial' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                Захоронение
            </button>
            <button @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Медиа
            </button>
            <button @click="activeTab = 'people'" :class="activeTab === 'people' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Близкие люди
            </button>
            <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Настройки
            </button>
            <button @click="activeTab = 'qrcode'" :class="activeTab === 'qrcode' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                QR-код
            </button>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Основной контент -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h1 class="text-2xl font-bold text-slate-700 mb-6">
                        {{ $memorial->exists ? 'Редактировать мемориал' : 'Создать мемориал' }}
                    </h1>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ $memorial->exists ? route('memorial.update', $memorial->id) : route('memorial.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="console.log('Отправка формы, CSRF токен:', document.querySelector('input[name=_token]').value)">
                        @csrf
                        @if($memorial->exists)
                            @method('PUT')
                        @endif

                        <!-- Вкладки -->
                        @include('memorial.edit.tabs.basic')
                        @include('memorial.edit.tabs.biography')
                        @include('memorial.edit.tabs.burial')
                        @include('memorial.edit.tabs.media')
                        @include('memorial.edit.tabs.people')
                        @include('memorial.edit.tabs.settings')
                        @include('memorial.edit.tabs.qrcode')

                        <!-- Кнопки сохранения -->
                        <div class="flex gap-4 pt-6 border-t">
                            <button type="submit" name="action" value="draft" class="flex-1 px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors font-medium">
                                Сохранить как черновик
                            </button>
                            <button type="submit" name="action" value="publish" class="flex-1 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors font-medium">
                                {{ $memorial->exists ? 'Сохранить и опубликовать' : 'Опубликовать' }}
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            @include('memorial.edit.sidebar')
        </div>
    </div>
</div>

@include('memorial.edit.scripts')
@endsection
