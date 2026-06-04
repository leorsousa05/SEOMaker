# Spec: Core Infrastructure

## ADDED

### Core\Router
- Map HTTP methods to handlers
- Parse URL params (/page/{slug})
- Call handler with params array
- 404/405 handlers

### Core\Database
- PDO wrapper for SQLite
- Query builder simples (select, insert, update, delete)
- Prepared statements obrigatórios

### Core\Config
- Carrega settings do banco na inicialização
- get(string $key, mixed $default): mixed
- set(string $key, mixed $value): void (persiste no banco)

### Core\Mailer
- Config via settings (host, port, user, pass, from)
- send() retorna bool
- Suporte a template simples (str_replace)

### Core\View
- Render PHP templates com extract()
- Layout system (yield/content)
