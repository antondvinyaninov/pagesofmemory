@extends('layouts.admin')

@section('title', 'SEO')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Опубликовано мемориалов</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['published_memorials'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Черновиков</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['draft_memorials'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Обновлено за 7 дней</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['updated_last_week'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Оценка индексируемости</p>
            <p class="text-2xl font-bold {{ $stats['index_quality'] >= 80 ? 'text-green-600' : 'text-amber-600' }} mt-2">
                {{ $stats['index_quality'] }}%
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">Технический SEO-чеклист</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($checks as $check)
                <div class="px-4 sm:px-6 py-4 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-800">{{ $check['name'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $check['details'] }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $check['ok'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $check['ok'] ? 'OK' : 'Проверить' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Быстрые SEO-ссылки</h3>
            </div>
            <div class="p-4 sm:p-6 space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">Sitemap URL</p>
                    <a href="{{ $sitemapUrl }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">{{ $sitemapUrl }}</a>
                </div>
                <div>
                    <p class="text-gray-500">Пример URL мемориала</p>
                    <a href="{{ $sampleMemorialUrl }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">{{ $sampleMemorialUrl }}</a>
                </div>
                <div>
                    <p class="text-gray-500">robots.txt</p>
                    <a href="{{ url('/robots.txt') }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">{{ url('/robots.txt') }}</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Рекомендации</h3>
            </div>
            <div class="p-4 sm:p-6">
                <ul class="space-y-2 text-sm text-slate-700">
                    @foreach($recommendations as $recommendation)
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-0.5">•</span>
                            <span>{{ $recommendation }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex items-center justify-between gap-4">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">Текущее содержимое robots.txt</h3>
            <a href="{{ url('/robots.txt') }}" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 hover:underline">Открыть файл</a>
        </div>
        <div class="p-4 sm:p-6">
            <pre class="bg-slate-900 text-slate-100 rounded-lg p-4 text-xs sm:text-sm overflow-auto">{{ trim($robotsContent) !== '' ? $robotsContent : 'robots.txt не найден или пуст.' }}</pre>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-3">Контент-качество для SEO</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Опубликованные мемориалы без фото</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['without_photo'] }}</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Опубликованные мемориалы без краткой биографии</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['without_biography'] }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
