<!-- Сайдбар -->
<aside class="lg:sticky lg:top-4 h-fit">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <nav class="p-0">
            <!-- Мобильная версия -->
            <div class="lg:hidden flex overflow-x-auto gap-2 p-4 scrollbar-hide">
                <button @click="activeTab = 'memories'" :class="activeTab === 'memories' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Воспоминания
                </button>
                <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    О человеке
                </button>
                <button @click="activeTab = 'burial'" :class="activeTab === 'burial' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    Захоронение
                </button>
                <button @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Медиа
                </button>
                <button @click="activeTab = 'people'" :class="activeTab === 'people' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Близкие люди
                </button>
                @auth
                <button @click="activeTab = 'statistics'" :class="activeTab === 'statistics' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Статистика
                </button>
                <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Настройки
                </button>
                @endauth
            </div>

            <!-- Десктопная версия -->
            <div class="hidden lg:block">
                <button @click="activeTab = 'memories'" :class="activeTab === 'memories' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-red-50 hover:text-red-600'" class="flex items-center gap-4 px-6 py-5 w-full text-left border-b border-gray-100 font-medium transition-all duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-base">Воспоминания</span>
                </button>
                <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-red-50 hover:text-red-600'" class="flex items-center gap-4 px-6 py-5 w-full text-left border-b border-gray-100 font-medium transition-all duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-base">О человеке</span>
                </button>
                <button @click="activeTab = 'burial'" :class="activeTab === 'burial' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-red-50 hover:text-red-600'" class="flex items-center gap-4 px-6 py-5 w-full text-left border-b border-gray-100 font-medium transition-all duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    <span class="text-base">Захоронение</span>
                </button>
                <button @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-red-50 hover:text-red-600'" class="flex items-center gap-4 px-6 py-5 w-full text-left border-b border-gray-100 font-medium transition-all duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-base">Медиа</span>
                </button>
                <button @click="activeTab = 'people'" :class="activeTab === 'people' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-red-50 hover:text-red-600'" class="flex items-center gap-4 px-6 py-5 w-full text-left border-b border-gray-100 font-medium transition-all duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="text-base">Близкие люди</span>
                </button>
                @auth
                <button @click="activeTab = 'statistics'" :class="activeTab === 'statistics' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-red-50 hover:text-red-600'" class="flex items-center gap-4 px-6 py-5 w-full text-left border-b border-gray-100 font-medium transition-all duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="text-base">Статистика</span>
                </button>
                <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-red-50 hover:text-red-600'" class="flex items-center gap-4 px-6 py-5 w-full text-left font-medium transition-all duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-base">Настройки</span>
                </button>
                @endauth
            </div>
        </nav>
    </div>
</aside>
