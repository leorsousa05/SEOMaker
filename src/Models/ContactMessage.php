<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class ContactMessage
{
    public int $id;
    public string $name;
    public string $email;
    public ?string $phone;
    public string $message;
    public string $status;
    public ?string $ip;
    public string $createdAt;
    public string $updatedAt;
    
    public static function validate(array $data): array
    {
        $errors = [];
        
        $name = trim($data['name'] ?? '');
        if ($name === '' || strlen($name) < 2) {
            $errors['name'] = 'Nome é obrigatório e deve ter pelo menos 2 caracteres.';
        } elseif (strlen($name) > 100) {
            $errors['name'] = 'Nome deve ter no máximo 100 caracteres.';
        }
        
        $email = trim($data['email'] ?? '');
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Informe um email válido.';
        } elseif (strlen($email) > 255) {
            $errors['email'] = 'Email deve ter no máximo 255 caracteres.';
        }
        
        $phone = trim($data['phone'] ?? '');
        if ($phone !== '' && strlen($phone) > 30) {
            $errors['phone'] = 'Telefone deve ter no máximo 30 caracteres.';
        }
        
        $message = trim($data['message'] ?? '');
        if ($message === '' || strlen($message) < 10) {
            $errors['message'] = 'Mensagem é obrigatória e deve ter pelo menos 10 caracteres.';
        } elseif (strlen($message) > 5000) {
            $errors['message'] = 'Mensagem deve ter no máximo 5000 caracteres.';
        }
        
        return $errors;
    }
    
    public static function isRateLimited(): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        $last = $_SESSION['last_contact_time'] ?? 0;
        if ($last > 0 && (time() - $last) < 60) {
            return true;
        }
        
        return false;
    }
    
    public static function markSent(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        $_SESSION['last_contact_time'] = time();
    }
    
    public static function create(array $data): int
    {
        $now = date('Y-m-d H:i:s');
        
        return Database::insert('contact_messages', [
            'name' => substr(trim($data['name']), 0, 100),
            'email' => substr(trim($data['email']), 0, 255),
            'phone' => isset($data['phone']) ? substr(trim($data['phone']), 0, 30) : null,
            'message' => trim($data['message']),
            'status' => 'new',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
    
    public static function countByStatus(string $status): int
    {
        $row = Database::fetchOne('SELECT COUNT(*) as count FROM contact_messages WHERE status = ?', [$status]);
        return (int) ($row['count'] ?? 0);
    }
    
    public static function findAll(string $orderBy = 'created_at DESC'): array
    {
        return Database::fetchAll("SELECT * FROM contact_messages ORDER BY {$orderBy}");
    }
    
    public static function updateStatus(int $id, string $status): bool
    {
        Database::update('contact_messages', [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$id]);
        return true;
    }
    
    public static function delete(int $id): bool
    {
        Database::delete('contact_messages', 'id = ?', [$id]);
        return true;
    }
}
