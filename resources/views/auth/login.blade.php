@extends('layouts.app')

@section('title', 'Вход и регистрация')

@section('content')
<div class="bg-gray-200 py-16 px-4 min-h-screen" x-data="{ mode: 'login', showPassword: false, showConfirmPassword: false }">
    <div class="max-w-md w-full mx-auto">
        <!-- Переключатель режимов -->
        <div class="flex rounded-lg border border-slate-200 bg-slate-100 p-1 mb-6">
            <button 
                @click="mode = 'login'" 
                :class="mode === 'login' ? 'bg-red-500 text-white' : 'text-slate-600 hover:text-slate-800'"
                class="flex-1 py-3 px-4 rounded-md font-medium transition-all duration-200"
            >
                Вход
            </button>
            <button 
                @click="mode = 'register'" 
                :class="mode === 'register' ? 'bg-red-500 text-white' : 'text-slate-600 hover:text-slate-800'"
                class="flex-1 py-3 px-4 rounded-md font-medium transition-all duration-200"
            >
                Регистрация
            </button>
        </div>

        <!-- Форма входа -->
        <div x-show="mode === 'login'" x-transition class="bg-white rounded-lg border border-slate-200 shadow-sm p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-slate-700 mb-2">Вход в систему</h1>
                <p class="text-slate-500">Введите ваши данные для входа</p>
            </div>

            <form action="/login" method="POST" class="space-y-4">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="login-email" class="block text-sm font-medium text-slate-800 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="login-email"
                        name="email"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
                        placeholder="example@email.com"
                        value="{{ old('email') }}"
                    />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Пароль -->
                <div>
                    <label for="login-password" class="block text-sm font-medium text-slate-800 mb-2">
                        Пароль
                    </label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="login-password"
                            name="password"
                            required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
                            placeholder="Введите пароль"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-700 transition-colors"
                        >
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ошибки -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-100 rounded-md p-4">
                        <p class="text-red-500 text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Кнопка отправки -->
                <button
                    type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-md transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Войти
                </button>
            </form>
        </div>

        <!-- Форма регистрации -->
        <div x-show="mode === 'register'" x-transition class="bg-white rounded-lg border border-slate-200 shadow-sm p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-slate-700 mb-2">Регистрация</h1>
                <p class="text-slate-500">Создайте новый аккаунт</p>
            </div>

            <form action="/register" method="POST" class="space-y-4">
                @csrf
                
                <!-- Имя -->
                <div>
                    <label for="register-first-name" class="block text-sm font-medium text-slate-800 mb-2">
                        Имя
                    </label>
                    <input
                        type="text"
                        id="register-first-name"
                        name="first_name"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
                        placeholder="Иван"
                        value="{{ old('first_name') }}"
                    />
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Фамилия -->
                <div>
                    <label for="register-last-name" class="block text-sm font-medium text-slate-800 mb-2">
                        Фамилия
                    </label>
                    <input
                        type="text"
                        id="register-last-name"
                        name="last_name"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
                        placeholder="Иванов"
                        value="{{ old('last_name') }}"
                    />
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="register-email" class="block text-sm font-medium text-slate-800 mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="register-email"
                        name="email"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
                        placeholder="example@email.com"
                        value="{{ old('email') }}"
                    />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Пароль -->
                <div>
                    <label for="register-password" class="block text-sm font-medium text-slate-800 mb-2">
                        Пароль
                    </label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="register-password"
                            name="password"
                            required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
                            placeholder="Введите пароль"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-700 transition-colors"
                        >
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Подтверждение пароля -->
                <div>
                    <label for="register-password-confirmation" class="block text-sm font-medium text-slate-800 mb-2">
                        Подтвердите пароль
                    </label>
                    <div class="relative">
                        <input
                            :type="showConfirmPassword ? 'text' : 'password'"
                            id="register-password-confirmation"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
                            placeholder="Подтвердите пароль"
                        />
                        <button
                            type="button"
                            @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-700 transition-colors"
                        >
                            <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Ошибки -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-100 rounded-md p-4">
                        <p class="text-red-500 text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Кнопка отправки -->
                <button
                    type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-md transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Зарегистрироваться
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
