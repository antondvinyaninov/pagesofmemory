<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\User;
use App\Models\Memorial;
use App\Models\Memory;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }
        
        // Кешируем статистику на 5 минут
        $stats = cache()->remember('admin_stats', 300, function () {
            return [
                'users' => User::count(),
                'memorials' => Memorial::count(),
                'memories' => Memory::count(),
                'recent_users' => User::latest()->take(5)->get(),
                'recent_memorials' => Memorial::with('user')->latest()->take(5)->get(),
            ];
        });
        
        return view('admin.index', compact('stats'));
    }
    
    public function users()
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }
        
        $page = request()->get('page', 1);
        
        // Кешируем список пользователей на 2 минуты
        $users = cache()->remember("admin_users_page_{$page}", 120, function () {
            return User::withCount('memorials')->latest()->paginate(20);
        });
        
        return view('admin.users', compact('users'));
    }
    
    public function memorials()
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }
        
        $page = request()->get('page', 1);
        
        // Кешируем список мемориалов на 2 минуты
        $memorials = cache()->remember("admin_memorials_page_{$page}", 120, function () {
            return Memorial::with('user')->latest()->paginate(20);
        });
        
        return view('admin.memorials', compact('memorials'));
    }
    
    public function deleteUser($id)
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }
        
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Нельзя удалить администратора');
        }
        
        $user->delete();
        
        // Очищаем кеш
        cache()->forget('admin_stats');
        cache()->flush();
        
        return back()->with('success', 'Пользователь удален');
    }
    
    public function convertUserToMemorial($id)
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }
        
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Нельзя перевести администратора в статус памяти');
        }
        
        if ($user->is_memorial) {
            return back()->with('error', 'Пользователь уже в статусе памяти');
        }
        
        // Создаем мемориал для пользователя
        $memorial = Memorial::create([
            'user_id' => $user->id,
            'first_name' => explode(' ', $user->name)[0] ?? '',
            'last_name' => explode(' ', $user->name)[1] ?? '',
            'middle_name' => explode(' ', $user->name)[2] ?? null,
            'birth_date' => now()->subYears(50), // Примерная дата, нужно будет уточнить
            'death_date' => now(),
            'photo' => $user->avatar,
            'status' => 'published',
            'privacy' => 'public',
        ]);
        
        // Обновляем пользователя
        $user->update([
            'is_memorial' => true,
            'memorial_id' => $memorial->id,
        ]);
        
        // Очищаем кеш
        cache()->forget('admin_stats');
        cache()->flush();
        
        return back()->with('success', 'Пользователь переведен в статус памяти. Мемориал создан.');
    }
    
    public function deleteMemorial($id)
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }
        
        $memorial = Memorial::findOrFail($id);
        $memorial->delete();
        
        // Очищаем кеш
        cache()->forget('admin_stats');
        cache()->flush();
        
        return back()->with('success', 'Мемориал удален');
    }
    
    public function analytics()
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }
        
        return view('admin.analytics');
    }
    
    public function seo()
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $appUrl = rtrim(config('app.url'), '/');
        $robotsPath = public_path('robots.txt');
        $sitemapPath = public_path('sitemap.xml');

        $robotsExists = file_exists($robotsPath);
        $robotsContent = $robotsExists ? file_get_contents($robotsPath) : '';
        $robotsLower = strtolower($robotsContent);

        $hasSitemapRoute = \Route::has('sitemap.xml');
        $sitemapExists = file_exists($sitemapPath) || $hasSitemapRoute;
        $hasSitemapDirective = str_contains($robotsLower, 'sitemap:');
        $isRobotsOpen = $robotsExists && !str_contains($robotsLower, 'disallow: /');
        $usesHttps = str_starts_with($appUrl, 'https://');

        $publishedMemorials = Memorial::where('status', 'published')->count();
        $draftMemorials = Memorial::where('status', 'draft')->count();
        $memorialsWithoutPhoto = Memorial::where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('photo')->orWhere('photo', '');
            })
            ->count();
        $memorialsWithoutBiography = Memorial::where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('biography')->orWhere('biography', '');
            })
            ->count();
        $updatedLastWeek = Memorial::where('status', 'published')
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();

        $sampleMemorialId = Memorial::where('status', 'published')->value('id') ?? 1;
        $sampleMemorialUrl = route('memorial.show', ['id' => $sampleMemorialId]);
        $sitemapUrl = $hasSitemapRoute ? route('sitemap.xml') : $appUrl . '/sitemap.xml';

        $checks = [
            [
                'name' => 'robots.txt',
                'ok' => $robotsExists,
                'details' => $robotsExists ? 'Файл доступен' : 'Файл отсутствует в public/',
            ],
            [
                'name' => 'Открыт для индексации',
                'ok' => $isRobotsOpen,
                'details' => $isRobotsOpen ? 'Нет глобального Disallow: /' : 'Обнаружен запрет индексации',
            ],
            [
                'name' => 'sitemap.xml',
                'ok' => $sitemapExists,
                'details' => $sitemapExists ? 'Файл найден' : 'Файл не найден',
            ],
            [
                'name' => 'Sitemap в robots.txt',
                'ok' => $hasSitemapDirective,
                'details' => $hasSitemapDirective ? 'Директива Sitemap указана' : 'Добавьте директиву Sitemap',
            ],
            [
                'name' => 'APP_URL на HTTPS',
                'ok' => $usesHttps,
                'details' => $usesHttps ? $appUrl : 'Текущее значение: ' . $appUrl,
            ],
        ];

        $recommendations = [];
        if (!$sitemapExists) {
            $recommendations[] = 'Создайте sitemap.xml для ускоренной индексации новых мемориалов.';
        }
        if (!$hasSitemapDirective) {
            $recommendations[] = 'Добавьте директиву Sitemap в robots.txt.';
        }
        if (!$usesHttps) {
            $recommendations[] = 'Для продакшена используйте HTTPS в APP_URL.';
        }
        if ($memorialsWithoutPhoto > 0) {
            $recommendations[] = "У {$memorialsWithoutPhoto} опубликованных мемориалов нет фото. Это снижает CTR в поиске.";
        }
        if ($memorialsWithoutBiography > 0) {
            $recommendations[] = "У {$memorialsWithoutBiography} опубликованных мемориалов пустая краткая биография.";
        }
        if (empty($recommendations)) {
            $recommendations[] = 'Критичных SEO-замечаний не обнаружено.';
        }

        $stats = [
            'published_memorials' => $publishedMemorials,
            'draft_memorials' => $draftMemorials,
            'updated_last_week' => $updatedLastWeek,
            'index_quality' => $publishedMemorials > 0
                ? round((($publishedMemorials - $memorialsWithoutPhoto - $memorialsWithoutBiography) / $publishedMemorials) * 100)
                : 100,
            'without_photo' => $memorialsWithoutPhoto,
            'without_biography' => $memorialsWithoutBiography,
        ];

        return view('admin.seo', compact(
            'checks',
            'stats',
            'recommendations',
            'robotsContent',
            'sitemapUrl',
            'sampleMemorialUrl'
        ));
    }
    
    public function newsletter()
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $defaultMailer = config('mail.default');
        $mailerConfig = config("mail.mailers.{$defaultMailer}", []);
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $mailStatus = [
            'mailer' => $defaultMailer,
            'host' => $mailerConfig['host'] ?? 'n/a',
            'port' => $mailerConfig['port'] ?? 'n/a',
            'encryption' => $mailerConfig['encryption'] ?? 'none',
            'from_address' => $fromAddress ?: 'не задан',
            'from_name' => $fromName ?: 'не задан',
            'is_configured' => !empty($fromAddress),
        ];

        $audiences = [
            [
                'key' => 'all_users',
                'label' => 'Все пользователи',
                'count' => User::whereNotNull('email')->where('email', '!=', '')->count(),
            ],
            [
                'key' => 'published_memorial_owners',
                'label' => 'Владельцы опубликованных мемориалов',
                'count' => User::whereHas('memorials', function ($query) {
                    $query->where('status', 'published');
                })->whereNotNull('email')->where('email', '!=', '')->count(),
            ],
        ];

        $systemTemplates = [
            'welcome' => 'Приветствие после регистрации',
            'memorial_created' => 'Уведомление о создании мемориала',
            'memorial_published' => 'Уведомление о публикации мемориала',
            'new_memory_notification' => 'Уведомление владельцу о новом воспоминании',
            'new_comment_notification' => 'Уведомление автору о новом комментарии',
        ];

        return view('admin.newsletter', compact('mailStatus', 'audiences', 'systemTemplates'));
    }

    public function sendNewsletterTest(Request $request, EmailNotificationService $emailService)
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'test_email' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $isSent = $emailService->sendTestEmail(
            $validated['test_email'],
            $validated['subject'],
            $validated['content']
        );

        if (!$isSent) {
            return back()
                ->withInput()
                ->with('error', 'Не удалось отправить тестовое письмо. Проверьте настройки почты.');
        }

        return back()
            ->withInput()
            ->with('success', "Тестовое письмо отправлено на {$validated['test_email']}");
    }

    public function sendNewsletterCampaign(Request $request, EmailNotificationService $emailService)
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'audience' => ['required', 'in:all_users,published_memorial_owners'],
            'subject' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $sentCount = $emailService->sendCampaign(
            $validated['audience'],
            $validated['subject'],
            $validated['content']
        );

        return back()->with('success', "Рассылка отправлена. Успешно доставлено: {$sentCount}");
    }
    
    public function settings()
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $settings = AppSetting::current();

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        // Проверка прав доступа
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'default_locale' => ['required', 'in:ru,en'],
            'default_timezone' => ['required', 'string', 'max:100'],
            'admin_notification_email' => ['nullable', 'email', 'max:255'],
            'maintenance_message' => ['nullable', 'string', 'max:255'],
            'project_icon' => ['nullable', 'file', 'mimetypes:image/png', 'max:2048'],
        ]);

        $currentSettings = AppSetting::current();
        $iconPath = trim((string) data_get($currentSettings, 'branding.icon_path', ''));

        if ($request->boolean('remove_project_icon')) {
            $this->deleteManagedProjectIcon($iconPath);
            $iconPath = '';
        }

        if ($request->hasFile('project_icon')) {
            $this->deleteManagedProjectIcon($iconPath);
            $iconPath = $this->storeProjectIcon($request->file('project_icon'));
        }

        $settings = [
            'general' => [
                'site_name' => trim($validated['site_name']),
                'site_tagline' => trim((string) ($validated['site_tagline'] ?? '')),
                'support_email' => trim((string) ($validated['support_email'] ?? '')),
                'contact_phone' => trim((string) ($validated['contact_phone'] ?? '')),
                'default_locale' => $validated['default_locale'],
                'default_timezone' => $validated['default_timezone'],
            ],
            'branding' => [
                'icon_path' => $iconPath,
            ],
            'access' => [
                'allow_registration' => $request->boolean('allow_registration'),
                'enable_memorial_creation' => $request->boolean('enable_memorial_creation'),
                'enable_memories' => $request->boolean('enable_memories'),
                'enable_comments' => $request->boolean('enable_comments'),
                'enable_public_profiles' => $request->boolean('enable_public_profiles'),
            ],
            'moderation' => [
                'auto_publish_memorials' => $request->boolean('auto_publish_memorials'),
                'moderate_memories' => $request->boolean('moderate_memories'),
                'moderate_comments' => $request->boolean('moderate_comments'),
            ],
            'notifications' => [
                'admin_notification_email' => trim((string) ($validated['admin_notification_email'] ?? '')),
                'notify_new_user' => $request->boolean('notify_new_user'),
                'notify_new_memorial' => $request->boolean('notify_new_memorial'),
                'notify_new_comment' => $request->boolean('notify_new_comment'),
            ],
            'maintenance' => [
                'maintenance_mode' => $request->boolean('maintenance_mode'),
                'maintenance_message' => trim((string) ($validated['maintenance_message'] ?? '')),
            ],
        ];

        AppSetting::saveCurrent($settings);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Настройки сохранены');
    }

    private function storeProjectIcon(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = 'branding/project-icon-' . Str::uuid() . '.png';
        
        Storage::disk('s3')->put($filename, file_get_contents($file->getRealPath()), 'public');
        
        return $filename;
    }

    private function deleteManagedProjectIcon(?string $path): void
    {
        $relative = trim((string) $path);

        if ($relative === '' || !str_starts_with($relative, 'branding/')) {
            return;
        }

        if (Storage::disk('s3')->exists($relative)) {
            Storage::disk('s3')->delete($relative);
        }
    }
}
