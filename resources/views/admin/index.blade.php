@extends('layouts.admin')

@section('title', 'Главная')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Статистика -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs sm:text-sm text-gray-600">Пользователей</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $stats['users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs sm:text-sm text-gray-600">Мемориалов</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $stats['memorials'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs sm:text-sm text-gray-600">Воспоминаний</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $stats['memories'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Быстрые ссылки -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <a href="{{ route('admin.users') }}" class="bg-white rounded-lg shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">Управление пользователями</h3>
                <p class="text-xs sm:text-sm text-gray-600">Просмотр, редактирование и удаление пользователей</p>
            </a>

            <a href="{{ route('admin.memorials') }}" class="bg-white rounded-lg shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800 mb-2">Управление мемориалами</h3>
                <p class="text-xs sm:text-sm text-gray-600">Просмотр и модерация мемориалов</p>
            </a>
        </div>

        <!-- Последние пользователи -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Последние пользователи</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Email</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stats['recent_users'] as $user)
                        <tr>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">{{ $user->email }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d.m.Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Последние мемориалы -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-semibold text-slate-800">Последние мемориалы</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Автор</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stats['recent_memorials'] as $memorial)
                        <tr>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">
                                <a href="{{ route('memorial.show', ['id' => $memorial->id]) }}" class="text-blue-600 hover:underline">
                                    {{ $memorial->first_name }} {{ $memorial->last_name }}
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">{{ $memorial->user->name }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500">{{ $memorial->created_at->format('d.m.Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
