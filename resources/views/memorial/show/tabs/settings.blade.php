<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Настройки мемориала
        </h3>
    </div>
    
    <div class="p-6 space-y-6">
        <div>
            <h4 class="text-base font-semibold text-slate-700 mb-3">Приватность</h4>
            <div class="space-y-2">
                <label class="flex items-center gap-3">
                    <input type="radio" name="privacy" class="text-red-500" checked />
                    <span>Публичный мемориал</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="radio" name="privacy" class="text-red-500" />
                    <span>Только для семьи и друзей</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="radio" name="privacy" class="text-red-500" />
                    <span>Приватный</span>
                </label>
            </div>
        </div>
        
        <div>
            <h4 class="text-base font-semibold text-slate-700 mb-3">Модерация</h4>
            <label class="flex items-center gap-3">
                <input type="checkbox" class="text-red-500" checked />
                <span>Проверять воспоминания перед публикацией</span>
            </label>
        </div>

        <div class="pt-4 border-t border-gray-200">
            <button class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors">
                Сохранить настройки
            </button>
        </div>
    </div>
</div>
