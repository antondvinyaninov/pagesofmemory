<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Maestroerror\HeicToJpg;

class StorageService
{
    /**
     * Конвертировать изображение в WebP
     */
    private function convertToWebP(UploadedFile $file, ?int $maxWidth = null, ?int $maxHeight = null): string
    {
        // Проверяем, является ли файл HEIC
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        if ($extension === 'heic' || $extension === 'heif' || $mimeType === 'image/heic' || $mimeType === 'image/heif') {
            // Конвертируем HEIC в JPG сначала
            $heicPath = $file->getRealPath();
            $jpgPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
            
            try {
                $heicToJpg = new HeicToJpg();
                $heicToJpg->convert($heicPath)->saveAs($jpgPath);
                
                // Теперь конвертируем JPG в WebP
                $image = \imagecreatefromjpeg($jpgPath);
                unlink($jpgPath); // Удаляем временный JPG
            } catch (\Exception $e) {
                throw new \Exception('Не удалось конвертировать HEIC: ' . $e->getMessage());
            }
        } else {
            // Обычное изображение - определяем тип по MIME
            $fileContent = file_get_contents($file);
            
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = \imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'image/png':
                    $image = \imagecreatefrompng($file->getRealPath());
                    break;
                case 'image/gif':
                    $image = \imagecreatefromgif($file->getRealPath());
                    break;
                case 'image/webp':
                    $image = \imagecreatefromwebp($file->getRealPath());
                    break;
                case 'image/bmp':
                case 'image/x-ms-bmp':
                    $image = \imagecreatefrombmp($file->getRealPath());
                    break;
                default:
                    // Пробуем универсальный метод
                    $image = \imagecreatefromstring($fileContent);
                    break;
            }
        }
        
        if (!$image) {
            throw new \Exception('Не удалось загрузить изображение. Формат: ' . $mimeType);
        }
        
        // Получаем размеры оригинала
        $originalWidth = \imagesx($image);
        $originalHeight = \imagesy($image);
        
        // Если заданы максимальные размеры, ресайзим
        if ($maxWidth || $maxHeight) {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
            
            // Вычисляем новые размеры с сохранением пропорций
            if ($maxWidth && $newWidth > $maxWidth) {
                $ratio = $maxWidth / $newWidth;
                $newWidth = $maxWidth;
                $newHeight = (int)($newHeight * $ratio);
            }
            
            if ($maxHeight && $newHeight > $maxHeight) {
                $ratio = $maxHeight / $newHeight;
                $newHeight = $maxHeight;
                $newWidth = (int)($newWidth * $ratio);
            }
            
            // Создаем новое изображение с нужными размерами
            $resizedImage = \imagecreatetruecolor($newWidth, $newHeight);
            \imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            \imagedestroy($image);
            $image = $resizedImage;
        }
        
        // Создаем временный файл для WebP
        $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.webp';
        
        // Конвертируем в WebP с качеством 85%
        \imagewebp($image, $tempPath, 85);
        \imagedestroy($image);
        
        $webpContent = file_get_contents($tempPath);
        unlink($tempPath);
        
        return $webpContent;
    }

    /**
     * Загрузить главное фото мемориала
     */
    public function uploadMemorialPhoto(int $memorialId, UploadedFile $file, string $type = 'main'): string
    {
        if ($type === 'burial') {
            // Фото места захоронения
            $filename = uniqid() . '.webp';
            $path = "memorials/{$memorialId}/burial/{$filename}";
        } else {
            // Главное фото
            $path = "memorials/{$memorialId}/main.webp";
        }
        
        $webpContent = $this->convertToWebP($file, 1920, 1080);
        Storage::put($path, $webpContent, 'public');
        return $path;
    }

    /**
     * Загрузить фото в галерею мемориала
     */
    public function uploadMemorialGalleryPhoto(int $memorialId, UploadedFile $file): string
    {
        $filename = uniqid() . '.webp';
        $path = "memorials/{$memorialId}/gallery/{$filename}";
        $webpContent = $this->convertToWebP($file, 1920, 1080);
        Storage::put($path, $webpContent, 'public');
        return $path;
    }

    /**
     * Загрузить видео мемориала
     */
    public function uploadMemorialVideo(int $memorialId, UploadedFile $file): string
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "memorials/{$memorialId}/videos/{$filename}";
        Storage::put($path, file_get_contents($file), 'public');
        return $path;
    }

    /**
     * Загрузить документ/фото военной службы
     */
    public function uploadMemorialMilitaryFile(int $memorialId, UploadedFile $file): string
    {
        return $this->uploadMemorialDocument($memorialId, $file, 'military');
    }

    /**
     * Загрузить документ/фото достижений
     */
    public function uploadMemorialAchievementFile(int $memorialId, UploadedFile $file): string
    {
        return $this->uploadMemorialDocument($memorialId, $file, 'achievements');
    }

    /**
     * Загрузить фото к воспоминанию
     */
    public function uploadMemoryPhoto(int $memoryId, UploadedFile $file): string
    {
        $filename = uniqid() . '.webp';
        $path = "memories/{$memoryId}/{$filename}";
        $webpContent = $this->convertToWebP($file, 1920, 1080);
        Storage::put($path, $webpContent, 'public');
        return $path;
    }

    /**
     * Загрузить аватар пользователя
     */
    public function uploadUserAvatar(UploadedFile $file, int $userId): string
    {
        $path = "users/avatars/{$userId}.webp";
        $webpContent = $this->convertToWebP($file, 500, 500);
        Storage::put($path, $webpContent, 'public');
        return $path;
    }

    /**
     * Загрузить изображение или PDF-документ для мемориала
     */
    private function uploadMemorialDocument(int $memorialId, UploadedFile $file, string $folder): string
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if ($extension === 'pdf') {
            $filename = uniqid() . '.pdf';
            $path = "memorials/{$memorialId}/{$folder}/{$filename}";
            Storage::put($path, file_get_contents($file), 'public');
            return $path;
        }

        $filename = uniqid() . '.webp';
        $path = "memorials/{$memorialId}/{$folder}/{$filename}";
        $webpContent = $this->convertToWebP($file, 1920, 1080);
        Storage::put($path, $webpContent, 'public');

        return $path;
    }

    /**
     * Удалить файл
     */
    public function deleteFile(string $path): bool
    {
        return Storage::delete($path);
    }

    /**
     * Получить URL файла
     */
    public function getUrl(string $path): string
    {
        return Storage::url($path);
    }
}
