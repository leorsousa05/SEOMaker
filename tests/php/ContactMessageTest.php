<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Models\ContactMessage;

function assertContact(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// Validation tests
$errors = ContactMessage::validate([]);
assertContact(isset($errors['name']), 'validation requires name');
assertContact(isset($errors['email']), 'validation requires email');
assertContact(isset($errors['message']), 'validation requires message');

$errors = ContactMessage::validate([
    'name' => 'J',
    'email' => 'invalid',
    'message' => 'hi',
]);
assertContact(isset($errors['name']), 'short name rejected');
assertContact(isset($errors['email']), 'invalid email rejected');
assertContact(isset($errors['message']), 'short message rejected');

$errors = ContactMessage::validate([
    'name' => 'João Silva',
    'email' => 'joao@example.com',
    'phone' => '',
    'message' => 'Esta é uma mensagem de teste com mais de dez caracteres.',
]);
assertContact(empty($errors), 'valid data passes');

$errors = ContactMessage::validate([
    'name' => 'João',
    'email' => 'joao@example.com',
    'phone' => '11999999999',
    'message' => 'Mensagem válida para teste do formulário de contato.',
]);
assertContact(!isset($errors['phone']), 'optional phone accepted');

// Rate limit tests
$_SESSION['last_contact_time'] = time();
assertContact(ContactMessage::isRateLimited(), 'rate limit blocks recent send');

$_SESSION['last_contact_time'] = time() - 120;
assertContact(!ContactMessage::isRateLimited(), 'rate limit allows after delay');

unset($_SESSION['last_contact_time']);
