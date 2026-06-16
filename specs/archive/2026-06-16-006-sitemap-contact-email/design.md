# Design Decisions — Parte 4

## Arquitetura

### Sitemap
- Classe `App\Seo\SitemapBuilder`
  - `generate(): string` — retorna XML completo
  - Usa `Config::get('site_url')` como base das URLs
  - Busca páginas ativas no banco
  - Escapa URLs corretamente

### Robots
- Classe `App\Seo\RobotsBuilder`
  - `generate(): string` — retorna conteúdo robots.txt
  - Se `robots_txt_custom` preenchido, retorna tal qual
  - Senão gera padrão + Sitemap

### Contact
- Classe `App\Content\ContactManager`
  - `save(array $data): int` — insere no banco
  - `sendEmail(array $data): bool` — envia via SMTP
  - `validate(array $data): array` — retorna erros
- Controller `App\Site\ContactController` para POST /contact
- PHPMailer via Composer (única dependência externa)

### Banco
```sql
CREATE TABLE contact_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT,
    message TEXT NOT NULL,
    status TEXT DEFAULT 'new', -- new, replied, archived
    ip TEXT,
    created_at TEXT,
    updated_at TEXT
);
```

## Rate Limiting
- Simples: salva último envio por IP em sessão PHP
- Se < 60s desde último envio, rejeita
- Bom o suficiente para evitar spam básico

## UX
- Formulário renderizado via block `contact_form` no editor
- Ou shortcode simples para inserir em qualquer página
- Feedback via query string `?contact=sent` ou `?contact=error`
- Toast message no frontend

## Email
- Usa PHPMailer + configurações SMTP salvas
- Fallback: `mail()` nativo se SMTP não configurado
- Corpo do email simples em português
