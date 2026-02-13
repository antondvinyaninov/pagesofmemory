@extends('layouts.app')

@section('title', $memorial->first_name . ' ' . $memorial->last_name . ' - Страница памяти')

@section('content')
<div class="min-h-screen bg-gray-200 pt-6" x-data="{ activeTab: 'memories' }">
    <!-- Hero блок -->
    @include('memorial.show.partials.hero', ['memorial' => $memorial])

    <!-- Основной контент -->
    <div class="container mx-auto px-4 pb-16">
        <div class="grid lg:grid-cols-[280px_1fr] gap-4">
            <!-- Сайдбар с меню -->
            @include('memorial.show.partials.sidebar')

            <!-- Основной контент с вкладками -->
            <main class="space-y-4">
                <!-- Вкладка: Воспоминания -->
                <div x-show="activeTab === 'memories'" x-transition>
                    @include('memorial.show.tabs.memories', ['memories' => $memories])
                </div>

                <!-- Вкладка: О человеке -->
                <div x-show="activeTab === 'about'" x-transition>
                    @include('memorial.show.tabs.about', ['memorial' => $memorial])
                </div>

                <!-- Вкладка: Захоронение -->
                <div x-show="activeTab === 'burial'" x-transition>
                    @include('memorial.show.tabs.burial', ['memorial' => $memorial])
                </div>

                <!-- Вкладка: Медиа -->
                <div x-show="activeTab === 'media'" x-transition>
                    @include('memorial.show.tabs.media')
                </div>

                <!-- Вкладка: Близкие люди -->
                <div x-show="activeTab === 'people'" x-transition>
                    @include('memorial.show.tabs.people')
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра фото -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4" onclick="closePhotoModal()">
    <div class="relative max-w-7xl max-h-full">
        <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Фото" class="max-w-full max-h-[90vh] object-contain rounded-lg">
    </div>
</div>

<script>
function openPhotoModal(url) {
    event.stopPropagation();
    document.getElementById('modalImage').src = url;
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Лайк воспоминания
async function likeMemory(memoryId, button) {
    try {
        const response = await fetch(`/memory/${memoryId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            button.querySelector('span').textContent = data.likes;
            
            const svg = button.querySelector('svg');
            const path = svg.querySelector('path');
            
            if (data.liked) {
                // Лайк поставлен - закрашиваем сердце красным
                svg.setAttribute('fill', '#ef4444');
                svg.removeAttribute('stroke');
                // Убираем stroke атрибуты с path
                path.removeAttribute('stroke-linecap');
                path.removeAttribute('stroke-linejoin');
                path.removeAttribute('stroke-width');
                button.classList.remove('text-gray-500');
            } else {
                // Лайк убран - делаем контур серым
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', '#6b7280');
                // Возвращаем stroke атрибуты на path
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('stroke-width', '2');
                button.classList.add('text-gray-500');
            }
        }
    } catch (error) {
        console.error('Ошибка лайка:', error);
    }
}

// Лайк комментария
async function likeComment(commentId, button) {
    try {
        const response = await fetch(`/comment/${commentId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.success) {
            button.querySelector('span').textContent = data.likes;
            
            const svg = button.querySelector('svg');
            if (data.liked) {
                // Лайк поставлен - закрашиваем сердце красным
                svg.setAttribute('fill', '#ef4444'); // red-500
                svg.removeAttribute('stroke');
                button.classList.remove('text-gray-400');
            } else {
                // Лайк убран - делаем контур серым
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', '#9ca3af'); // gray-400
                button.classList.add('text-gray-400');
            }
        }
    } catch (error) {
        console.error('Ошибка лайка комментария:', error);
    }
}

// Отправка комментария
async function submitComment(memoryId, form) {
    event.preventDefault();
    
    const input = form.querySelector('input[type="text"]');
    const content = input.value.trim();
    
    if (!content) return;
    
    try {
        const response = await fetch(`/memory/${memoryId}/comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Добавляем новый комментарий в начало списка
            const commentsContainer = form.closest('.border-t');
            const spaceY3 = commentsContainer.querySelector('.space-y-3');
            const newComment = createCommentElement(data.comment);
            spaceY3.insertAdjacentHTML('afterbegin', newComment);
            
            // Очищаем поле ввода
            input.value = '';
            
            // Обновляем счетчик комментариев
            const memoryCard = form.closest('[x-data]');
            const commentButton = memoryCard.querySelector('[\\@click*="showComments"]');
            const countSpan = commentButton.querySelector('span');
            countSpan.textContent = parseInt(countSpan.textContent) + 1;
            
            // Закрашиваем иконку синим (пользователь теперь имеет комментарий)
            const svg = commentButton.querySelector('svg');
            const path = svg.querySelector('path');
            svg.setAttribute('fill', '#3b82f6'); // blue-500
            svg.removeAttribute('stroke');
            path.removeAttribute('stroke-linecap');
            path.removeAttribute('stroke-linejoin');
            path.removeAttribute('stroke-width');
        }
    } catch (error) {
        console.error('Ошибка отправки комментария:', error);
    }
}

function createCommentElement(comment) {
    const timeAgo = new Date(comment.created_at).toLocaleString('ru-RU');
    return `
        <div class="flex gap-3">
            <a href="/user/id1" class="flex-shrink-0">
                <img 
                    src="${comment.author_avatar}" 
                    alt="${comment.author_name}"
                    class="w-12 h-12 rounded-md object-cover hover:opacity-80 transition-opacity cursor-pointer"
                />
            </a>
            <div class="flex-1 min-w-0">
                <div class="bg-white rounded-lg px-3 py-2 border border-gray-200">
                    <a href="/user/id1" class="hover:underline">
                        <h5 class="font-semibold text-slate-700 text-sm mb-1">${comment.author_name}</h5>
                    </a>
                    <p class="text-slate-600 text-xs leading-relaxed">${comment.content}</p>
                </div>
                <div class="flex items-center gap-3 mt-1 px-2">
                    <button onclick="likeComment(${comment.id}, this)" class="flex items-center gap-1 text-gray-400 hover:text-red-500 transition-colors text-xs">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span>${comment.likes}</span>
                    </button>
                    <span class="text-gray-400 text-xs">только что</span>
                </div>
            </div>
        </div>
    `;
}
</script>
@endsection
