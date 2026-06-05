<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Redirect
{
    public ?int $id = null;
    public string $from_path = '';
    public string $to_path = '';
    public string $type = '301';
    public bool $is_active = true;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $r = new self();
        $r->id = isset($data['id']) ? (int) $data['id'] : null;
        $r->from_path = (string) ($data['from_path'] ?? '');
        $r->to_path = (string) ($data['to_path'] ?? '');
        $r->type = (string) ($data['type'] ?? '301');
        $r->is_active = (bool) ($data['is_active'] ?? true);
        $r->created_at = $data['created_at'] ?? null;
        $r->updated_at = $data['updated_at'] ?? null;
        return $r;
    }
    
    public static function findByPath(string $path): ?array
    {
        $row = Database::fetchOne(
            'SELECT * FROM redirects WHERE from_path = ? AND is_active = 1 LIMIT 1',
            [$path]
        );
        return $row ?: null;
    }
    
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function findAll(string $orderBy = 'created_at DESC'): array
    {
        return Database::fetchAll("SELECT * FROM redirects ORDER BY {$orderBy}");
    }
    
    public static function isDuplicate(string $fromPath, ?int $excludeId = null): bool
    {
        $sql = 'SELECT 1 FROM redirects WHERE from_path = ?';
        $params = [$fromPath];
        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
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
        $from = trim((string) ($data['from_path'] ?? ''));
        $to = trim((string) ($data['to_path'] ?? ''));
        
        if ($from === '') {
            $errors['from_path'] = 'O caminho de origem é obrigatório.';
        } elseif ($from === $to) {
            $errors['from_path'] = 'Origem e destino não podem ser iguais.';
        } elseif (self::isDuplicate($from, $excludeId)) {
            $errors['from_path'] = 'Já existe um redirect para este caminho.';
        }
        
        if ($to === '') {
            $errors['to_path'] = 'O caminho de destino é obrigatório.';
        }
        
        return $errors;
    }
    
    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::insert('redirects', $data);
    }
    
    /**
     * @param array<string, mixed> $data
     */
    public static function update(int $id, array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        Database::update('redirects', $data, 'id = ?', [$id]);
        return true;
    }
    
    public static function delete(int $id): bool
    {
        Database::delete('redirects', 'id = ?', [$id]);
        return true;
    }
}
