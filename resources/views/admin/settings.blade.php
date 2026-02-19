@extends('layouts.admin')

@section('title', 'Настройки')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Общие настройки</h3>
            </div>
            <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Название проекта</label>
                    <input id="site_name" name="site_name" type="text" value="{{ old('site_name', data_get($settings, 'general.site_name')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                </div>
                <div>
                    <label for="site_tagline" class="block text-sm font-medium text-gray-700 mb-1">Подзаголовок</label>
                    <input id="site_tagline" name="site_tagline" type="text" value="{{ old('site_tagline', data_get($settings, 'general.site_tagline')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div>
                    <label for="support_email" class="block text-sm font-medium text-gray-700 mb-1">Email поддержки</label>
                    <input id="support_email" name="support_email" type="email" value="{{ old('support_email', data_get($settings, 'general.support_email')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                    <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', data_get($settings, 'general.contact_phone')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div>
                    <label for="default_locale" class="block text-sm font-medium text-gray-700 mb-1">Язык по умолчанию</label>
                    <select id="default_locale" name="default_locale" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                        <option value="ru" {{ old('default_locale', data_get($settings, 'general.default_locale')) === 'ru' ? 'selected' : '' }}>Русский</option>
                        <option value="en" {{ old('default_locale', data_get($settings, 'general.default_locale')) === 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
                <div>
                    <label for="default_timezone" class="block text-sm font-medium text-gray-700 mb-1">Таймзона</label>
                    <input id="default_timezone" name="default_timezone" type="text" value="{{ old('default_timezone', data_get($settings, 'general.default_timezone')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                </div>
                <div class="md:col-span-2">
                    <label for="project_icon" class="block text-sm font-medium text-gray-700 mb-2">Иконка проекта</label>
                    <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                        @php
                            $customIconPath = trim((string) data_get($settings, 'branding.icon_path', ''));
                        @endphp
                        <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-slate-700">
                                <img src="{{ project_icon_url() }}" alt="Текущая иконка" class="h-10 w-10 object-contain">
                            </div>
                            <div class="text-sm text-gray-600">
                                <p class="font-medium text-slate-800">Текущий логотип используется в хедере, favicon и письмах</p>
                                <p>{{ $customIconPath !== '' ? 'Источник: загруженная иконка' : 'Источник: стандартное залитое сердце' }}</p>
                                <p>Загрузка: PNG, до 2 МБ (рекомендуется квадрат 512x512)</p>
                            </div>
                        </div>
                        <input id="project_icon" name="project_icon" type="file" accept="image/png" class="w-full text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-red-50 file:px-3 file:py-2 file:text-red-700 hover:file:bg-red-100">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="remove_project_icon" value="1" {{ old('remove_project_icon') ? 'checked' : '' }}>
                            Вернуть стандартную иконку проекта
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Доступ и функционал</h3>
            </div>
            <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                    <input type="checkbox" name="allow_registration" value="1" {{ old('allow_registration', data_get($settings, 'access.allow_registration')) ? 'checked' : '' }} class="mt-1">
                    <span>
                        <span class="block text-sm font-medium text-slate-800">Разрешить регистрацию</span>
                        <span class="block text-xs text-gray-500">Новые пользователи смогут создавать аккаунты.</span>
                    </span>
                </label>
                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                    <input type="checkbox" name="enable_memorial_creation" value="1" {{ old('enable_memorial_creation', data_get($settings, 'access.enable_memorial_creation')) ? 'checked' : '' }} class="mt-1">
                    <span>
                        <span class="block text-sm font-medium text-slate-800">Разрешить создание мемориалов</span>
                        <span class="block text-xs text-gray-500">Отключение блокирует создание новых страниц памяти.</span>
                    </span>
                </label>
                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                    <input type="checkbox" name="enable_memories" value="1" {{ old('enable_memories', data_get($settings, 'access.enable_memories')) ? 'checked' : '' }} class="mt-1">
                    <span>
                        <span class="block text-sm font-medium text-slate-800">Разрешить воспоминания</span>
                        <span class="block text-xs text-gray-500">Пользователи смогут добавлять воспоминания.</span>
                    </span>
                </label>
                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                    <input type="checkbox" name="enable_comments" value="1" {{ old('enable_comments', data_get($settings, 'access.enable_comments')) ? 'checked' : '' }} class="mt-1">
                    <span>
                        <span class="block text-sm font-medium text-slate-800">Разрешить комментарии</span>
                        <span class="block text-xs text-gray-500">Пользователи смогут комментировать воспоминания.</span>
                    </span>
                </label>
                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg md:col-span-2">
                    <input type="checkbox" name="enable_public_profiles" value="1" {{ old('enable_public_profiles', data_get($settings, 'access.enable_public_profiles')) ? 'checked' : '' }} class="mt-1">
                    <span>
                        <span class="block text-sm font-medium text-slate-800">Публичные профили пользователей</span>
                        <span class="block text-xs text-gray-500">Если отключено, просмотр чужих профилей блокируется.</span>
                    </span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-800">Модерация</h3>
                </div>
                <div class="p-4 sm:p-6 space-y-3">
                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="auto_publish_memorials" value="1" {{ old('auto_publish_memorials', data_get($settings, 'moderation.auto_publish_memorials')) ? 'checked' : '' }} class="mt-1">
                        <span>
                            <span class="block text-sm font-medium text-slate-800">Автопубликация мемориалов</span>
                            <span class="block text-xs text-gray-500">Новые мемориалы сразу получат статус опубликован.</span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="moderate_memories" value="1" {{ old('moderate_memories', data_get($settings, 'moderation.moderate_memories')) ? 'checked' : '' }} class="mt-1">
                        <span>
                            <span class="block text-sm font-medium text-slate-800">Модерация воспоминаний</span>
                            <span class="block text-xs text-gray-500">Подготовлено для включения ручной проверки контента.</span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="moderate_comments" value="1" {{ old('moderate_comments', data_get($settings, 'moderation.moderate_comments')) ? 'checked' : '' }} class="mt-1">
                        <span>
                            <span class="block text-sm font-medium text-slate-800">Модерация комментариев</span>
                            <span class="block text-xs text-gray-500">Подготовлено для включения ручной проверки комментариев.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-slate-800">Уведомления и обслуживание</h3>
                </div>
                <div class="p-4 sm:p-6 space-y-4">
                    <div>
                        <label for="admin_notification_email" class="block text-sm font-medium text-gray-700 mb-1">Email администратора для уведомлений</label>
                        <input id="admin_notification_email" name="admin_notification_email" type="email" value="{{ old('admin_notification_email', data_get($settings, 'notifications.admin_notification_email')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="notify_new_user" value="1" {{ old('notify_new_user', data_get($settings, 'notifications.notify_new_user')) ? 'checked' : '' }} class="mt-1">
                        <span class="text-sm text-slate-800">Уведомлять о новых пользователях</span>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="notify_new_memorial" value="1" {{ old('notify_new_memorial', data_get($settings, 'notifications.notify_new_memorial')) ? 'checked' : '' }} class="mt-1">
                        <span class="text-sm text-slate-800">Уведомлять о новых мемориалах</span>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                        <input type="checkbox" name="notify_new_comment" value="1" {{ old('notify_new_comment', data_get($settings, 'notifications.notify_new_comment')) ? 'checked' : '' }} class="mt-1">
                        <span class="text-sm text-slate-800">Уведомлять о новых комментариях</span>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-amber-200 bg-amber-50 rounded-lg">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', data_get($settings, 'maintenance.maintenance_mode')) ? 'checked' : '' }} class="mt-1">
                        <span>
                            <span class="block text-sm font-medium text-amber-800">Режим обслуживания</span>
                            <span class="block text-xs text-amber-700">Флаг сохраняется в настройках и готов к интеграции с middleware.</span>
                        </span>
                    </label>
                    <div>
                        <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">Сообщение режима обслуживания</label>
                        <input id="maintenance_message" name="maintenance_message" type="text" value="{{ old('maintenance_message', data_get($settings, 'maintenance.maintenance_message')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors font-medium">
                Сохранить настройки
            </button>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h4 class="text-sm font-semibold text-slate-800 mb-2">Примечание</h4>
        <p class="text-sm text-gray-600">
            Настройки сохраняются в базе данных (таблица <code>app_settings</code>). Часть переключателей уже используется в приложении, часть подготовлена для дальнейшей интеграции бизнес-логики.
        </p>
    </div>
</div>
@endsection
