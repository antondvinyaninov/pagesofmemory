<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemorialController;
use App\Http\Controllers\AuthController;
use App\Models\Memorial;

Route::get('/', function () {
    // Статистика
    $stats = [
        'photos' => \DB::table('memorials')
            ->whereNotNull('photos')
            ->where('photos', '!=', '[]')
            ->selectRaw('SUM(json_array_length(photos::json)) as total')
            ->value('total') ?? 0,
        'memories' => \App\Models\Memory::count(),
        'users' => \App\Models\User::count(),
        'memorials' => Memorial::where('status', 'published')->count(),
    ];
    
    $recentMemorials = Memorial::query()
        ->where('status', 'published')
        ->where(function ($query) {
            $query->where('privacy', 'public')->orWhereNull('privacy');
        })
        ->select('id', 'first_name', 'middle_name', 'last_name', 'birth_date', 'death_date', 'birth_place', 'views', 'photo', 'updated_at')
        ->withCount('memories')
        ->orderByDesc('updated_at')
        ->orderByDesc('id')
        ->take(6)
        ->get();

    return view('welcome', compact('recentMemorials', 'stats'));
});

Route::get('/sitemap.xml', function () {
    $memorials = cache()->remember('sitemap_published_memorials', 3600, function () {
        return Memorial::where('status', 'published')
            ->where(function ($query) {
                $query->where('privacy', 'public')->orWhereNull('privacy');
            })
            ->select('id', 'updated_at')
            ->orderByDesc('updated_at')
            ->get();
    });

    return response()
        ->view('sitemap', compact('memorials'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap.xml');

Route::get('/memorial/id{id}', [MemorialController::class, 'show'])->name('memorial.show')->where('id', '[0-9]+');
Route::get('/memorial/create', [MemorialController::class, 'create'])->middleware('auth')->name('memorial.create');
Route::post('/memorial', [MemorialController::class, 'store'])->middleware('auth')->name('memorial.store');
Route::get('/memorial/id{id}/edit', [MemorialController::class, 'edit'])->middleware('auth')->name('memorial.edit')->where('id', '[0-9]+');
Route::put('/memorial/{id}', [MemorialController::class, 'update'])->middleware('auth')->name('memorial.update');

// Memory routes
Route::post('/memorial/{id}/memory', [App\Http\Controllers\MemoryController::class, 'store'])->middleware('auth')->name('memory.store');
Route::post('/memory/{id}/like', [App\Http\Controllers\MemoryController::class, 'like'])->middleware('auth')->name('memory.like');
Route::post('/memory/{id}/view', [App\Http\Controllers\MemoryController::class, 'view'])->name('memory.view');
Route::post('/memory/{id}/comment', [App\Http\Controllers\MemoryController::class, 'comment'])->middleware('auth')->name('memory.comment');
Route::post('/comment/{id}/like', [App\Http\Controllers\MemoryController::class, 'likeComment'])->middleware('auth')->name('comment.like');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile route (my profile)
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth')->name('profile');

// User profile route (public profiles)
Route::get('/user/id{id}', [App\Http\Controllers\UserController::class, 'show'])->name('user.show')->where('id', '[0-9]+');
Route::redirect('/user/admin', '/admin');

// User profile edit
Route::get('/user/edit', [App\Http\Controllers\UserController::class, 'edit'])->middleware('auth')->name('user.edit');
Route::put('/user/update', [App\Http\Controllers\UserController::class, 'update'])->middleware('auth')->name('user.update');

// User security
Route::get('/user/security', [App\Http\Controllers\UserController::class, 'security'])->middleware('auth')->name('user.security');
Route::put('/user/password', [App\Http\Controllers\UserController::class, 'updatePassword'])->middleware('auth')->name('user.updatePassword');

// User privacy
Route::get('/user/privacy', [App\Http\Controllers\UserController::class, 'privacy'])->middleware('auth')->name('user.privacy');
Route::put('/user/privacy', [App\Http\Controllers\UserController::class, 'updatePrivacy'])->middleware('auth')->name('user.updatePrivacy');

// API для конвертации HEIC
Route::post('/api/convert-heic', [App\Http\Controllers\UserController::class, 'convertHeic'])->middleware('auth');

// Admin routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::get('/memorials', [App\Http\Controllers\AdminController::class, 'memorials'])->name('admin.memorials');
    Route::get('/analytics', [App\Http\Controllers\AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/seo', [App\Http\Controllers\AdminController::class, 'seo'])->name('admin.seo');
    Route::get('/newsletter', [App\Http\Controllers\AdminController::class, 'newsletter'])->name('admin.newsletter');
    Route::post('/newsletter/test', [App\Http\Controllers\AdminController::class, 'sendNewsletterTest'])->name('admin.newsletter.test');
    Route::post('/newsletter/send', [App\Http\Controllers\AdminController::class, 'sendNewsletterCampaign'])->name('admin.newsletter.send');
    Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::delete('/memorials/{id}', [App\Http\Controllers\AdminController::class, 'deleteMemorial'])->name('admin.memorials.delete');
});

// Тестовая страница карты
Route::get('/test-map', function () {
    return view('test-map');
});
