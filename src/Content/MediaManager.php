<?php

declare(strict_types=1);

namespace App\Content;

use App\Core\Database;
use App\Models\Media;

class MediaManager
{
    private static string $uploadDir;
    private static string $uploadUrl;
    private static string $publicDir;
    
    private static function init(): void
    {
        if (!isset(self::$uploadDir)) {
            self::$publicDir = realpath(__DIR__ . '/../../public') ?: __DIR__ . '/../../public';
            self::$uploadDir = self::$publicDir . '/uploads';
            self::$uploadUrl = '/uploads';
        }
    }
    
    public static function upload(array $file): array
    {
        self::init();
        
        $errors = [];
        
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['success' => false, 'error' => 'Nenhum arquivo enviado'];
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Erro no upload: ' . self::uploadErrorMessage($file['error'])];
        }
        
        $mimeType = mime_content_type($file['tmp_name']);
        if (!self::isValidImage($file['tmp_name'], $mimeType)) {
            return ['success' => false, 'error' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP.'];
        }
        
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Arquivo muito grande. Máximo 5MB.'];
        }
        
        $ext = self::getExtension($mimeType);
        if (!$ext) {
            return ['success' => false, 'error' => 'Extensão de arquivo não reconhecida.'];
        }
        
        $year = date('Y');
        $month = date('m');
        $dir = self::$uploadDir . "/{$year}/{$month}";
        
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $safeName = self::sanitizeFilename($file['name']);
        $uniqueName = uniqid() . '_' . $safeName;
        $path = "{$dir}/{$uniqueName}";
        $relativePath = "/uploads/{$year}/{$month}/{$uniqueName}";
        
        $moved = (PHP_SAPI === 'cli')
            ? rename($file['tmp_name'], $path)
            : move_uploaded_file($file['tmp_name'], $path);

        if (!$moved) {
            return ['success' => false, 'error' => 'Falha ao mover arquivo.'];
        }
        
        // Generate thumbnail
        $thumbPath = null;
        $thumbRelative = null;
        if (extension_loaded('gd')) {
            $thumbResult = self::generateThumbnail($path, $dir, $uniqueName);
            if ($thumbResult) {
                $thumbPath = $thumbResult['path'];
                $thumbRelative = $thumbResult['url'];
            }
        }
        
        // Get dimensions
        $width = null;
        $height = null;
        $imgInfo = getimagesize($path);
        if ($imgInfo) {
            $width = $imgInfo[0];
            $height = $imgInfo[1];
        }
        
