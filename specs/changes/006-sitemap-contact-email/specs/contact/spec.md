# Contact Form & Email Spec

## Model
```php
namespace App\Models;

class ContactMessage
{
    public int $id;
    public string $name;
    public string $email;
    public ?string $phone;
    public string $message;
    public string $status; // new | replied | archived
    public ?string $ip;
    public string $createdAt;
    public string $updatedAt;
    
    public static function validate(array $data): array; // retorna erros
    public static function create(array $data): int;
    public static function sendNotification(array $data): bool;
}
```

## Validação

1. `name` obrigatório, min 2 chars, max 100
2. `email` obrigatório, formato válido, max 255
3. `phone` opcional, max 30
4. `message` obrigatório, min 10 chars, max 5000
5. Retorna array associativo: `['field' => 'mensagem']` ou vazio

## Rate Limit

1. Verifica `$_SESSION['last_contact_time']`
2. Se existir e < 60 segundos atrás, rejeita com erro genérico
3. Se aceito, atualiza `$_SESSION['last_contact_time'] = time()`

## Envio de Email

1. Usa PHPMailer quando SMTP configurado
2. Fallback para `mail()` quando SMTP não configurado
3. Configurações:
   - From: `mail_from` / `mail_from_name`
   - To: `contact_email`
   - Subject: `Nova mensagem de {name} — {site_title}`
4. Corpo em texto simples contendo todos os campos
5. Retorna `true` em caso de sucesso, `false` em falha

## Controller

```php
POST /contact
```

1. Recebe POST com name, email, phone, message
2. Valida
3. Verifica rate limit
4. Salva no banco
5. Envia email
6. Redireciona para `/?contact=sent` (ou página origem)
7. Em erro: redireciona para `/?contact=error&message=...`

## Admin

1. Nova rota `GET /admin/messages`
2. Lista mensagens ordenadas por `created_at DESC`
3. Ações:
   - `GET /admin/messages/reply/{id}` → status `replied`
   - `GET /admin/messages/archive/{id}` → status `archived`
   - `GET /admin/messages/delete/{id}` → remove
4. Badge no sidebar mostra contagem de `new`

## Testes
- `testValidationRequiresNameEmailMessage()`
- `testValidationAcceptsOptionalPhone()`
- `testValidationRejectsInvalidEmail()`
- `testRateLimitBlocksFastRequests()`
- `testRateLimitAllowsAfterDelay()`
- `testSendEmailUsesSmtpConfig()`
