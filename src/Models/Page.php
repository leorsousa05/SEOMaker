<?php

declare(strict_types=1);

namespace App\Models;

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
}
