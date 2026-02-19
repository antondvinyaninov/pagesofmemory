<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public static function defaults(): array
    {
        return [
            'general' => [
                'site_name' => config('app.name', 'Memory'),
                'site_tagline' => 'Платформа страниц памяти',
                'support_email' => '',
                'contact_phone' => '',
                'default_locale' => 'ru',
                'default_timezone' => config('app.timezone', 'UTC'),
            ],
            'branding' => [
                'icon_path' => '',
            ],
            'access' => [
                'allow_registration' => true,
                'enable_memorial_creation' => true,
                'enable_memories' => true,
                'enable_comments' => true,
                'enable_public_profiles' => true,
            ],
            'moderation' => [
                'auto_publish_memorials' => false,
                'moderate_memories' => false,
                'moderate_comments' => false,
            ],
            'notifications' => [
                'admin_notification_email' => '',
                'notify_new_user' => true,
                'notify_new_memorial' => true,
                'notify_new_comment' => false,
            ],
            'maintenance' => [
                'maintenance_mode' => false,
                'maintenance_message' => 'Сервис временно недоступен. Скоро вернемся.',
            ],
        ];
    }

    public static function current(): array
    {
        $record = self::query()->firstOrCreate(
            ['id' => 1],
            ['data' => self::defaults()]
        );

        $data = is_array($record->data) ? $record->data : [];

        return array_replace_recursive(self::defaults(), $data);
    }

    public static function saveCurrent(array $settings): self
    {
        return self::query()->updateOrCreate(
            ['id' => 1],
            ['data' => $settings]
        );
    }

    public static function get(string $path, mixed $default = null): mixed
    {
        return data_get(self::current(), $path, $default);
    }
}
