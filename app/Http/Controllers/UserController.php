<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show($id)
    {
        if (!AppSetting::get('access.enable_public_profiles', true)) {
            if (!auth()->check() || (int) auth()->id() !== (int) $id) {
                abort(403, 'Публичные профили временно отключены');
            }
        }

        $user = User::with([
            'memorials' => function($query) use ($id) {
                // Если смотрим свой профиль - показываем все мемориалы
                if (auth()->check() && auth()->id() == $id) {
                    return $query;
                }
                // Для других пользователей - только опубликованные
                return $query->where('status', 'published')
                    ->where(function ($visibilityQuery) {
                        $visibilityQuery->where('privacy', 'public')->orWhereNull('privacy');
                    });
            },
            'memories.memorial' // Загружаем воспоминания пользователя с мемориалами
        ])->findOrFail($id);
        
        // Проверка приватности профиля
        // TODO: добавить проверку настроек приватности
        
        return view('user.show', compact('user'));
    }

    public function edit()
    {
        return view('user.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:10240'], // 10MB
        ]);

        // Собираем полное имя
        $fullName = trim($validated['last_name'] . ' ' . $validated['first_name'] . ' ' . ($validated['middle_name'] ?? ''));
        
        // Обновление основных данных
        $user->name = $fullName;
        $user->email = $validated['email'];
        $user->country = $validated['country'] ?? null;
        $user->region = $validated['region'] ?? null;
        $user->city = $validated['city'] ?? null;

        // Обработка аватара
        if ($request->hasFile('avatar')) {
            $storageService = app(\App\Services\StorageService::class);
            $avatarPath = $storageService->uploadUserAvatar($request->file('avatar'), $user->id);
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('user.edit')->with('success', 'Профиль успешно обновлен');
    }

    public function security()
    {
        return view('user.security');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('user.security')->with('success', 'Пароль успешно изменен');
    }

    public function privacy()
    {
        return view('user.privacy');
    }

    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_type' => ['required', 'in:public,private'],
            'show_email' => ['nullable', 'boolean'],
            'show_memorials' => ['nullable', 'boolean'],
        ]);

        $user->profile_type = $validated['profile_type'];
        $user->show_email = $request->has('show_email');
        $user->show_memorials = $request->has('show_memorials');
        $user->save();

        return redirect()->route('user.privacy')->with('success', 'Настройки приватности обновлены');
    }

    public function convertHeic(Request $request)
    {
        $request->validate([
            'heic_file' => ['required', 'file', 'max:10240'], // 10MB
        ]);

        $file = $request->file('heic_file');
        
        try {
            // Используем HeicToJpg для конвертации
            $heicPath = $file->getRealPath();
            $jpgPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
            
            $heicToJpg = new \Maestroerror\HeicToJpg();
            $heicToJpg->convert($heicPath)->saveAs($jpgPath);
            
            // Возвращаем JPG файл
            return response()->file($jpgPath, [
                'Content-Type' => 'image/jpeg',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Не удалось конвертировать HEIC: ' . $e->getMessage()], 500);
        }
    }
}
