<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Memorial;
use App\Services\EmailNotificationService;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MemorialController extends Controller
{
    public function show(Request $request, $id)
    {
        $allPhotos = [];
        $allVideos = [];
        $memorySort = $this->resolveMemorySort($request);

        $memorial = Memorial::with('memories.user')->find($id);

        if (!$memorial) {
            abort(404);
        }

        if (!$this->canViewMemorial($memorial)) {
            abort(403, $this->getMemorialAccessDeniedMessage($memorial));
        }

        $isOwner = auth()->check() && $memorial->user_id === auth()->id();

        // Увеличиваем счетчик просмотров мемориала (не для владельца)
        if (!$isOwner) {
            $memorial->increment('views');
        }

        $memoriesCollection = $this->applyMemorySort($memorial->memories, $memorySort);

        // Получаем воспоминания с информацией о связи автора
        $userId = auth()->id();
        
        // Предзагружаем все связи для авторов воспоминаний
        $memoryUserIds = $memoriesCollection->pluck('user_id')->unique();
        $relationships = \App\Models\Relationship::where('memorial_id', $memorial->id)
            ->whereIn('user_id', $memoryUserIds)
            ->get()
            ->keyBy('user_id');
        
        // Предзагружаем лайки пользователя
        $memoryIds = $memoriesCollection->pluck('id');
        $userMemoryLikes = $userId ? \DB::table('memory_likes')
            ->where('user_id', $userId)
            ->whereIn('memory_id', $memoryIds)
            ->pluck('memory_id')
            ->flip()
            ->toArray() : [];
        
        // Предзагружаем комментарии с пользователями
        $allComments = \App\Models\Comment::with('user')
            ->whereIn('memory_id', $memoryIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('memory_id');
        
        // Предзагружаем лайки комментариев пользователя
        $commentIds = $allComments->flatten()->pluck('id');
        $userCommentLikes = $userId && $commentIds->isNotEmpty() ? \DB::table('comment_likes')
            ->where('user_id', $userId)
            ->whereIn('comment_id', $commentIds)
            ->pluck('comment_id')
            ->flip()
            ->toArray() : [];
        
        // Предзагружаем информацию о комментариях пользователя
        $userCommentMemories = $userId && $commentIds->isNotEmpty() ? \App\Models\Comment::where('user_id', $userId)
            ->whereIn('memory_id', $memoryIds)
            ->pluck('memory_id')
            ->flip()
            ->toArray() : [];
        
        $memories = $memoriesCollection->map(function($memory) use ($userId, $relationships, $userMemoryLikes, $allComments, $userCommentLikes, $userCommentMemories) {
            $relationship = $relationships->get($memory->user_id);
            
            // Проверяем, лайкнул ли текущий пользователь это воспоминание
            $userLiked = isset($userMemoryLikes[$memory->id]);
            
            // Загружаем комментарии
            $memoryComments = $allComments->get($memory->id, collect());
            $comments = $memoryComments->map(function($comment) use ($userCommentLikes) {
                $commentLiked = isset($userCommentLikes[$comment->id]);
                
                return [
                    'id' => $comment->id,
                    'author_id' => $comment->user->id,
                    'author_name' => $comment->user->name,
                    'author_avatar' => $comment->user->avatar ? \Storage::disk('s3')->url($comment->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&size=128&background=f3e5f5&color=7b1fa2&bold=true',
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->toDateTimeString(),
                    'likes' => $comment->likes ?? 0,
                    'user_liked' => $commentLiked,
                ];
            })->toArray();
            
            // Проверяем, есть ли у пользователя комментарий к этому воспоминанию
            $userHasComment = isset($userCommentMemories[$memory->id]);
            
            return [
                'id' => $memory->id,
                'author_id' => $memory->user->id,
                'author_name' => $memory->user->name,
                'author_avatar' => $memory->user->avatar ? \Storage::disk('s3')->url($memory->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($memory->user->name) . '&size=128&background=e3f2fd&color=1976d2&bold=true',
                'author_relationship' => $relationship ? $this->getRelationshipLabel($relationship) : null,
                'content' => $memory->content,
                'photos' => $memory->media && is_array($memory->media) 
                    ? array_filter(array_map(function($item) {
                        if (is_array($item) && isset($item['type']) && $item['type'] === 'image' && isset($item['url'])) {
                            return $item['url'];
                        }
                        return null;
                    }, $memory->media))
                    : [],
                'videos' => $memory->media && is_array($memory->media) 
                    ? array_filter(array_map(function($item) {
                        if (is_array($item) && isset($item['type']) && $item['type'] === 'video' && isset($item['url'])) {
                            return $item['url'];
                        }
                        return null;
                    }, $memory->media))
                    : [],
                'created_at' => $memory->created_at->toDateTimeString(),
                'likes' => $memory->likes,
                'user_liked' => $userLiked,
                'user_has_comment' => $userHasComment,
                'comments' => $comments,
                'views' => $memory->views,
            ];
        })->toArray();
        
        // Проверяем связь текущего пользователя с мемориалом
        $userRelationship = auth()->check() 
            ? \App\Models\Relationship::where('memorial_id', $memorial->id)
                ->where('user_id', auth()->id())
                ->first()
            : null;
        
        // Собираем все медиа из воспоминаний
        foreach ($memories as $memory) {
            if (isset($memory['photos']) && is_array($memory['photos'])) {
                foreach ($memory['photos'] as $photo) {
                    $allPhotos[] = [
                        'url' => $photo,
                        'memory_id' => $memory['id'],
                        'author' => $memory['author_name'],
                    ];
                }
            }
            if (isset($memory['videos']) && is_array($memory['videos'])) {
                foreach ($memory['videos'] as $video) {
                    $allVideos[] = [
                        'url' => $video,
                        'memory_id' => $memory['id'],
                        'author' => $memory['author_name'],
                    ];
                }
            }
        }

        // Добавляем медиа самого мемориала из вкладки "Медиа"
        foreach (($memorial->media_photos ?? []) as $photoPath) {
            if (!is_string($photoPath) || trim($photoPath) === '') {
                continue;
            }

            $allPhotos[] = [
                'url' => $this->toPublicMediaUrl($photoPath),
                'memory_id' => null,
                'author' => 'Галерея мемориала',
            ];
        }

        foreach (($memorial->media_videos ?? []) as $videoPath) {
            if (!is_string($videoPath) || trim($videoPath) === '') {
                continue;
            }

            $allVideos[] = [
                'url' => $this->toPublicMediaUrl($videoPath),
                'memory_id' => null,
                'author' => 'Галерея мемориала',
            ];
        }

        $memorialGalleryPhotos = collect($allPhotos)
            ->filter(fn ($item) => is_array($item) && is_null($item['memory_id'] ?? null))
            ->values()
            ->all();

        $memorialGalleryVideos = collect($allVideos)
            ->filter(fn ($item) => is_array($item) && is_null($item['memory_id'] ?? null))
            ->values()
            ->all();

        return view('memorial.show.show', compact(
            'memorial',
            'memories',
            'userRelationship',
            'allPhotos',
            'allVideos',
            'memorialGalleryPhotos',
            'memorialGalleryVideos',
            'memorySort'
        ));
    }
    
    private function getRelationshipLabel($relationship)
    {
        // Если пользователь не хочет указывать связь - не показываем
        if ($relationship->relationship_type === 'not_specified' || !$relationship->visible) {
            return null;
        }
        
        if ($relationship->relationship_type === 'other') {
            return $relationship->custom_relationship;
        }
        
        $labels = [
            'husband' => 'Муж',
            'wife' => 'Жена',
            'father' => 'Отец',
            'mother' => 'Мать',
            'son' => 'Сын',
            'daughter' => 'Дочь',
            'brother' => 'Брат',
            'sister' => 'Сестра',
            'grandfather' => 'Дедушка',
            'grandmother' => 'Бабушка',
            'grandson' => 'Внук',
            'granddaughter' => 'Внучка',
            'uncle' => 'Дядя',
            'aunt' => 'Тетя',
            'nephew' => 'Племянник',
            'niece' => 'Племянница',
            'relative' => 'Родственник',
            'friend_male' => 'Друг',
            'friend_female' => 'Подруга',
            'colleague' => 'Коллега',
            'neighbor' => 'Сосед',
            'classmate' => 'Одноклассник',
            'coursemate' => 'Однокурсник',
        ];
        
        return $labels[$relationship->relationship_type] ?? $relationship->relationship_type;
    }

    public function create()
    {
        if (!AppSetting::get('access.enable_memorial_creation', true)) {
            abort(403, 'Создание мемориалов временно отключено');
        }

        // Создаем пустой объект мемориала для формы
        $memorial = new Memorial();
        return view('memorial.edit.edit', compact('memorial'));
    }

    public function store(Request $request)
    {
        if (!AppSetting::get('access.enable_memorial_creation', true)) {
            abort(403, 'Создание мемориалов временно отключено');
        }

        $validated = $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'death_date' => ['required', 'date', 'after:birth_date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:10240'],
            'biography' => ['nullable', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'in:none,orthodox,catholic,islam,judaism,buddhism,hinduism,other'],
            'privacy' => ['required', 'string', 'in:public,family,private'],
            'moderate_memories' => ['nullable', 'boolean'],
            'allow_comments' => ['nullable', 'boolean'],
            'confirm_responsibility' => ['required', 'accepted'],
            'full_biography' => ['nullable', 'string'],
            'education' => ['nullable', 'array', 'max:5'],
            'education.*.name' => ['nullable', 'string', 'max:255'],
            'education.*.details' => ['nullable', 'string', 'max:255'],
            'education_details' => ['nullable', 'string', 'max:255'],
            'career' => ['nullable', 'array', 'max:5'],
            'career.*.position' => ['nullable', 'string', 'max:255'],
            'career.*.details' => ['nullable', 'string', 'max:255'],
            'career_details' => ['nullable', 'string', 'max:255'],
            'hobbies' => ['nullable', 'string'],
            'character_traits' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'military_service' => ['nullable', 'string', 'max:255'],
            'military_rank' => ['nullable', 'string', 'max:255'],
            'military_years' => ['nullable', 'string', 'max:255'],
            'military_conflicts' => ['nullable', 'array'],
            'military_conflicts.*' => ['nullable', 'string', 'in:ww2,afghanistan,chechnya_1,chechnya_2,georgia,syria,ukraine'],
            'military_conflicts_custom' => ['nullable', 'array'],
            'military_conflicts_custom.*' => ['nullable', 'string', 'max:255'],
            'military_details' => ['nullable', 'string'],
            'military_files' => ['nullable', 'array', 'max:10'],
            'military_files.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif,pdf', 'max:10240'],
            'military_files.*.title' => ['nullable', 'string', 'max:255'],
            'existing_military_files' => ['nullable', 'array', 'max:10'],
            'existing_military_files.*.path' => ['nullable', 'string', 'max:2048'],
            'existing_military_files.*.title' => ['nullable', 'string', 'max:255'],
            'achievement_files' => ['nullable', 'array', 'max:10'],
            'achievement_files.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif,pdf', 'max:10240'],
            'achievement_files.*.title' => ['nullable', 'string', 'max:255'],
            'existing_achievement_files' => ['nullable', 'array', 'max:10'],
            'existing_achievement_files.*.path' => ['nullable', 'string', 'max:2048'],
            'existing_achievement_files.*.title' => ['nullable', 'string', 'max:255'],
            'media_photos' => ['nullable', 'array', 'max:5'],
            'media_photos.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif', 'max:10240'],
            'media_videos' => ['nullable', 'array', 'max:2'],
            'media_videos.*' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm,mkv', 'max:512000'],
            'existing_media_photos' => ['nullable', 'array', 'max:5'],
            'existing_media_photos.*' => ['nullable', 'string', 'max:2048'],
            'existing_media_videos' => ['nullable', 'array', 'max:2'],
            'existing_media_videos.*' => ['nullable', 'string', 'max:2048'],
            'burial_place' => ['nullable', 'string', 'max:255'],
            'burial_city' => ['nullable', 'string', 'max:255'],
            'burial_address' => ['nullable', 'string', 'max:255'],
            'burial_location' => ['nullable', 'string', 'max:255'],
            'creator_relationship' => ['required', 'string'],
            'creator_relationship_custom' => ['nullable', 'string', 'max:255'],
        ]);

        $validated = $this->normalizeEducationAndCareer($validated);
        $validated = $this->normalizeMilitaryConflicts($validated);
        $validated['moderate_memories'] = $request->boolean('moderate_memories');
        $validated['allow_comments'] = $request->boolean('allow_comments');
        unset(
            $validated['media_photos'],
            $validated['media_videos'],
            $validated['existing_media_photos'],
            $validated['existing_media_videos'],
            $validated['military_files'],
            $validated['existing_military_files'],
            $validated['achievement_files'],
            $validated['existing_achievement_files']
        );

        $validated['user_id'] = auth()->id();
        
        // Определяем статус: draft или published
        $autoPublish = AppSetting::get('moderation.auto_publish_memorials', false);
        $validated['status'] = ($request->input('action') === 'publish' || $autoPublish) ? 'published' : 'draft';

        // Создаем мемориал сначала без фото
        $memorial = Memorial::create($validated);

        // Обработка фото после создания мемориала
        if ($request->hasFile('photo')) {
            $storageService = new StorageService();
            $photoPath = $storageService->uploadMemorialPhoto($memorial->id, $request->file('photo'));
            $memorial->photo = $photoPath;
            $memorial->save();
        }

        $this->syncMemorialMedia($request, $memorial);
        $this->syncMemorialFiles($request, $memorial);
        
        // Создаем связь создателя с мемориалом
        \App\Models\Relationship::create([
            'memorial_id' => $memorial->id,
            'user_id' => auth()->id(),
            'relationship_type' => $validated['creator_relationship'],
            'custom_relationship' => $validated['creator_relationship_custom'] ?? null,
            'confirmed' => true, // Создатель автоматически подтверждает связь
            'visible' => true,
        ]);

        $emailService = app(EmailNotificationService::class);
        $emailService->sendMemorialCreatedEmail(auth()->user(), $memorial);
        if ($validated['status'] === 'published') {
            $emailService->sendMemorialPublishedEmail(auth()->user(), $memorial);
        }
        
        $message = $validated['status'] === 'published' ? 'Мемориал успешно опубликован' : 'Мемориал сохранен как черновик';
        
        return redirect()->route('memorial.show', ['id' => $memorial->id])
            ->with('success', $message);
    }

    public function edit($id)
    {
        $memorialId = str_replace('id', '', $id);
        $memorial = Memorial::findOrFail($memorialId);
        
        // Проверка прав доступа
        if ($memorial->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого мемориала');
        }
        
        return view('memorial.edit.edit', compact('memorial'));
    }

    public function update(Request $request, $id)
    {
        // Увеличиваем лимит памяти для обработки больших файлов
        ini_set('memory_limit', '256M');
        
        $memorial = Memorial::findOrFail($id);
        $previousStatus = $memorial->status;
        
        // Проверка прав доступа
        if ($memorial->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого мемориала');
        }

        // Логирование входящих данных
        \Log::info('=== НАЧАЛО ОБНОВЛЕНИЯ МЕМОРИАЛА ===');
        \Log::info('Memorial ID: ' . $id);
        \Log::info('birth_place из запроса: ' . $request->input('birth_place'));
        \Log::info('burial_city из запроса: ' . $request->input('burial_city'));
        \Log::info('burial_latitude из запроса: ' . $request->input('burial_latitude'));
        \Log::info('burial_longitude из запроса: ' . $request->input('burial_longitude'));

        $validated = $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'death_date' => ['required', 'date', 'after:birth_date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:10240'],
            'biography' => ['nullable', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'in:none,orthodox,catholic,islam,judaism,buddhism,hinduism,other'],
            'privacy' => ['required', 'string', 'in:public,family,private'],
            'moderate_memories' => ['nullable', 'boolean'],
            'allow_comments' => ['nullable', 'boolean'],
            'full_biography' => ['nullable', 'string'],
            'education' => ['nullable', 'array', 'max:5'],
            'education.*.name' => ['nullable', 'string', 'max:255'],
            'education.*.details' => ['nullable', 'string', 'max:255'],
            'education_details' => ['nullable', 'string', 'max:255'],
            'career' => ['nullable', 'array', 'max:5'],
            'career.*.position' => ['nullable', 'string', 'max:255'],
            'career.*.details' => ['nullable', 'string', 'max:255'],
            'career_details' => ['nullable', 'string', 'max:255'],
            'hobbies' => ['nullable', 'string'],
            'character_traits' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'military_service' => ['nullable', 'string', 'max:255'],
            'military_rank' => ['nullable', 'string', 'max:255'],
            'military_years' => ['nullable', 'string', 'max:255'],
            'military_conflicts' => ['nullable', 'array'],
            'military_conflicts.*' => ['nullable', 'string', 'in:ww2,afghanistan,chechnya_1,chechnya_2,georgia,syria,ukraine'],
            'military_conflicts_custom' => ['nullable', 'array'],
            'military_conflicts_custom.*' => ['nullable', 'string', 'max:255'],
            'military_details' => ['nullable', 'string'],
            'military_files' => ['nullable', 'array', 'max:10'],
            'military_files.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif,pdf', 'max:10240'],
            'military_files.*.title' => ['nullable', 'string', 'max:255'],
            'existing_military_files' => ['nullable', 'array', 'max:10'],
            'existing_military_files.*.path' => ['nullable', 'string', 'max:2048'],
            'existing_military_files.*.title' => ['nullable', 'string', 'max:255'],
            'achievement_files' => ['nullable', 'array', 'max:10'],
            'achievement_files.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif,pdf', 'max:10240'],
            'achievement_files.*.title' => ['nullable', 'string', 'max:255'],
            'existing_achievement_files' => ['nullable', 'array', 'max:10'],
            'existing_achievement_files.*.path' => ['nullable', 'string', 'max:2048'],
            'existing_achievement_files.*.title' => ['nullable', 'string', 'max:255'],
            'media_photos' => ['nullable', 'array', 'max:5'],
            'media_photos.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif', 'max:10240'],
            'media_videos' => ['nullable', 'array', 'max:2'],
            'media_videos.*' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm,mkv', 'max:512000'],
            'existing_media_photos' => ['nullable', 'array', 'max:5'],
            'existing_media_photos.*' => ['nullable', 'string', 'max:2048'],
            'existing_media_videos' => ['nullable', 'array', 'max:2'],
            'existing_media_videos.*' => ['nullable', 'string', 'max:2048'],
            'burial_place' => ['nullable', 'string', 'max:255'],
            'burial_city' => ['nullable', 'string', 'max:255'],
            'burial_address' => ['nullable', 'string', 'max:255'],
            'burial_location' => ['nullable', 'string', 'max:255'],
            'burial_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'burial_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'creator_relationship' => ['nullable', 'string'],
            'creator_relationship_custom' => ['nullable', 'string', 'max:255'],
        ]);
        
        $validated = $this->normalizeEducationAndCareer($validated);
        $validated = $this->normalizeMilitaryConflicts($validated);
        $validated['moderate_memories'] = $request->boolean('moderate_memories');
        $validated['allow_comments'] = $request->boolean('allow_comments');
        unset(
            $validated['media_photos'],
            $validated['media_videos'],
            $validated['existing_media_photos'],
            $validated['existing_media_videos'],
            $validated['military_files'],
            $validated['existing_military_files'],
            $validated['achievement_files'],
            $validated['existing_achievement_files']
        );

        \Log::info('Валидированные данные:', $validated);
        \Log::info('birth_place после валидации: ' . ($validated['birth_place'] ?? 'NULL'));
        \Log::info('burial_city после валидации: ' . ($validated['burial_city'] ?? 'NULL'));
        
        // Определяем статус
        $validated['status'] = $request->input('action') === 'publish' ? 'published' : 'draft';

        // Обновляем данные
        \Log::info('Данные ДО обновления:', $memorial->toArray());
        $memorial->update($validated);
        \Log::info('Данные ПОСЛЕ обновления:', $memorial->fresh()->toArray());

        // Обработка нового фото
        if ($request->hasFile('photo')) {
            $storageService = new StorageService();
            $photoPath = $storageService->uploadMemorialPhoto($memorial->id, $request->file('photo'));
            $memorial->photo = $photoPath;
            $memorial->save();
        }
        
        // Обработка фото места захоронения
        $burialPhotos = [];
        
        // Сохраняем существующие фото
        if ($request->has('existing_burial_photos')) {
            $burialPhotos = array_values(array_filter($request->input('existing_burial_photos')));
        }
        
        // Добавляем новые фото
        if ($request->hasFile('burial_photos')) {
            $storageService = new StorageService();
            
            foreach ($request->file('burial_photos') as $photo) {
                if ($photo) {
                    $photoPath = $storageService->uploadMemorialPhoto($memorial->id, $photo, 'burial');
                    $burialPhotos[] = $photoPath;
                }
            }
        }
        
        // Сохраняем все фото
        if (!empty($burialPhotos)) {
            $memorial->burial_photos = $burialPhotos;
            $memorial->save();
        }

        $this->syncMemorialMedia($request, $memorial);
        $this->syncMemorialFiles($request, $memorial);
        
        // Обновляем или создаем связь пользователя с мемориалом (только если указана)
        if (!empty($validated['creator_relationship'])) {
            \App\Models\Relationship::updateOrCreate(
                [
                    'memorial_id' => $memorial->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'relationship_type' => $validated['creator_relationship'],
                    'custom_relationship' => $validated['creator_relationship_custom'] ?? null,
                    'confirmed' => true,
                    'visible' => true,
                ]
            );
        }
        
        \Log::info('=== КОНЕЦ ОБНОВЛЕНИЯ МЕМОРИАЛА ===');
        
        $message = $validated['status'] === 'published' ? 'Мемориал успешно обновлен и опубликован' : 'Изменения сохранены как черновик';
        
        // Если черновик - остаемся на странице редактирования
        if ($validated['status'] === 'draft') {
            return redirect()->back()->with('success', $message);
        }
        
        // Если опубликован - переходим на страницу мемориала
        if ($previousStatus !== 'published' && $validated['status'] === 'published') {
            app(EmailNotificationService::class)->sendMemorialPublishedEmail(auth()->user(), $memorial);
        }

        return redirect()->route('memorial.show', ['id' => $id])
            ->with('success', $message);
    }

    private function syncMemorialMedia(Request $request, Memorial $memorial): void
    {
        $photos = $this->normalizeMediaPaths($request->input('existing_media_photos', []));
        $videos = $this->normalizeMediaPaths($request->input('existing_media_videos', []));

        $storageService = new StorageService();

        foreach ((array) $request->file('media_photos', []) as $photoFile) {
            if ($photoFile) {
                $photos[] = $storageService->uploadMemorialGalleryPhoto($memorial->id, $photoFile);
            }
        }

        foreach ((array) $request->file('media_videos', []) as $videoFile) {
            if ($videoFile) {
                $videos[] = $storageService->uploadMemorialVideo($memorial->id, $videoFile);
            }
        }

        $photos = array_values(array_unique($photos));
        $videos = array_values(array_unique($videos));

        if (count($photos) > 5) {
            throw ValidationException::withMessages([
                'media_photos' => 'Можно добавить не более 5 фотографий.',
            ]);
        }

        if (count($videos) > 2) {
            throw ValidationException::withMessages([
                'media_videos' => 'Можно добавить не более 2 видео.',
            ]);
        }

        $memorial->media_photos = $photos;
        $memorial->media_videos = $videos;
        $memorial->save();
    }

    private function syncMemorialFiles(Request $request, Memorial $memorial): void
    {
        $militaryFiles = $this->syncMemorialFileCollection(
            $request,
            $memorial->id,
            'military_files',
            'existing_military_files',
            fn (StorageService $storageService, int $memorialId, $file) => $storageService->uploadMemorialMilitaryFile($memorialId, $file),
            10
        );

        $achievementFiles = $this->syncMemorialFileCollection(
            $request,
            $memorial->id,
            'achievement_files',
            'existing_achievement_files',
            fn (StorageService $storageService, int $memorialId, $file) => $storageService->uploadMemorialAchievementFile($memorialId, $file),
            10
        );

        $memorial->military_files = empty($militaryFiles) ? null : $militaryFiles;
        $memorial->achievement_files = empty($achievementFiles) ? null : $achievementFiles;
        $memorial->save();
    }

    private function syncMemorialFileCollection(
        Request $request,
        int $memorialId,
        string $newField,
        string $existingField,
        callable $uploadCallback,
        int $maxItems
    ): array {
        $items = $this->normalizeDocumentItems($request->input($existingField, []));
        $storageService = new StorageService();

        foreach ((array) $request->file($newField, []) as $index => $filePayload) {
            if (!is_array($filePayload)) {
                continue;
            }

            $uploadedFile = $filePayload['file'] ?? null;
            if (!$uploadedFile) {
                continue;
            }

            $title = $this->trimToNullableString($request->input("{$newField}.{$index}.title"), 255);
            $path = $uploadCallback($storageService, $memorialId, $uploadedFile);

            $items[] = [
                'path' => $path,
                'title' => $title,
            ];
        }

        $normalized = collect($items)
            ->filter(fn ($item) => is_array($item) && is_string($item['path'] ?? null) && trim((string) $item['path']) !== '')
            ->map(function (array $item) {
                return [
                    'path' => trim((string) $item['path']),
                    'title' => $this->trimToNullableString($item['title'] ?? null, 255),
                ];
            })
            ->unique('path')
            ->values()
            ->all();

        if (count($normalized) > $maxItems) {
            throw ValidationException::withMessages([
                $newField => "Можно добавить не более {$maxItems} файлов.",
            ]);
        }

        return $normalized;
    }

    private function normalizeDocumentItems(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->filter(fn ($item) => is_array($item))
            ->map(function (array $item) {
                return [
                    'path' => $item['path'] ?? null,
                    'title' => $item['title'] ?? null,
                ];
            })
            ->all();
    }

    private function normalizeMediaPaths(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->filter(fn ($path) => is_string($path))
            ->map(fn (string $path) => trim($path))
            ->filter()
            ->values()
            ->all();
    }

    private function toPublicMediaUrl(string $path): string
    {
        $trimmedPath = trim($path);
        if ($trimmedPath === '') {
            return '';
        }

        if (str_starts_with($trimmedPath, 'http://') || str_starts_with($trimmedPath, 'https://')) {
            return $trimmedPath;
        }

        return \Storage::disk('s3')->url(ltrim($trimmedPath, '/'));
    }

    private function normalizeEducationAndCareer(array $validated): array
    {
        if (array_key_exists('education', $validated) && is_array($validated['education'])) {
            [$education, $educationDetails] = $this->collapseStructuredItems($validated['education'], 'name', 'details');
            $validated['education'] = $education;
            $validated['education_details'] = $educationDetails;
        } else {
            $validated['education'] = $this->trimToNullableString($validated['education'] ?? null, 255);
            $validated['education_details'] = $this->trimToNullableString($validated['education_details'] ?? null, 255);
        }

        if (array_key_exists('career', $validated) && is_array($validated['career'])) {
            [$career, $careerDetails] = $this->collapseStructuredItems($validated['career'], 'position', 'details');
            $validated['career'] = $career;
            $validated['career_details'] = $careerDetails;
        } else {
            $validated['career'] = $this->trimToNullableString($validated['career'] ?? null, 255);
            $validated['career_details'] = $this->trimToNullableString($validated['career_details'] ?? null, 255);
        }

        return $validated;
    }

    private function collapseStructuredItems(array $items, string $mainKey, string $detailsKey): array
    {
        $rows = collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(function (array $item) use ($mainKey, $detailsKey) {
                return [
                    'main' => trim((string) ($item[$mainKey] ?? '')),
                    'details' => trim((string) ($item[$detailsKey] ?? '')),
                ];
            })
            ->filter(fn (array $row) => $row['main'] !== '' || $row['details'] !== '')
            ->values();

        $mainText = $rows->pluck('main')->filter()->implode('; ');

        $detailsText = $rows
            ->map(function (array $row) {
                if ($row['main'] !== '' && $row['details'] !== '') {
                    return $row['main'] . ': ' . $row['details'];
                }

                return $row['details'];
            })
            ->filter()
            ->implode('; ');

        return [
            $this->trimToNullableString($mainText, 255),
            $this->trimToNullableString($detailsText, 255),
        ];
    }

    private function trimToNullableString(mixed $value, int $maxLength): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        return mb_substr($trimmed, 0, $maxLength);
    }

    private function normalizeMilitaryConflicts(array $validated): array
    {
        $predefined = collect($validated['military_conflicts'] ?? [])
            ->filter(fn ($value) => is_string($value))
            ->map(fn (string $value) => trim($value))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $custom = collect($validated['military_conflicts_custom'] ?? [])
            ->filter(fn ($value) => is_string($value))
            ->map(fn (string $value) => trim($value))
            ->filter()
            ->map(fn (string $value) => mb_substr($value, 0, 255))
            ->unique()
            ->values()
            ->all();

        $conflicts = array_values(array_unique(array_merge($predefined, $custom)));
        $validated['military_conflicts'] = empty($conflicts) ? null : $conflicts;

        unset($validated['military_conflicts_custom']);

        return $validated;
    }

    private function resolveMemorySort(Request $request): string
    {
        $sort = trim((string) $request->query('memory_sort', 'new'));
        return in_array($sort, ['new', 'popular', 'media'], true) ? $sort : 'new';
    }

    private function applyMemorySort(Collection $memories, string $sort): Collection
    {
        return match ($sort) {
            'popular' => $memories
                ->sortByDesc(function ($memory) {
                    $likes = (int) ($memory->likes ?? 0);
                    $timestamp = optional($memory->created_at)->timestamp ?? 0;
                    return ($likes * 10000000000) + $timestamp;
                })
                ->values(),
            'media' => $memories
                ->filter(fn ($memory) => $this->memoryHasMedia($memory->media ?? null))
                ->values(),
            default => $memories
                ->sortByDesc(fn ($memory) => optional($memory->created_at)->timestamp ?? 0)
                ->values(),
        };
    }

    private function applyMemorySortToArray(array $memories, string $sort): array
    {
        return match ($sort) {
            'popular' => collect($memories)
                ->sortByDesc(function (array $memory) {
                    $likes = (int) ($memory['likes'] ?? 0);
                    $timestamp = strtotime((string) ($memory['created_at'] ?? '')) ?: 0;
                    return ($likes * 10000000000) + $timestamp;
                })
                ->values()
                ->all(),
            'media' => collect($memories)
                ->filter(fn (array $memory) => $this->memoryHasMedia($memory['media'] ?? null))
                ->values()
                ->all(),
            default => collect($memories)
                ->sortByDesc(fn (array $memory) => strtotime((string) ($memory['created_at'] ?? '')) ?: 0)
                ->values()
                ->all(),
        };
    }

    private function memoryHasMedia(mixed $media): bool
    {
        if (!is_array($media) || empty($media)) {
            return false;
        }

        foreach ($media as $item) {
            if (is_array($item) && isset($item['url']) && is_string($item['url']) && trim($item['url']) !== '') {
                return true;
            }

            if (is_string($item) && trim($item) !== '') {
                return true;
            }
        }

        return false;
    }

    private function canViewMemorial(Memorial $memorial): bool
    {
        $isOwner = auth()->check() && $memorial->user_id === auth()->id();
        if ($isOwner) {
            return true;
        }

        if ($memorial->status === 'draft') {
            return false;
        }

        $privacy = in_array($memorial->privacy, ['public', 'family', 'private'], true)
            ? $memorial->privacy
            : 'public';

        if ($privacy === 'public') {
            return true;
        }

        if (!auth()->check()) {
            return false;
        }

        if ($privacy === 'private') {
            return false;
        }

        if ($privacy === 'family') {
            return \App\Models\Relationship::where('memorial_id', $memorial->id)
                ->where('user_id', auth()->id())
                ->where('confirmed', true)
                ->exists();
        }

        return true;
    }

    private function getMemorialAccessDeniedMessage(Memorial $memorial): string
    {
        if ($memorial->status === 'draft') {
            return 'Этот мемориал находится в черновиках и недоступен для просмотра';
        }

        return match ($memorial->privacy) {
            'private' => 'Этот мемориал доступен только владельцу',
            'family' => 'Этот мемориал доступен только семье и приглашенным близким',
            default => 'У вас нет доступа к этому мемориалу',
        };
    }
}
