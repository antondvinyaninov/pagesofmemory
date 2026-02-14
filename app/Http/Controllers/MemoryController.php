<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\Memorial;
use App\Models\Relationship;
use Illuminate\Http\Request;

class MemoryController extends Controller
{
    public function store(Request $request, $memorialId)
    {
        $memorial = Memorial::findOrFail($memorialId);
        
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

        // TODO: Обработка загрузки медиа файлов

        return redirect()->route('memorial.show', ['id' => $memorialId])
            ->with('success', 'Воспоминание успешно добавлено');
    }
    
    public function like(Request $request, $id)
    {
        $memory = Memory::findOrFail($id);
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
        $memory = Memory::findOrFail($id);
        
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:500'],
        ]);
        
        $comment = \App\Models\Comment::create([
            'memory_id' => $memory->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);
        
        // Загружаем пользователя для ответа
        $comment->load('user');
        
        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
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
        $comment = \App\Models\Comment::findOrFail($id);
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
        $memory = Memory::findOrFail($id);
        
        // Увеличиваем счетчик просмотров
        // Можно добавить проверку, чтобы не считать просмотры от автора
        $memory->increment('views');
        
        return response()->json([
            'success' => true,
            'views' => $memory->views
        ]);
    }
}