        $mediaId = Database::insert('media', [
            'filename' => $uniqueName,
            'original_name' => $file['name'],
            'mime_type' => $mimeType,
            'size_bytes' => $file['size'],
            'width' => $width,
            'height' => $height,
            'path' => $relativePath,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        return [
            'success' => true,
            'id' => $mediaId,
            'filename' => $uniqueName,
            'path' => $relativePath,
            'thumb' => $thumbRelative,
            'width' => $width,
            'height' => $height,
        ];
    }
    
    public static function delete(int $id): bool
    {
        self::init();
        
        $media = Database::fetchOne('SELECT * FROM media WHERE id = ?', [$id]);
        if (!$media) {
            return false;
        }
        
        $path = __DIR__ . '/../../public' . $media['path'];
        if (file_exists($path)) {
            unlink($path);
        }
        
        // Delete thumbnail if exists
        $thumbPath = dirname($path) . '/thumb_' . basename($path);
        if (file_exists($thumbPath)) {
            unlink($thumbPath);
        }
        
        Database::delete('media', 'id = ?', [$id]);
        return true;
    }
    
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function list(int $page = 1, int $perPage = 24): array
    {
        $offset = ($page - 1) * $perPage;
        return Database::fetchAll(
            'SELECT * FROM media ORDER BY id DESC LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );
    }
    
    public static function count(): int
    {
        $result = Database::fetchOne('SELECT COUNT(*) as count FROM media');
        return (int) ($result['count'] ?? 0);
    }
    
    public static function find(int $id): ?Media
    {
        $data = Database::fetchOne('SELECT * FROM media WHERE id = ?', [$id]);
        return $data ? Media::fromArray($data) : null;
    }
    
    /**
     * @return array{path: string, url: string}|null
     */
    public static function generateThumbnail(string $sourcePath, string $destDir, string $filename): ?array
    {
        if (!extension_loaded('gd')) {
            return null;
        }
        
        $thumbName = 'thumb_' . $filename;
        $thumbPath = $destDir . '/' . $thumbName;
        
        $imgInfo = getimagesize($sourcePath);
        if (!$imgInfo) {
            return null;
        }
        
        [$srcWidth, $srcHeight, $type] = $imgInfo;
        
        $thumbWidth = 300;
        $thumbHeight = 300;
        
        // Calculate crop dimensions
        $srcRatio = $srcWidth / $srcHeight;
        $thumbRatio = $thumbWidth / $thumbHeight;
        
        if ($srcRatio > $thumbRatio) {
            $cropHeight = $srcHeight;
            $cropWidth = (int) ($srcHeight * $thumbRatio);
            $srcX = (int) (($srcWidth - $cropWidth) / 2);
            $srcY = 0;
        } else {
            $cropWidth = $srcWidth;
            $cropHeight = (int) ($srcWidth / $thumbRatio);
            $srcX = 0;
            $srcY = (int) (($srcHeight - $cropHeight) / 2);
        }
        
        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        if (!$thumb) {
            return null;
        }
        
        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF => imagecreatefromgif($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($sourcePath) : false,
            default => false,
        };
        
        if (!$source) {
            imagedestroy($thumb);
            return null;
        }
        
        // Preserve transparency for PNG/GIF
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }
        
        imagecopyresampled($thumb, $source, 0, 0, $srcX, $srcY, $thumbWidth, $thumbHeight, $cropWidth, $cropHeight);
        
        $saved = match ($type) {
            IMAGETYPE_JPEG => imagejpeg($thumb, $thumbPath, 85),
            IMAGETYPE_PNG => imagepng($thumb, $thumbPath, 8),
            IMAGETYPE_GIF => imagegif($thumb, $thumbPath),
            IMAGETYPE_WEBP => function_exists('imagewebp') ? imagewebp($thumb, $thumbPath, 85) : false,
            default => false,
        };
        
        imagedestroy($thumb);
        imagedestroy($source);
        
        if (!$saved) {
            return null;
        }
        
        self::init();
        $resolvedThumbPath = realpath($thumbPath) ?: $thumbPath;
        $relativeUrl = str_replace(self::$publicDir, '', $resolvedThumbPath);
        return ['path' => $thumbPath, 'url' => $relativeUrl];
    }
    
    public static function isValidImage(string $tmpPath, string $mimeType): bool
    {
        $validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $validTypes, true)) {
            return false;
        }
        
        $imgInfo = getimagesize($tmpPath);
        return $imgInfo !== false;
    }
    
    private static function getExtension(string $mimeType): ?string
    {
        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            default => null,
        };
    }
    
    private static function sanitizeFilename(string $filename): string
    {
        $filename = preg_replace('/[^\w\s.-]/u', '', $filename);
        $filename = preg_replace('/\s+/', '_', $filename);
        $filename = strtolower($filename);
        return $filename;
    }
    
    private static function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => 'Arquivo excede o tamanho máximo do servidor.',
            UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o tamanho máximo do formulário.',
            UPLOAD_ERR_PARTIAL => 'Upload incompleto.',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo enviado.',
            UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária não encontrada.',
            UPLOAD_ERR_CANT_WRITE => 'Erro ao salvar arquivo.',
            UPLOAD_ERR_EXTENSION => 'Upload bloqueado por extensão.',
            default => 'Erro desconhecido.',
        };
    }
}
