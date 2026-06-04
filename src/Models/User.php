<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    public ?int $id = null;
    public string $username = '';
    public string $password_hash = '';
    public ?string $created_at = null;
    
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $user = new self();
        $user->id = isset($data['id']) ? (int) $data['id'] : null;
        $user->username = (string) ($data['username'] ?? '');
        $user->password_hash = (string) ($data['password_hash'] ?? '');
        $user->created_at = $data['created_at'] ?? null;
        return $user;
    }
}
