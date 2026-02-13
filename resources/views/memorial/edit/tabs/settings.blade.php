                        <div x-show="activeTab === 'settings'" class="space-y-6">
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
