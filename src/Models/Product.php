<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Product
{
    public ?int    $id               = null;
    public string  $name             = '';
    public string  $slug             = '';
    public string  $short_description = '';
    public string  $description      = '';
    public float   $price            = 0.0;
    public ?float  $promo_price      = null;
    public ?int    $image_id         = null;
    public string  $gallery_ids      = '[]';
    public string  $category         = '';
    public string  $tags             = '';
    public string  $sku              = '';
    public int     $stock            = 0;
    public string  $external_link    = '';
    public bool    $featured         = false;
    public bool    $is_active        = true;
    public ?string $created_at       = null;
    public ?string $updated_at       = null;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $p = new self();
        $p->id               = isset($data['id']) ? (int) $data['id'] : null;
        $p->name             = (string) ($data['name'] ?? '');
        $p->slug             = (string) ($data['slug'] ?? '');
        $p->short_description = (string) ($data['short_description'] ?? '');
        $p->description      = (string) ($data['description'] ?? '');
        $p->price            = isset($data['price']) ? (float) $data['price'] : 0.0;
        $p->promo_price      = isset($data['promo_price']) && $data['promo_price'] !== '' && $data['promo_price'] !== null
                                ? (float) $data['promo_price'] : null;
        $p->image_id         = isset($data['image_id']) && $data['image_id'] !== '' ? (int) $data['image_id'] : null;
        $p->gallery_ids      = (string) ($data['gallery_ids'] ?? '[]');
        $p->category         = (string) ($data['category'] ?? '');
        $p->tags             = (string) ($data['tags'] ?? '');
        $p->sku              = (string) ($data['sku'] ?? '');
        $p->stock            = isset($data['stock']) ? (int) $data['stock'] : 0;
        $p->external_link    = (string) ($data['external_link'] ?? '');
        $p->featured         = (bool) ($data['featured'] ?? false);
        $p->is_active        = (bool) ($data['is_active'] ?? true);
        $p->created_at       = $data['created_at'] ?? null;
        $p->updated_at       = $data['updated_at'] ?? null;
        return $p;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'short_description' => $this->short_description,
            'description'       => $this->description,
            'price'             => $this->price,
            'promo_price'       => $this->promo_price,
            'image_id'          => $this->image_id,
            'gallery_ids'       => $this->gallery_ids,
            'category'          => $this->category,
            'tags'              => $this->tags,
            'sku'               => $this->sku,
            'stock'             => $this->stock,
            'external_link'     => $this->external_link,
            'featured'          => $this->featured ? 1 : 0,
            'is_active'         => $this->is_active ? 1 : 0,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }

    public static function generateSlug(string $name): string
    {
        $slug = mb_strtolower(trim($name));
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug) ?: $slug;
        $slug = preg_replace('/[^a-z0-9\-]+/', '-', $slug) ?: '';
        return trim($slug, '-');
    }

    public static function isDuplicateSlug(string $slug, ?int $excludeId = null): bool
    {
        if ($slug === '') {
            return false;
        }
        $sql    = 'SELECT 1 FROM products WHERE slug = ?';
        $params = [$slug];
        if ($excludeId !== null) {
            $sql    .= ' AND id != ?';
            $params[] = $excludeId;
        }
        return Database::fetchOne($sql, $params) !== false;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, string>
     */
    public static function validate(array $data, ?int $excludeId = null): array
    {
        $errors = [];

        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '') {
            $errors['name'] = 'O nome do produto é obrigatório.';
        }

        $price = $data['price'] ?? '';
        if ($price === '' || !is_numeric($price) || (float) $price < 0) {
            $errors['price'] = 'O preço deve ser um número válido maior ou igual a zero.';
        }

        $promoPrice = $data['promo_price'] ?? '';
        if ($promoPrice !== '' && $promoPrice !== null) {
            if (!is_numeric($promoPrice) || (float) $promoPrice < 0) {
                $errors['promo_price'] = 'O preço promocional deve ser um número válido.';
            } elseif ($price !== '' && is_numeric($price) && (float) $promoPrice >= (float) $price) {
                $errors['promo_price'] = 'O preço promocional deve ser menor que o preço original.';
            }
        }

        $slug = trim((string) ($data['slug'] ?? ''));
        if ($slug !== '' && !preg_match('/^[a-z0-9\-]+$/', $slug)) {
            $errors['slug'] = 'O slug deve conter apenas letras minúsculas, números e hífens.';
        }
        if ($slug !== '' && self::isDuplicateSlug($slug, $excludeId)) {
            $errors['slug'] = 'Este slug já está em uso por outro produto.';
        }

        return $errors;
    }
}
