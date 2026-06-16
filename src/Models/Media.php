<?php

declare(strict_types=1);

namespace App\Models;

class Media
{
    public ?int $id = null;
    public string $filename = '';
    public string $original_name = '';
    public string $mime_type = '';
    public int $size_bytes = 0;
    public ?int $width = null;
    public ?int $height = null;
    public string $path = '';
    public ?string $created_at = null;
    
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $media = new self();
        $media->id = isset($data['id']) ? (int) $data['id'] : null;
        $media->filename = (string) ($data['filename'] ?? '');
        $media->original_name = (string) ($data['original_name'] ?? '');
        $media->mime_type = (string) ($data['mime_type'] ?? '');
        $media->size_bytes = (int) ($data['size_bytes'] ?? 0);
        $media->width = isset($data['width']) ? (int) $data['width'] : null;
        $media->height = isset($data['height']) ? (int) $data['height'] : null;
        $media->path = (string) ($data['path'] ?? '');
        $media->created_at = $data['created_at'] ?? null;
        return $media;
    }
    
    public function thumbUrl(): string
    {
        $dir = dirname($this->path);
        $file = basename($this->path);
        return $dir . '/thumb_' . $file;
    }
    
    public static function find(int $id): ?self
    {
        $data = \App\Core\Database::fetchOne('SELECT * FROM media WHERE id = ?', [$id]);
        if (!$data) {
            return null;
        }
        return self::fromArray($data);
    }
    
    public function url(): string
    {
        return $this->path;
    }
    
    public function humanSize(): string
    {
        $bytes = $this->size_bytes;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / (1024 * 1024), 1) . ' MB';
    }
}
