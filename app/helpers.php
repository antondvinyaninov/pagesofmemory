<?php

/**
 * Helper functions for the Memory Platform
 */

if (!function_exists('expand_region_abbreviations')) {
    /**
     * Заменяет сокращения в названиях регионов на полные названия
     * 
     * @param string|null $text
     * @return string|null
     */
    function expand_region_abbreviations(?string $text): ?string
    {
        if (!$text) {
            return $text;
        }
        
        $replacements = [
            ' Респ' => ' Республика',
            ' обл' => ' область',
            ' край' => ' край',
            ' АО' => ' автономный округ',
            ' Аобл' => ' автономная область',
            ' г' => ' город',
        ];
        
        foreach ($replacements as $abbr => $full) {
            // Заменяем только если после сокращения нет других букв
            $text = preg_replace('/' . preg_quote($abbr, '/') . '(?![а-яА-Яa-zA-Z])/', $full, $text);
        }
        
        return $text;
    }
}

if (!function_exists('s3_url')) {
    /**
     * Получает URL из S3 с кешированием
     * 
     * @param string|null $path
     * @return string|null
     */
    function s3_url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        
        // Кешируем URL на 1 час
        return cache()->remember("s3_url_{$path}", 3600, function () use ($path) {
            return \Storage::disk('s3')->url($path);
        });
    }
}

if (!function_exists('avatar_url')) {
    /**
     * Получает URL аватарки пользователя или генерирует placeholder
     * 
     * @param object $user
     * @return string
     */
    function avatar_url($user): string
    {
        if ($user->avatar) {
            return s3_url($user->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=ef4444&color=fff&bold=true';
    }
}

if (!function_exists('project_site_name')) {
    /**
     * Возвращает название сервиса из настроек с безопасным fallback.
     */
    function project_site_name(): string
    {
        static $resolvedName = null;

        if ($resolvedName !== null) {
            return $resolvedName;
        }

        try {
            $configuredName = trim((string) \App\Models\AppSetting::get('general.site_name', ''));
        } catch (\Throwable) {
            $configuredName = '';
        }

        $resolvedName = $configuredName !== ''
            ? $configuredName
            : config('app.name', 'Memory');

        return $resolvedName;
    }
}

if (!function_exists('project_icon_path')) {
    /**
     * Возвращает путь иконки проекта из настроек или путь по умолчанию.
     */
    function project_icon_path(): string
    {
        static $resolvedPath = null;

        if ($resolvedPath !== null) {
            return $resolvedPath;
        }

        try {
            $customPath = trim((string) \App\Models\AppSetting::get('branding.icon_path', ''));
        } catch (\Throwable) {
            $customPath = '';
        }

        // Если путь начинается с branding/ - это S3
        if ($customPath !== '' && str_starts_with($customPath, 'branding/')) {
            $resolvedPath = $customPath;
            return $resolvedPath;
        }

        // Если это внешний URL
        if ($customPath !== '' && (str_starts_with($customPath, 'http://') || str_starts_with($customPath, 'https://'))) {
            $resolvedPath = $customPath;
            return $resolvedPath;
        }

        // Если это локальный путь (старая логика для совместимости)
        if ($customPath !== '' && !str_starts_with($customPath, 'http://') && !str_starts_with($customPath, 'https://')) {
            $normalizedPath = ltrim($customPath, '/');

            if (file_exists(public_path($normalizedPath))) {
                $resolvedPath = $normalizedPath;
                return $resolvedPath;
            }
        }

        // Дефолтная иконка
        $resolvedPath = 'brand/memory-icon.svg';
        return $resolvedPath;
    }
}

if (!function_exists('project_icon_url')) {
    /**
     * Возвращает URL иконки проекта.
     */
    function project_icon_url(): string
    {
        $path = project_icon_path();

        // Если это уже полный URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Если это путь в S3 (начинается с branding/)
        if (str_starts_with($path, 'branding/')) {
            return Storage::disk('s3')->url($path);
        }

        // Локальный файл
        return asset($path);
    }
}

if (!function_exists('project_icon_mime_type')) {
    /**
     * Определяет MIME-тип по расширению пути иконки.
     */
    function project_icon_mime_type(): string
    {
        $path = project_icon_path();
        $ext = strtolower(pathinfo(parse_url($path, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        return match ($ext) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            default => 'image/png',
        };
    }
}

if (!function_exists('project_apple_touch_icon_url')) {
    /**
     * URL иконки для apple-touch-icon (iOS ожидает PNG).
     */
    function project_apple_touch_icon_url(): string
    {
        $path = project_icon_path();
        $ext = strtolower(pathinfo(parse_url($path, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        if ($ext === 'png') {
            return project_icon_url();
        }

        return asset('brand/memory-icon.png');
    }
}

if (!function_exists('project_email_icon_url')) {
    /**
     * URL иконки для email-шаблонов (почтовые клиенты лучше поддерживают PNG).
     */
    function project_email_icon_url(): string
    {
        $path = project_icon_path();
        $ext = strtolower(pathinfo(parse_url($path, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        if ($ext === 'png') {
            return project_icon_url();
        }

        return asset('brand/memory-icon.png');
    }
}
