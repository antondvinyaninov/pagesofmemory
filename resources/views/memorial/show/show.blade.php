@extends('layouts.app')

@section('title', $memorial->first_name . ' ' . $memorial->last_name . ' - Страница памяти')

@section('content')
<div class="min-h-screen bg-gray-200 pt-6" x-data="{ activeTab: 'memories' }">
    <!-- Hero блок -->
    @include('memorial.show.partials.hero', ['memorial' => $memorial])

    <!-- Мобильное меню на всю ширину -->
    <div class="lg:hidden bg-white shadow-md mb-4">
        <div class="flex overflow-x-scroll gap-2 px-4 py-4" style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
            <button @click="activeTab = 'memories'" :class="activeTab === 'memories' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Воспоминания
            </button>
            <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                О человеке
            </button>
            <button @click="activeTab = 'burial'" :class="activeTab === 'burial' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                Захоронение
            </button>
            <button @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Медиа
            </button>
            <button @click="activeTab = 'people'" :class="activeTab === 'people' ? 'bg-red-50 text-red-600' : 'text-slate-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Близкие люди
            </button>
        </div>
    </div>

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

// Отслеживание просмотров воспоминаний
const viewedMemories = new Set();
const memoryObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
            const memoryId = entry.target.dataset.memoryId;
            
            // Если воспоминание еще не было просмотрено
            if (!viewedMemories.has(memoryId)) {
                viewedMemories.add(memoryId);
                
                // Отправляем запрос на увеличение счетчика
                fetch(`/memory/${memoryId}/view`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).catch(error => console.error('Ошибка отслеживания просмотра:', error));
            }
        }
    });
}, {
    threshold: 0.5 // Считаем просмотром когда 50% воспоминания видно
});

// Наблюдаем за всеми воспоминаниями
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-memory-id]').forEach(memory => {
        memoryObserver.observe(memory);
    });
});
</script>
@endsection
