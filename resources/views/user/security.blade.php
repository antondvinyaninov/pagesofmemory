@extends('layouts.app')

@section('title', 'Безопасность')

@section('content')
<div class="bg-gray-200 min-h-screen py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Основной контент -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h1 class="text-2xl font-bold text-slate-700 mb-6">Безопасность</h1>

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

                    <form action="{{ route('user.updatePassword') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-slate-700 mb-2">Изменить пароль</h2>
                            <p class="text-sm text-gray-500">Используйте надежный пароль для защиты вашего аккаунта</p>
                        </div>

                        <!-- Текущий пароль -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Текущий пароль</label>
                            <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <!-- Новый пароль -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Новый пароль</label>
                            <input type="password" name="password" id="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Минимум 6 символов</p>
                        </div>

                        <!-- Подтверждение пароля -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Подтвердите новый пароль</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <!-- Кнопки -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                                Изменить пароль
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
                        <a href="{{ route('user.security') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 bg-red-50 rounded-lg font-medium">
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
@endsection
