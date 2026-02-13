@extends('layouts.app')

@section('title', 'Приватность')

@section('content')
<div class="bg-gray-200 min-h-screen py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Основной контент -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h1 class="text-2xl font-bold text-slate-700 mb-6">Приватность</h1>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('user.updatePrivacy') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-slate-700 mb-2">Настройки профиля</h2>
                            <p class="text-sm text-gray-500">Управляйте видимостью вашего профиля</p>
                        </div>

                        <!-- Тип профиля -->
                        <div class="space-y-4">
                            <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="profile_type" value="public" {{ Auth::user()->profile_type === 'public' ? 'checked' : '' }} class="mt-1 w-4 h-4 text-red-500 focus:ring-red-500">
                                <div>
                                    <div class="font-medium text-gray-900">Открытый профиль</div>
                                    <div class="text-sm text-gray-500">Ваш профиль виден всем пользователям</div>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="profile_type" value="private" {{ Auth::user()->profile_type === 'private' ? 'checked' : '' }} class="mt-1 w-4 h-4 text-red-500 focus:ring-red-500">
                                <div>
                                    <div class="font-medium text-gray-900">Закрытый профиль</div>
                                    <div class="text-sm text-gray-500">Только вы можете видеть свой профиль</div>
                                </div>
                            </label>
                        </div>

                        <!-- Дополнительные настройки -->
                        <div class="border-t border-gray-200 pt-6 space-y-4">
                            <h3 class="font-semibold text-gray-900 mb-4">Дополнительные настройки</h3>
                            
                            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">Показывать email</div>
                                    <div class="text-sm text-gray-500">Другие пользователи смогут видеть ваш email</div>
                                </div>
                                <input type="checkbox" name="show_email" {{ Auth::user()->show_email ? 'checked' : '' }} class="w-5 h-5 text-red-500 focus:ring-red-500 rounded">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">Показывать созданные мемориалы</div>
                                    <div class="text-sm text-gray-500">Список ваших мемориалов будет виден другим</div>
                                </div>
                                <input type="checkbox" name="show_memorials" {{ Auth::user()->show_memorials ? 'checked' : '' }} class="w-5 h-5 text-red-500 focus:ring-red-500 rounded">
                            </label>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                Сохранить настройки
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            <!-- Боковая панель справа с навигацией -->
            <aside class="lg:w-80 lg:sticky lg:top-4 lg:h-fit">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <nav class="p-4">
                        <a href="{{ route('user.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
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
                        <a href="{{ route('user.privacy') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 bg-red-50 rounded-lg font-medium">
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
@endsection
