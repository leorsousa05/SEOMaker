# Design — Parte 5

## Architecture
Pequenas melhorias no domínio SEO e no domínio Page/Admin. Sem novas tabelas.

## Core Components

### `App\Seo\SeoManager`
- `metaTags()`: já gera canonical e Twitter Cards básicos; adicionar `twitter:image`

### `App\Models\Page`
- `fromArray()` / `toArray()`: sem mudanças
- `generateSlug(string $title): string` — normaliza e cria slug
- `isDuplicateSlug(string $slug, ?int $excludeId): bool` — checa duplicidade
- `validate(array $data, ?int $excludeId): array` — retorna erros por campo

### `App\Admin\PagesController::save()`
- Aplica `Page::generateSlug()` se slug vazio
- Valida via `Page::validate()`
- Em caso de erro, redireciona de volta ao form com flash de erros e dados

### `templates/admin/pages_edit.php`
- Exibe erros de validação por campo
- Checkbox para "gerar slug automaticamente"

## Routes/API
Sem novas rotas públicas.

## Data Model
Sem mudanças no schema. Slug continua `TEXT UNIQUE`.
