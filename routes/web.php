<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemorialController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/memorial/id{id}', [MemorialController::class, 'show'])->name('memorial.show')->where('id', '[0-9]+');
Route::get('/memorial/create', [MemorialController::class, 'create'])->middleware('auth')->name('memorial.create');
Route::post('/memorial', [MemorialController::class, 'store'])->middleware('auth')->name('memorial.store');
Route::get('/memorial/id{id}/edit', [MemorialController::class, 'edit'])->middleware('auth')->name('memorial.edit')->where('id', '[0-9]+');
Route::put('/memorial/{id}', [MemorialController::class, 'update'])->middleware('auth')->name('memorial.update');

// Memory routes
Route::post('/memorial/{id}/memory', [App\Http\Controllers\MemoryController::class, 'store'])->middleware('auth')->name('memory.store');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile route (my profile)
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth')->name('profile');

// User profile route (public profiles)
Route::get('/user/id{id}', [App\Http\Controllers\UserController::class, 'show'])->name('user.show')->where('id', '[0-9]+');

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

// Тестовая страница карты
Route::get('/test-map', function () {
    return view('test-map');
});
