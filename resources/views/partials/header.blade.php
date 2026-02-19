<header class="mb-0 bg-gray-200">
    <div class="container mx-auto px-3 sm:px-4 pt-4">
        <div class="relative rounded-2xl border border-slate-700 bg-slate-800 shadow-xl overflow-visible">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.15),_transparent_44%),radial-gradient(circle_at_bottom_left,_rgba(148,163,184,0.2),_transparent_50%),linear-gradient(135deg,_#1e293b_0%,_#111827_60%,_#0b1120_100%)] rounded-2xl"></div>
            <div class="relative py-3 sm:py-0" x-data="{ mobileSearchOpen: false }">
                <div class="flex items-center justify-between sm:grid sm:grid-cols-3 sm:items-center sm:h-16 sm:gap-4">
                <!-- Логотип слева -->
                <div class="flex items-center px-4 sm:px-6">
                    <a href="/" class="flex items-center gap-2 text-lg sm:text-xl font-bold text-white transition-colors hover:text-sky-200">
                        <img src="{{ project_icon_url() }}" alt="{{ project_site_name() }}" class="h-7 w-7 rounded-md object-contain">
                        <span class="inline max-w-[120px] sm:max-w-[180px] lg:max-w-[220px] truncate text-sm sm:text-base">{{ project_site_name() }}</span>
                        <span class="ml-2 hidden sm:inline-flex items-center rounded-full border border-slate-400 bg-slate-600 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-slate-100">
                            beta
                        </span>
                    </a>
                </div>

                <!-- Поиск по центру -->
                <div class="hidden sm:flex justify-center">
                    <div class="w-full max-w-xl">
                        <label for="header-search" class="sr-only">Поиск страниц памяти</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input
                                id="header-search"
                                type="text"
                                placeholder="Найдите страницу памяти: ФИО, годы жизни или город"
                                class="w-full rounded-card border border-slate-300 bg-white py-1.5 pl-9 pr-8 text-slate-800 placeholder:text-gray-400 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-1 focus:ring-sky-400"
                            />
                        </div>
                    </div>
                </div>

                <!-- Навигация справа -->
                <nav class="flex items-center justify-end px-4 sm:px-6">
                    <button
                        type="button"
                        @click="mobileSearchOpen = !mobileSearchOpen"
                        class="sm:hidden mr-2 inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-white transition-colors hover:bg-white/20"
                        aria-label="Открыть поиск">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    @guest
                        <a href="{{ route('login') }}" class="whitespace-nowrap rounded border border-red-400/70 bg-red-500 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-red-600 sm:px-4 sm:text-base">
                            Вход
                        </a>
                    @else
                        <div class="relative" style="z-index: 9999;">
                            <button 
                                type="button"
                                onclick="toggleUserMenu(event)"
                                class="flex items-center gap-2 text-white transition-colors hover:text-sky-200">
                                <img 
                                    src="{{ avatar_url(auth()->user()) }}" 
                                    alt="{{ auth()->user()->name }}" 
                                    class="h-10 w-10 rounded-full border-2 border-white/20 object-cover">
                            </button>
                            
                            <!-- Dropdown меню -->
                            <div 
                                id="userMenu"
                                class="hidden absolute right-0 mt-2 w-48 rounded-lg bg-white py-2 shadow-xl border border-gray-200"
                                style="z-index: 9999;">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    Профиль
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors">
                                        Выйти
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <script>
                            function toggleUserMenu(event) {
                                event.stopPropagation();
                                const menu = document.getElementById('userMenu');
                                menu.classList.toggle('hidden');
                            }
                            
                            // Закрыть меню при клике вне его
                            document.addEventListener('click', function(event) {
                                const menu = document.getElementById('userMenu');
                                const button = event.target.closest('button[onclick*="toggleUserMenu"]');
                                const clickedInsideMenu = menu && menu.contains(event.target);
                                
                                if (!button && !clickedInsideMenu && menu && !menu.classList.contains('hidden')) {
                                    menu.classList.add('hidden');
                                }
                            });
                        </script>
                    @endguest
                </nav>
            </div>

            <div x-show="mobileSearchOpen" x-transition class="sm:hidden px-4 pb-3">
                <label for="header-search-mobile" class="sr-only">Поиск страниц памяти</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input
                        id="header-search-mobile"
                        type="text"
                        placeholder="Поиск страницы памяти"
                        class="w-full rounded-card border border-slate-300 bg-white py-1.5 pl-9 pr-3 text-sm text-slate-800 placeholder:text-gray-400 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-1 focus:ring-sky-400"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
</header>
