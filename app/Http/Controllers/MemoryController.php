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
            return redirect()->route('memorial.show', ['id' => 'id' . $memorialId])
                ->with('success', 'Связь успешно установлена');
        }

        // Создаем воспоминание
        $memory = Memory::create([
            'memorial_id' => $memorial->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        // TODO: Обработка загрузки медиа файлов

        return redirect()->route('memorial.show', ['id' => 'id' . $memorialId])
            ->with('success', 'Воспоминание успешно добавлено');
    }
}
