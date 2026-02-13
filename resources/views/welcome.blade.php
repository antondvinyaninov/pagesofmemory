@extends('layouts.app')

@section('title', 'Главная - Страницы памяти')

@section('content')
<div class="bg-gray-200">
    <div class="container mx-auto px-4 pt-6 pb-16">
        <!-- Приветствие -->
        <section class="mb-16">
            <div class="bg-white rounded-card shadow-lg text-center p-8 animate-fade-in">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h1 class="text-5xl font-bold text-slate-700 mb-4">Память о близких</h1>
                <p class="text-xl text-gray-500 mb-8 max-w-2xl mx-auto leading-relaxed">
                    Сохраните драгоценные воспоминания о ваших близких для будущих поколений
                </p>
                
                @auth
                    <div class="bg-gray-50 rounded-card shadow-md p-6 max-w-md mx-auto">
                        <h2 class="text-2xl font-semibold text-slate-700 mb-4">
                            С возвращением, {{ auth()->user()->name }}! 👋
                        </h2>
                        <p class="text-gray-500 mb-6">
                            Готовы продолжить работу с вашими воспоминаниями?
                        </p>
                        <a href="/profile" class="inline-block bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover">
                            Открыть профиль
                        </a>
                    </div>
                @else
                    <div class="flex justify-center gap-4 mb-4 max-w-lg mx-auto">
                        <a href="/register" class="flex-1 max-w-48 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Регистрация
                        </a>
                        <a href="/login" class="flex-1 max-w-48 border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white font-medium py-3 px-4 rounded transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Войти
                        </a>
                    </div>
                    <p class="text-gray-500 text-sm">
                        Зарегистрируйтесь, чтобы создать страницу памяти
                    </p>
                @endauth
            </div>
        </section>

        <!-- Статистика -->
        <section class="mb-16">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in">
                    <svg class="w-8 h-8 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="text-gray-500 text-sm mb-2">Фотографий</div>
                    <div class="text-2xl font-semibold text-slate-700 leading-none">15,832</div>
                </div>
                <div class="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in-delay-1">
                    <svg class="w-8 h-8 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <div class="text-gray-500 text-sm mb-2">Историй</div>
                    <div class="text-2xl font-semibold text-slate-700 leading-none">2,431</div>
                </div>
                <div class="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in-delay-2">
                    <svg class="w-8 h-8 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <div class="text-gray-500 text-sm mb-2">Пользователей</div>
                    <div class="text-2xl font-semibold text-slate-700 leading-none">8,521</div>
                </div>
                <div class="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in-delay-3">
                    <svg class="w-8 h-8 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <div class="text-gray-500 text-sm mb-2">Воспоминаний</div>
                    <div class="text-2xl font-semibold text-slate-700 leading-none">42,981</div>
                </div>
            </div>
        </section>

        <!-- Как это работает -->
        <section class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-700 mb-4">Как это работает</h2>
                <p class="text-xl text-gray-500 max-w-2xl mx-auto">
                    Простые шаги для создания страницы памяти о ваших близких
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Шаг 1 -->
                <div class="text-center group">
                    <div class="bg-white rounded-card shadow-md hover:shadow-lg p-6 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-100 transition-colors">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-700 mb-3">1. Регистрация</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Создайте аккаунт и получите доступ к созданию страниц памяти
                        </p>
                    </div>
                </div>

                <!-- Шаг 2 -->
                <div class="text-center group">
                    <div class="bg-white rounded-card shadow-md hover:shadow-lg p-6 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-100 transition-colors">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-700 mb-3">2. Загрузка</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Добавьте фотографии, видео и напишите историю жизни близкого человека
                        </p>
                    </div>
                </div>

                <!-- Шаг 3 -->
                <div class="text-center group">
                    <div class="bg-white rounded-card shadow-md hover:shadow-lg p-6 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-100 transition-colors">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-700 mb-3">3. Поделиться</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Пригласите родных и друзей делиться воспоминаниями и фотографиями
                        </p>
                    </div>
                </div>
            </div>

            <!-- Дополнительная информация -->
            <div class="mt-12 bg-slate-50 rounded-card p-6 text-center">
                <div class="max-w-3xl mx-auto">
                    <h3 class="text-xl font-semibold text-slate-700 mb-3">
                        Сохраните память навсегда
                    </h3>
                    <p class="text-gray-500 mb-6">
                        Все данные надежно защищены и сохранены для будущих поколений. 
                        Создайте цифровое наследие, которое останется с вашей семьей навсегда.
                    </p>
                    <div class="flex justify-center gap-4 flex-wrap">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Безопасное хранение
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Неограниченные фото
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Доступ всей семье
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Последние страницы памяти -->
        <section>
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-2xl font-bold text-slate-700">Последние страницы памяти</h2>
                <a href="/memorials" class="border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white font-medium py-2 px-4 rounded text-sm transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Показать все
                </a>
            </div>
            <p class="text-gray-500 mb-6">Здесь отображаются недавно созданные или обновлённые страницы памяти. Используйте поиск выше, чтобы быстро найти нужного человека.</p>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Карточка мемориала 1 -->
                <div class="bg-white rounded-card shadow-md hover:shadow-lg overflow-hidden hover:-translate-y-1 transition-all duration-300">
                    <div class="aspect-square bg-gray-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-slate-700 mb-1">
                            Иван Петрович Смирнов
                        </h4>
                        <p class="text-gray-500 text-sm mb-3">1945 - 2023</p>
                        <div class="flex justify-between items-center">
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                32 воспоминания
                            </span>
                            <a href="/memorial/1" class="border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-1 px-3 rounded text-sm transition-all duration-300 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Смотреть
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Карточка мемориала 2 -->
                <div class="bg-white rounded-card shadow-md hover:shadow-lg overflow-hidden hover:-translate-y-1 transition-all duration-300">
                    <div class="aspect-square bg-gray-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-slate-700 mb-1">
                            Анна Сергеевна Иванова
                        </h4>
                        <p class="text-gray-500 text-sm mb-3">1938 - 2022</p>
                        <div class="flex justify-between items-center">
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                18 воспоминаний
                            </span>
                            <a href="/memorial/2" class="border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-1 px-3 rounded text-sm transition-all duration-300 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Смотреть
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Карточка мемориала 3 -->
                <div class="bg-white rounded-card shadow-md hover:shadow-lg overflow-hidden hover:-translate-y-1 transition-all duration-300">
                    <div class="aspect-square bg-gray-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="p-4">
                        <h4 class="text-lg font-semibold text-slate-700 mb-1">
                            Елена Павловна Соколова
                        </h4>
                        <p class="text-gray-500 text-sm mb-3">1942 - 2023</p>
                        <div class="flex justify-between items-center">
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                24 воспоминания
                            </span>
                            <a href="/memorial/3" class="border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-1 px-3 rounded text-sm transition-all duration-300 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Смотреть
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
