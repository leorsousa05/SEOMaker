# Design — Parte 6

## Architecture
- `SeoManager::breadcrumbSchema(Page $page): string` gera JSON-LD
- `App\Models\Redirect` — CRUD e lookup
- `App\Admin\RedirectsController` — admin CRUD
- Intercept no `public/index.php` antes do `$router->dispatch()`

## Core Components

### `App\Seo\SeoManager`
- `breadcrumbSchema(Page $page): string` — BreadcrumbList com 2 itens

### `App\Models\Redirect`
- `findByPath(string $path): ?array`
- `validate(array $data): array`
- `create(array $data): int`
- `update(int $id, array $data): bool`
- `delete(int $id): bool`
- `findAll(): array`

### `App\Admin\RedirectsController`
- `index()` — lista com filtros
- `save()` — create/update
- `delete()` — remove

### `public/index.php`
- Antes do dispatch: checa se request path tem redirect ativo
- Se sim: header 301 + Location

## Routes
```
GET  /admin/redirects         → RedirectsController::index
POST /admin/redirects/save    → RedirectsController::save
GET  /admin/redirects/delete/{id} → RedirectsController::delete
```

## Data Model
```sql
CREATE TABLE redirects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    from_path TEXT NOT NULL UNIQUE,
    to_path TEXT NOT NULL,
    type TEXT DEFAULT '301',
    is_active INTEGER DEFAULT 1,
    created_at TEXT,
    updated_at TEXT
)
```
