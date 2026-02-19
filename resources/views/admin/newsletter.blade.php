@extends('layouts.admin')

@section('title', 'Рассылки')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
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

    <div class="bg-white rounded-lg shadow">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">Подключение почты</h3>
        </div>
        <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-xs text-gray-500">Mailer</p>
                <p class="text-sm font-semibold text-slate-800 mt-1">{{ $mailStatus['mailer'] }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-xs text-gray-500">SMTP Host / Port</p>
                <p class="text-sm font-semibold text-slate-800 mt-1">{{ $mailStatus['host'] }} : {{ $mailStatus['port'] }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-xs text-gray-500">From</p>
                <p class="text-sm font-semibold text-slate-800 mt-1">{{ $mailStatus['from_name'] }}</p>
                <p class="text-xs text-gray-500">{{ $mailStatus['from_address'] }}</p>
            </div>
            <div class="border rounded-lg p-4 {{ $mailStatus['is_configured'] ? 'border-green-200 bg-green-50' : 'border-amber-200 bg-amber-50' }}">
                <p class="text-xs {{ $mailStatus['is_configured'] ? 'text-green-700' : 'text-amber-700' }}">Статус</p>
                <p class="text-sm font-semibold mt-1 {{ $mailStatus['is_configured'] ? 'text-green-800' : 'text-amber-800' }}">
                    {{ $mailStatus['is_configured'] ? 'Настроено' : 'Проверьте MAIL_FROM_ADDRESS в .env' }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Системные шаблоны уведомлений</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3">
                @foreach($systemTemplates as $templateKey => $templateName)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <p class="text-sm font-medium text-slate-800">{{ $templateName }}</p>
                        <p class="text-xs text-gray-500 mt-1">Ключ: {{ $templateKey }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Тест отправки</h3>
            </div>
            <div class="p-4 sm:p-6">
                <form action="{{ route('admin.newsletter.test') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="test_email" class="block text-sm font-medium text-gray-700 mb-1">Email для теста</label>
                        <input id="test_email" name="test_email" type="email" value="{{ old('test_email', auth()->user()->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="test_subject" class="block text-sm font-medium text-gray-700 mb-1">Тема</label>
                        <input id="test_subject" name="subject" type="text" value="{{ old('subject', 'Тестовое письмо от Memory') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="test_content" class="block text-sm font-medium text-gray-700 mb-1">Текст письма</label>
                        <textarea id="test_content" name="content" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>{{ old('content', "Это тестовое письмо.\nЕсли вы его получили, почта настроена корректно.") }}</textarea>
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors font-medium">
                        Отправить тестовое письмо
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">Массовая рассылка</h3>
        </div>
        <div class="p-4 sm:p-6">
            <form action="{{ route('admin.newsletter.send') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Аудитория</label>
                    <select id="audience" name="audience" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                        @foreach($audiences as $audience)
                            <option value="{{ $audience['key'] }}" {{ old('audience') === $audience['key'] ? 'selected' : '' }}>
                                {{ $audience['label'] }} ({{ $audience['count'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="campaign_subject" class="block text-sm font-medium text-gray-700 mb-1">Тема письма</label>
                    <input id="campaign_subject" name="subject" type="text" value="{{ old('subject') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                </div>
                <div>
                    <label for="campaign_content" class="block text-sm font-medium text-gray-700 mb-1">Содержание</label>
                    <textarea id="campaign_content" name="content" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" required>{{ old('content') }}</textarea>
                </div>
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white rounded-lg transition-colors font-medium">
                    Запустить рассылку
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
