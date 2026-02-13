<?php

namespace App\Http\Controllers;

use App\Models\Memorial;
use App\Services\StorageService;
use Illuminate\Http\Request;

class MemorialController extends Controller
{
    public function show($id)
    {
        // id1 - демо данные, остальные - из базы
        if ($id === '1') {
            // Временные демо-данные (как в папке old)
            $memorial = (object)[
                'id' => $id,
                'first_name' => 'Иван',
                'last_name' => 'Иванов',
                'middle_name' => 'Иванович',
                'birth_date' => \Carbon\Carbon::parse('1945-03-15'),
                'death_date' => \Carbon\Carbon::parse('2023-01-10'),
                'birth_place' => 'Москва, Россия',
                'photo' => null,
                'biography' => 'Любящий муж, отец и дедушка...',
                'religion' => 'orthodox',
                'necrologue' => 'С глубоким прискорбием сообщаем о кончине выдающегося инженера-конструктора...',
                'burial_place' => 'Новодевичье кладбище',
                'burial_city' => 'Москва',
                'burial_address' => 'Москва, Лужнецкий проезд, 2',
                'burial_location' => 'Участок 2, ряд 15, место 7',
                'burial_latitude' => 55.726389,
                'burial_longitude' => 37.555556,
                'burial_photos' => [],
                'full_biography' => 'Иван Иванович Иванов родился 15 марта 1945 года в Москве...',
                'education' => 'МГТУ им. Н.Э. Баумана',
                'education_details' => '1963-1968, красный диплом',
                'career' => 'Главный конструктор',
                'career_details' => 'КБ машиностроения, 55 лет',
                'hobbies' => "Шахматы (кандидат в мастера спорта)\nКлассическая музыка\nЧтение (библиотека 3000+ книг)",
                'character_traits' => "Исключительная порядочность\nПринципиальность и честность\nДушевная щедрость",
            ];
            
            // Тестовые воспоминания
            $memories = [
                [
                    'id' => 1,
                    'author_name' => 'Мария Иванова',
                    'author_avatar' => 'https://ui-avatars.com/api/?name=Мария+Иванова&size=128&background=e3f2fd&color=1976d2&bold=true',
                    'author_relationship' => 'Ребенок',
                    'content' => 'Папа всегда был для меня примером. Помню, как он каждое воскресенье учил меня играть в шахматы. Он был терпеливым учителем и мудрым наставником. Его советы помогают мне до сих пор.',
                    'photo_url' => null,
                    'created_at' => \Carbon\Carbon::now()->subDays(5)->toDateTimeString(),
                    'likes' => 12,
                    'comments' => [
                        [
                            'id' => 1,
                            'author_name' => 'Елена Смирнова',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Елена+Смирнова&size=128&background=f3e5f5&color=7b1fa2&bold=true',
                            'content' => 'Какие теплые воспоминания! Иван Иванович действительно был замечательным человеком.',
                            'created_at' => \Carbon\Carbon::now()->subDays(4)->toDateTimeString(),
                            'likes' => 3,
                        ],
                        [
                            'id' => 2,
                            'author_name' => 'Дмитрий Козлов',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Дмитрий+Козлов&size=128&background=e8f5e9&color=388e3c&bold=true',
                            'content' => 'Светлая память. Соболезную вашей утрате.',
                            'created_at' => \Carbon\Carbon::now()->subDays(4)->toDateTimeString(),
                            'likes' => 5,
                        ],
                        [
                            'id' => 3,
                            'author_name' => 'Ольга Волкова',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Ольга+Волкова&size=128&background=fff3e0&color=f57c00&bold=true',
                            'content' => 'Мария, держитесь! Ваш папа был прекрасным человеком.',
                            'created_at' => \Carbon\Carbon::now()->subDays(3)->toDateTimeString(),
                            'likes' => 2,
                        ],
                    ],
                    'views' => 45,
                ],
                [
                    'id' => 2,
                    'author_name' => 'Петр Сидоров',
                    'author_avatar' => 'https://ui-avatars.com/api/?name=Петр+Сидоров&size=128&background=fce4ec&color=c2185b&bold=true',
                    'author_relationship' => 'Коллега',
                    'content' => 'Иван Иванович был не просто коллегой, а настоящим профессионалом своего дела. Работая с ним над проектами, я многому научился. Его внимание к деталям и стремление к совершенству вдохновляли всю команду.',
                    'photo_url' => null,
                    'created_at' => \Carbon\Carbon::now()->subDays(3)->toDateTimeString(),
                    'likes' => 8,
                    'comments' => [
                        [
                            'id' => 4,
                            'author_name' => 'Александр Новиков',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Александр+Новиков&size=128&background=e0f2f1&color=00796b&bold=true',
                            'content' => 'Полностью согласен! Работать с Иваном Ивановичем было честью.',
                            'created_at' => \Carbon\Carbon::now()->subDays(2)->toDateTimeString(),
                            'likes' => 4,
                        ],
                        [
                            'id' => 5,
                            'author_name' => 'Наталья Морозова',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Наталья+Морозова&size=128&background=ede7f6&color=5e35b1&bold=true',
                            'content' => 'Таких специалистов сейчас не хватает. Царствие небесное.',
                            'created_at' => \Carbon\Carbon::now()->subDays(2)->toDateTimeString(),
                            'likes' => 6,
                        ],
                    ],
                    'views' => 32,
                ],
                [
                    'id' => 3,
                    'author_name' => 'Анна Петрова',
                    'author_avatar' => 'https://ui-avatars.com/api/?name=Анна+Петрова&size=128&background=ffebee&color=d32f2f&bold=true',
                    'author_relationship' => 'Друг',
                    'content' => 'Светлая память замечательному человеку. Иван был душой компании, всегда готов помочь и поддержать. Его чувство юмора и оптимизм помогали в самые трудные времена.',
                    'photo_url' => null,
                    'created_at' => \Carbon\Carbon::now()->subDays(1)->toDateTimeString(),
                    'likes' => 15,
                    'comments' => [
                        [
                            'id' => 6,
                            'author_name' => 'Игорь Соколов',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Игорь+Соколов&size=128&background=e1f5fe&color=0277bd&bold=true',
                            'content' => 'Анна, спасибо за эти слова. Иван действительно был особенным.',
                            'created_at' => \Carbon\Carbon::now()->subHours(12)->toDateTimeString(),
                            'likes' => 7,
                        ],
                        [
                            'id' => 7,
                            'author_name' => 'Татьяна Лебедева',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Татьяна+Лебедева&size=128&background=f1f8e9&color=689f38&bold=true',
                            'content' => 'Будем помнить его всегда. Вечная память.',
                            'created_at' => \Carbon\Carbon::now()->subHours(8)->toDateTimeString(),
                            'likes' => 9,
                        ],
                        [
                            'id' => 8,
                            'author_name' => 'Сергей Белов',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Сергей+Белов&size=128&background=fbe9e7&color=bf360c&bold=true',
                            'content' => 'Искренние соболезнования семье и близким.',
                            'created_at' => \Carbon\Carbon::now()->subHours(5)->toDateTimeString(),
                            'likes' => 4,
                        ],
                        [
                            'id' => 9,
                            'author_name' => 'Виктория Зайцева',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Виктория+Зайцева&size=128&background=fce4ec&color=ad1457&bold=true',
                            'content' => 'Помню наши встречи... Иван всегда был таким жизнерадостным. Очень жаль.',
                            'created_at' => \Carbon\Carbon::now()->subHours(3)->toDateTimeString(),
                            'likes' => 5,
                        ],
                        [
                            'id' => 10,
                            'author_name' => 'Максим Орлов',
                            'author_avatar' => 'https://ui-avatars.com/api/?name=Максим+Орлов&size=128&background=e8eaf6&color=3949ab&bold=true',
                            'content' => 'Светлая память. Крепитесь, родные.',
                            'created_at' => \Carbon\Carbon::now()->subHours(1)->toDateTimeString(),
                            'likes' => 3,
                        ],
                    ],
                    'views' => 67,
                ],
            ];
            $userRelationship = null;
        } else {
            // Реальные данные из базы
            $memorial = Memorial::with('memories.user')->findOrFail($id);
            
            // Проверка доступа: если черновик - показываем только владельцу
            if ($memorial->status === 'draft') {
                if (!auth()->check() || $memorial->user_id !== auth()->id()) {
                    abort(403, 'Этот мемориал находится в черновиках и недоступен для просмотра');
                }
            }
            
            // Увеличиваем счетчик просмотров (не для владельца)
            if (!auth()->check() || $memorial->user_id !== auth()->id()) {
                $memorial->increment('views');
            }
            
            // Получаем воспоминания с информацией о связи автора
            $userId = auth()->id();
            
            $memories = $memorial->memories->map(function($memory) use ($userId) {
                $relationship = \App\Models\Relationship::where('memorial_id', $memory->memorial_id)
                    ->where('user_id', $memory->user_id)
                    ->first();
                
                // Проверяем, лайкнул ли текущий пользователь это воспоминание
                $userLiked = $userId ? \DB::table('memory_likes')
                    ->where('memory_id', $memory->id)
                    ->where('user_id', $userId)
                    ->exists() : false;
                
                // Загружаем комментарии
                $comments = $memory->comments()->with('user')->orderBy('created_at', 'desc')->get()->map(function($comment) use ($userId) {
                    // Проверяем, лайкнул ли текущий пользователь этот комментарий
                    $commentLiked = $userId ? \DB::table('comment_likes')
                        ->where('comment_id', $comment->id)
                        ->where('user_id', $userId)
                        ->exists() : false;
                    
                    return [
                        'id' => $comment->id,
                        'author_name' => $comment->user->name,
                        'author_avatar' => $comment->user->avatar ? \Storage::disk('s3')->url($comment->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&size=128&background=f3e5f5&color=7b1fa2&bold=true',
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->toDateTimeString(),
                        'likes' => $comment->likes ?? 0,
                        'user_liked' => $commentLiked,
                    ];
                })->toArray();
                
                // Проверяем, есть ли у пользователя комментарий к этому воспоминанию
                $userHasComment = $userId ? $memory->comments()->where('user_id', $userId)->exists() : false;
                
                return [
                    'id' => $memory->id,
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
            $allPhotos = [];
            $allVideos = [];
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
        }

        return view('memorial.show.show', compact('memorial', 'memories', 'userRelationship', 'allPhotos', 'allVideos'));
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
        // Создаем пустой объект мемориала для формы
        $memorial = new Memorial();
        return view('memorial.edit.edit', compact('memorial'));
    }

    public function store(Request $request)
    {
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
            'full_biography' => ['nullable', 'string'],
            'necrologue' => ['nullable', 'string'],
            'education' => ['nullable', 'string', 'max:255'],
            'education_details' => ['nullable', 'string', 'max:255'],
            'career' => ['nullable', 'string', 'max:255'],
            'career_details' => ['nullable', 'string', 'max:255'],
            'hobbies' => ['nullable', 'string'],
            'character_traits' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'military_service' => ['nullable', 'string', 'max:255'],
            'military_rank' => ['nullable', 'string', 'max:255'],
            'military_years' => ['nullable', 'string', 'max:255'],
            'military_details' => ['nullable', 'string'],
            'burial_place' => ['nullable', 'string', 'max:255'],
            'burial_city' => ['nullable', 'string', 'max:255'],
            'burial_address' => ['nullable', 'string', 'max:255'],
            'burial_location' => ['nullable', 'string', 'max:255'],
            'creator_relationship' => ['required', 'string'],
            'creator_relationship_custom' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['user_id'] = auth()->id();
        
        // Определяем статус: draft или published
        $validated['status'] = $request->input('action') === 'publish' ? 'published' : 'draft';

        // Создаем мемориал сначала без фото
        $memorial = Memorial::create($validated);

        // Обработка фото после создания мемориала
        if ($request->hasFile('photo')) {
            $storageService = new StorageService();
            $photoPath = $storageService->uploadMemorialPhoto($memorial->id, $request->file('photo'));
            $memorial->photo = $photoPath;
            $memorial->save();
        }
        
        // Создаем связь создателя с мемориалом
        \App\Models\Relationship::create([
            'memorial_id' => $memorial->id,
            'user_id' => auth()->id(),
            'relationship_type' => $validated['creator_relationship'],
            'custom_relationship' => $validated['creator_relationship_custom'] ?? null,
            'confirmed' => true, // Создатель автоматически подтверждает связь
            'visible' => true,
        ]);
        
        $message = $validated['status'] === 'published' ? 'Мемориал успешно опубликован' : 'Мемориал сохранен как черновик';
        
        return redirect()->route('memorial.show', ['id' => 'id' . $memorial->id])
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
        $memorial = Memorial::findOrFail($id);
        
        // Проверка прав доступа
        if ($memorial->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого мемориала');
        }

        // Логирование входящих данных
        \Log::info('=== НАЧАЛО ОБНОВЛЕНИЯ МЕМОРИАЛА ===');
        \Log::info('Memorial ID: ' . $id);
        \Log::info('Все данные запроса:', $request->all());
        \Log::info('birth_place из запроса: ' . $request->input('birth_place'));
        \Log::info('birth_place_input из запроса: ' . $request->input('birth_place_input'));
        \Log::info('burial_city из запроса: ' . $request->input('burial_city'));
        \Log::info('burial_city_input из запроса: ' . $request->input('burial_city_input'));

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
            'full_biography' => ['nullable', 'string'],
            'necrologue' => ['nullable', 'string'],
            'education' => ['nullable', 'string', 'max:255'],
            'education_details' => ['nullable', 'string', 'max:255'],
            'career' => ['nullable', 'string', 'max:255'],
            'career_details' => ['nullable', 'string', 'max:255'],
            'hobbies' => ['nullable', 'string'],
            'character_traits' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'military_service' => ['nullable', 'string', 'max:255'],
            'military_rank' => ['nullable', 'string', 'max:255'],
            'military_years' => ['nullable', 'string', 'max:255'],
            'military_details' => ['nullable', 'string'],
            'burial_place' => ['nullable', 'string', 'max:255'],
            'burial_city' => ['nullable', 'string', 'max:255'],
            'burial_address' => ['nullable', 'string', 'max:255'],
            'burial_location' => ['nullable', 'string', 'max:255'],
            'burial_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'burial_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'creator_relationship' => ['nullable', 'string'],
            'creator_relationship_custom' => ['nullable', 'string', 'max:255'],
        ]);
        
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
        return redirect()->route('memorial.show', ['id' => $id])
            ->with('success', $message);
    }
}
