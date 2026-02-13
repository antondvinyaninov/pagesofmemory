<header class="bg-slate-700 shadow-lg mb-0">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-3 items-center h-16 gap-4">
            <!-- Логотип слева -->
            <div class="flex items-center">
                <a href="/" class="flex items-center gap-2 text-xl font-bold text-white hover:text-blue-300 transition-colors">
                    <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="hidden md:inline">Страницы памяти</span>
                    <span class="ml-2 inline-flex items-center rounded-full border border-red-200 bg-red-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-red-600">
                        beta
                    </span>
                </a>
            </div>

            <!-- Поиск по центру -->
            <div class="flex justify-center">
                <div class="w-full max-w-xl">
                    <label for="header-search" class="sr-only">Поиск страниц памяти</label>
                    <div class="relative">
                        <svg class="pointer-events-none w-3.5 h-3.5 text-red-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            id="header-search"
                            type="text"
                            placeholder="Найдите страницу памяти: ФИО, годы жизни или город"
                            class="w-full pl-9 pr-8 py-1.5 rounded-card bg-white/95 text-slate-800 placeholder:text-gray-400 border border-slate-300 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 shadow-sm"
                        />
                    </div>
                </div>
            </div>

            <!-- Навигация справа -->
            <nav class="flex items-center justify-end">
                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded transition-colors">
                        Вход
                    </a>
                @else
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 text-white hover:text-blue-300 transition-colors">
                            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </button>
                        
                        <!-- Dropdown меню -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Профиль
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Выйти
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </nav>
        </div>
    </div>
</header>
