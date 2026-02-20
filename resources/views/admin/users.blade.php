@extends('layouts.admin')

@section('title', 'Пользователи')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Таблица пользователей -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Email</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Роль</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Мемориалов</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">{{ $user->id }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">
                                <a href="{{ route('user.show', ['id' => $user->id]) }}" class="text-blue-600 hover:underline flex items-center gap-1">
                                    {{ $user->name }}
                                    @if($user->is_memorial)
                                        <span class="text-sm" title="Светлая память">🕊️</span>
                                    @endif
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden lg:table-cell">{{ $user->email }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm hidden sm:table-cell">
                                <span class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $user->role === 'admin' ? 'Админ' : 'Пользователь' }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">{{ $user->memorials_count }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    @if(!$user->is_memorial && $user->role !== 'admin')
                                        <button 
                                            onclick="showMemorialModal({{ $user->id }}, '{{ $user->name }}')"
                                            class="text-amber-600 hover:text-amber-800 text-xs sm:text-sm whitespace-nowrap"
                                        >
                                            В память
                                        </button>
                                        <span class="text-gray-300">|</span>
                                    @endif
                                    
                                    @if($user->role !== 'admin')
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Удалить пользователя?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs sm:text-sm">
                                            Удалить
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Пагинация -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        </div>
    </div>

<!-- Модальное окно для перевода в статус памяти -->
<div id="memorialModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-slate-700 mb-4">Перевести в статус памяти</h3>
        <p class="text-gray-600 mb-4">
            Вы переводите пользователя <strong id="userName"></strong> в статус памяти.
        </p>
        <p class="text-sm text-gray-500 mb-6">
            Будет создан мемориал, и все ссылки на профиль будут вести на страницу памяти.
        </p>
        
        <form id="memorialForm" method="POST" action="">
            @csrf
            <div class="flex gap-3">
                <button 
                    type="button" 
                    onclick="closeMemorialModal()"
                    class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors"
                >
                    Отмена
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors"
                >
                    Подтвердить
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showMemorialModal(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('memorialForm').action = `/admin/users/${userId}/convert-to-memorial`;
    document.getElementById('memorialModal').classList.remove('hidden');
}

function closeMemorialModal() {
    document.getElementById('memorialModal').classList.add('hidden');
}

// Закрытие по клику вне модального окна
document.getElementById('memorialModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeMemorialModal();
    }
});
</script>
@endsection
