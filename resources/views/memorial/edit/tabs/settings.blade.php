                        <div x-show="activeTab === 'settings'" class="space-y-6">
                            @if(!$memorial->exists)
                            <div class="p-5 bg-amber-50 border-2 border-amber-300 rounded-lg">
                                <h4 class="text-base font-semibold text-amber-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Подтверждение ответственности
                                </h4>
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="confirm_responsibility" required class="mt-1 w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                    <span class="text-sm text-gray-800 leading-relaxed">
                                        Я подтверждаю, что являюсь родственником или близким человеком усопшего, и беру на себя ответственность за достоверность предоставленной информации. Я подтверждаю факт смерти данного человека.
                                    </span>
                                </label>
                            </div>
                            @endif

                            <div>
                                <h4 class="text-base font-semibold text-slate-700 mb-3">Приватность</h4>
                                <p class="text-sm text-gray-600 mb-4">Выберите, кто может видеть этот мемориал</p>
                                <div class="space-y-3">
                                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="privacy" value="public" {{ old('privacy', $memorial->privacy ?? 'public') == 'public' ? 'checked' : '' }} class="mt-1 text-red-500 focus:ring-red-500" />
                                        <div>
                                            <span class="font-medium text-gray-900">Публичный мемориал</span>
                                            <p class="text-sm text-gray-500 mt-1">Доступен всем пользователям интернета</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="privacy" value="family" {{ old('privacy', $memorial->privacy ?? 'public') == 'family' ? 'checked' : '' }} class="mt-1 text-red-500 focus:ring-red-500" />
                                        <div>
                                            <span class="font-medium text-gray-900">Только для семьи и друзей</span>
                                            <p class="text-sm text-gray-500 mt-1">Доступен только приглашенным людям</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="privacy" value="private" {{ old('privacy', $memorial->privacy ?? 'public') == 'private' ? 'checked' : '' }} class="mt-1 text-red-500 focus:ring-red-500" />
                                        <div>
                                            <span class="font-medium text-gray-900">Приватный</span>
                                            <p class="text-sm text-gray-500 mt-1">Доступен только вам</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-gray-200">
                                <h4 class="text-base font-semibold text-slate-700 mb-3">Модерация воспоминаний</h4>
                                <p class="text-sm text-gray-600 mb-4">Контролируйте публикацию воспоминаний от других пользователей</p>
                                <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="moderate_memories" value="1" {{ old('moderate_memories', $memorial->moderate_memories ?? false) ? 'checked' : '' }} class="mt-1 text-red-500 focus:ring-red-500" />
                                    <div>
                                        <span class="font-medium text-gray-900">Проверять воспоминания перед публикацией</span>
                                        <p class="text-sm text-gray-500 mt-1">Вы будете получать уведомления о новых воспоминаниях и сможете одобрить или отклонить их</p>
                                    </div>
                                </label>
                            </div>

                            <div class="pt-6 border-t border-gray-200">
                                <h4 class="text-base font-semibold text-slate-700 mb-3">Комментарии</h4>
                                <p class="text-sm text-gray-600 mb-4">Разрешите или запретите комментарии к воспоминаниям</p>
                                <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="allow_comments" value="1" {{ old('allow_comments', $memorial->allow_comments ?? true) ? 'checked' : '' }} class="mt-1 text-red-500 focus:ring-red-500" />
                                    <div>
                                        <span class="font-medium text-gray-900">Разрешить комментарии</span>
                                        <p class="text-sm text-gray-500 mt-1">Пользователи смогут оставлять комментарии к воспоминаниям</p>
                                    </div>
                                </label>
                            </div>
                        </div>
