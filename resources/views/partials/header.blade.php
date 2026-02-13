<header class="bg-slate-700 shadow-lg mb-0">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16 gap-1">
            <!-- Логотип -->
            <div class="flex items-center">
                <a href="/" class="flex items-center gap-2 text-xl font-bold text-white hover:text-blue-300 transition-colors">
                    <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Страницы памяти
                    <span class="ml-2 inline-flex items-center rounded-full border border-red-200 bg-red-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-red-600">
                        beta
                    </span>
                </a>
            </div>

            <!-- Поиск -->
            <div class="relative block self-center w-[520px] sm:w-[420px] md:w-[520px]">
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

            <!-- Навигация -->
            <nav class="flex items-center gap-6">
                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded transition-colors">
                        Вход
                    </a>
                @else
                    <div class="flex items-center gap-2 text-white">
                        <div class="w-7 h-7 bg-white/10 rounded-full overflow-hidden">
                            <span class="w-7 h-7 inline-flex items-center justify-center text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                        </div>
                        <span class="hidden sm:inline">Привет, {{ auth()->user()->name }}!</span>
                    </div>
                    <a href="/profile" class="text-white hover:text-blue-300 font-medium transition-colors">
                        Профиль
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-2 text-white hover:text-red-500 hover:bg-slate-600 rounded transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="hidden sm:inline">Выйти</span>
                        </button>
                    </form>
                @endguest
            </nav>
        </div>
    </div>
</header>
