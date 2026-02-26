@extends('layouts.admin')

@section('title', 'Мемориалы')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Фильтр по статусу -->
    <div class="mb-4 flex gap-2">
        <a href="{{ route('admin.memorials', ['status' => 'all']) }}" 
           class="px-4 py-2 rounded {{ request('status', 'all') === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Все
        </a>
        <a href="{{ route('admin.memorials', ['status' => 'published']) }}" 
           class="px-4 py-2 rounded {{ request('status') === 'published' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Опубликованные
        </a>
        <a href="{{ route('admin.memorials', ['status' => 'draft']) }}" 
           class="px-4 py-2 rounded {{ request('status') === 'draft' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Черновики
        </a>
    </div>

    <!-- Таблица мемориалов -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Автор</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Статус</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Дата</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($memorials as $memorial)
                        <tr>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">{{ $memorial->id }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">
                                <a href="{{ route('memorial.show', ['id' => $memorial->id]) }}" class="text-blue-600 hover:underline">
                                    {{ $memorial->last_name }} {{ $memorial->first_name }}
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden lg:table-cell">
                                <a href="{{ route('user.show', ['id' => $memorial->user_id]) }}" class="text-blue-600 hover:underline">
                                    {{ $memorial->user->name }}
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm hidden sm:table-cell">
                                <span class="px-2 py-1 text-xs rounded-full {{ $memorial->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $memorial->status === 'published' ? 'Опубликован' : 'Черновик' }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">{{ $memorial->created_at->format('d.m.Y') }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('memorial.edit', ['id' => $memorial->id]) }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm">
                                        Редактировать
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.memorials.delete', $memorial->id) }}" method="POST" onsubmit="return confirm('Удалить мемориал?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs sm:text-sm">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Пагинация -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $memorials->links() }}
            </div>
        </div>
    </div>
@endsection
