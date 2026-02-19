<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Comment;
use App\Models\Memory;
use App\Models\Memorial;
use App\Models\Relationship;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;

class MemoryController extends Controller
{
    public function store(Request $request, $memorialId)
    {
        if (!AppSetting::get('access.enable_memories', true)) {
            return redirect()->back()->with('error', 'Добавление воспоминаний временно отключено');
        }

        $memorial = Memorial::findOrFail($memorialId);

        if (!$this->canAccessMemorial($memorial)) {
            abort(403, 'У вас нет доступа к этому мемориалу');
        }

        if ($memorial->moderate_memories && (int) $memorial->user_id !== (int) auth()->id()) {
            return redirect()->route('memorial.show', ['id' => $memorialId])
                ->with('error', 'Владелец мемориала включил модерацию. Новые воспоминания временно недоступны.');
        }
        
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:10'],
            'media.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov', 'max:20480'], // 20MB
            'relationship_type' => ['nullable', 'string'],
            'relationship_custom' => ['nullable', 'string', 'max:255'],
        ]);

        // Проверяем, есть ли у пользователя связь с мемориалом
        $relationship = Relationship::where('memorial_id', $memorial->id)
            ->where('user_id', auth()->id())
            ->first();

        // Если связи нет и она указана в форме - создаем
        if (!$relationship && $request->filled('relationship_type')) {
            Relationship::create([
                'memorial_id' => $memorial->id,
                'user_id' => auth()->id(),
                'relationship_type' => $validated['relationship_type'],
                'custom_relationship' => $validated['relationship_custom'] ?? null,
                'confirmed' => true,
                'visible' => $validated['relationship_type'] !== 'not_specified', // Скрываем если "не хочу указывать"
            ]);
        }

        // Если это служебное сообщение из вкладки "Близкие люди" - не создаем воспоминание
        if ($validated['content'] === '[Связь установлена через вкладку Близкие люди]') {
            return redirect()->route('memorial.show', ['id' => $memorialId])
                ->with('success', 'Связь успешно установлена');
        }

        // Создаем воспоминание
        $memory = Memory::create([
            'memorial_id' => $memorial->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        app(EmailNotificationService::class)->sendNewMemoryNotification($memorial, $memory);

        // TODO: Обработка загрузки медиа файлов

        return redirect()->route('memorial.show', ['id' => $memorialId])
            ->with('success', 'Воспоминание успешно добавлено');
    }
    
    public function like(Request $request, $id)
    {
        $memory = Memory::with('memorial')->findOrFail($id);
        if (!$memory->memorial || !$this->canAccessMemorial($memory->memorial)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этому мемориалу',
            ], 403);
        }

        $userId = auth()->id();
        
        // Проверяем, есть ли уже лайк от этого пользователя
        $existingLike = \DB::table('memory_likes')
            ->where('memory_id', $memory->id)
            ->where('user_id', $userId)
            ->first();
        
        if ($existingLike) {
            // Удаляем лайк
            \DB::table('memory_likes')
                ->where('memory_id', $memory->id)
                ->where('user_id', $userId)
                ->delete();
            
            $memory->decrement('likes');
            $liked = false;
        } else {
            // Добавляем лайк
            \DB::table('memory_likes')->insert([
                'memory_id' => $memory->id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $memory->increment('likes');
            $liked = true;
        }
        
        return response()->json([
            'success' => true,
            'likes' => $memory->likes,
            'liked' => $liked
        ]);
    }
    
    public function comment(Request $request, $id)
    {
        if (!AppSetting::get('access.enable_comments', true)) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарии временно отключены',
            ], 403);
        }

        $memory = Memory::with('memorial')->findOrFail($id);

        if (!$memory->memorial || !$this->canAccessMemorial($memory->memorial)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этому мемориалу',
            ], 403);
        }

        if ($memory->memorial->allow_comments === false) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарии отключены владельцем мемориала',
            ], 403);
        }
        
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:500'],
        ]);
        
        $comment = Comment::create([
            'memory_id' => $memory->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        app(EmailNotificationService::class)->sendNewCommentNotification($memory, $comment);
        
        // Загружаем пользователя для ответа
        $comment->load('user');
        
        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'author_id' => $comment->user->id,
                'author_name' => $comment->user->name,
                'author_avatar' => $comment->user->avatar ? \Storage::disk('s3')->url($comment->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&size=128&background=f3e5f5&color=7b1fa2&bold=true',
                'content' => $comment->content,
                'created_at' => $comment->created_at->toDateTimeString(),
                'likes' => 0,
            ]
        ]);
    }
    
    public function likeComment(Request $request, $id)
    {
        $comment = Comment::with('memory.memorial')->findOrFail($id);
        $memorial = $comment->memory?->memorial;
        if (!$memorial || !$this->canAccessMemorial($memorial)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этому мемориалу',
            ], 403);
        }

        $userId = auth()->id();
        
        // Проверяем, есть ли уже лайк от этого пользователя
        $existingLike = \DB::table('comment_likes')
            ->where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();
        
        if ($existingLike) {
            // Удаляем лайк
            \DB::table('comment_likes')
                ->where('comment_id', $comment->id)
                ->where('user_id', $userId)
                ->delete();
            
            $comment->decrement('likes');
            $liked = false;
        } else {
            // Добавляем лайк
            \DB::table('comment_likes')->insert([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $comment->increment('likes');
            $liked = true;
        }
        
        return response()->json([
            'success' => true,
            'likes' => $comment->likes,
            'liked' => $liked
        ]);
    }
    
    public function view(Request $request, $id)
    {
        $memory = Memory::with('memorial')->findOrFail($id);
        if (!$memory->memorial || !$this->canViewMemorial($memory->memorial)) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этому мемориалу',
            ], 403);
        }
        
        // Увеличиваем счетчик просмотров
        // Можно добавить проверку, чтобы не считать просмотры от автора
        $memory->increment('views');
        
        return response()->json([
            'success' => true,
            'views' => $memory->views
        ]);
    }

    private function canAccessMemorial(Memorial $memorial): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->canViewMemorial($memorial);
    }

    private function canViewMemorial(Memorial $memorial): bool
    {
        if ((int) $memorial->user_id === (int) auth()->id()) {
            return true;
        }

        if ($memorial->status !== 'published') {
            return false;
        }

        $privacy = in_array($memorial->privacy, ['public', 'family', 'private'], true)
            ? $memorial->privacy
            : 'public';

        if ($privacy === 'public') {
            return true;
        }

        if ($privacy === 'private') {
            return false;
        }

        return Relationship::where('memorial_id', $memorial->id)
            ->where('user_id', auth()->id())
            ->where('confirmed', true)
            ->exists();
    }
}
