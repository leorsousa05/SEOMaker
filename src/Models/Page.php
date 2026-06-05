<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Page
{
    public ?int $id = null;
    public string $slug = '';
    public string $title = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $content_html = '';
    public string $schema_type = 'WebPage';
    public string $schema_data = '{}';
    public bool $is_active = true;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $page = new self();
        $page->id = isset($data['id']) ? (int) $data['id'] : null;
        $page->slug = (string) ($data['slug'] ?? '');
        $page->title = (string) ($data['title'] ?? '');
        $page->meta_title = (string) ($data['meta_title'] ?? '');
        $page->meta_description = (string) ($data['meta_description'] ?? '');
        $page->content_html = (string) ($data['content_html'] ?? '');
        $page->schema_type = (string) ($data['schema_type'] ?? 'WebPage');
        $page->schema_data = (string) ($data['schema_data'] ?? '{}');
        $page->is_active = (bool) ($data['is_active'] ?? true);
        $page->created_at = $data['created_at'] ?? null;
        $page->updated_at = $data['updated_at'] ?? null;
        return $page;
    }
    
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'content_html' => $this->content_html,
            'schema_type' => $this->schema_type,
            'schema_data' => $this->schema_data,
            'is_active' => $this->is_active ? 1 : 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    
    public static function generateSlug(string $title): string
    {
        $slug = mb_strtolower(trim($title));
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
        $slug = preg_replace('/[^a-z0-9\-]+/', '-', $slug) ?: '';
        $slug = trim($slug, '-');
        return $slug;
    }
    
    public static function isDuplicateSlug(string $slug, ?int $excludeId = null): bool
    {
        if ($slug === '') {
            return false;
        }
        $sql = 'SELECT 1 FROM pages WHERE slug = ?';
        $params = [$slug];
        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $existing = Database::fetchOne($sql, $params);
        return $existing !== false;
    }
    
    /**
     * @param array<string, mixed> $data
     * @return array<string, string>
     */
    public static function validate(array $data, ?int $excludeId = null): array
    {
        $errors = [];
        $title = trim((string) ($data['title'] ?? ''));
        $slug = trim((string) ($data['slug'] ?? ''));
        
        if ($title === '') {
            $errors['title'] = 'O título da página é obrigatório.';
        }
        
        if ($slug !== '' && !preg_match('/^[a-z0-9\-]+$/', $slug)) {
            $errors['slug'] = 'O slug deve conter apenas letras minúsculas, números e hífens.';
        }
        
        if ($slug !== '' && self::isDuplicateSlug($slug, $excludeId)) {
            $errors['slug'] = 'Este slug já está em uso por outra página.';
        }
        
        return $errors;
    }
}
