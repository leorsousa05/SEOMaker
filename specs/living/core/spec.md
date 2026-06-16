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

### Core\CanonicalRedirect
- Decide se a requisição atual deve ser redirecionada 301 para a versão canônica.
- Configurações: `canonical_host` (`www` | `non-www` | `auto`) e `force_trailing_slash` (`1` | `0` | `auto`).
- Executado em `public/index.php` antes do routing e da verificação de redirects.
- Preserva query string e fragment; ignora arquivos com extensão e path raiz para trailing slash.

### Core\FileCache
- Cache de arquivo simples em `config/cache/`.
- Métodos: `get`, `set`, `delete`, `clear`.
- Usado por `SitemapGenerator` e `RobotsBuilder` para cachear recursos de SEO.
