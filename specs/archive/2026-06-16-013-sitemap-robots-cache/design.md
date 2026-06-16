# Design: Sitemap.xml and Robots.txt Caching

## Overview
Introduzir uma camada de cache em arquivo para `sitemap.xml` e `robots.txt`. Os geradores continuam produzindo XML/texto, mas o resultado é persistido em disco e reutilizado até ser invalidado por eventos de mutação.

## Proposed Directory & File Structure
```
/home/arch/codes/template-seo/
├── config/
│   └── cache/                      (New directory)
│       ├── sitemap.xml             (Generated)
│       └── robots.txt              (Generated)
├── src/
│   ├── Core/
│   │   ├── Seeder.php              (Modified)
│   │   └── FileCache.php           (New)
│   ├── Seo/
│   │   ├── SitemapGenerator.php    (Modified)
│   │   └── RobotsBuilder.php       (Modified)
│   ├── Admin/
│   │   ├── PagesController.php     (Modified)
│   │   ├── SettingsController.php  (Modified)
│   │   └── RedirectsController.php (Modified)
│   └── Public/
│       └── SiteController.php      (Modified)
├── tests/
│   ├── php/
│   │   └── SitemapRobotsCacheTest.php (New)
│   └── run.php                     (Modified)
└── specs/changes/013-sitemap-robots-cache/
    └── ...
```

## Code Architecture & Design Patterns
- **Cache-Aside Pattern:** controller verifica cache; se miss, gera e armazena.
- **Invalidation on Write:** qualquer mutação relevante limpa o cache.
- **Graceful Degradation:** se escrita falhar, retorna conteúdo gerado dinamicamente.

## Data Model
```php
namespace App\Core;

class FileCache
{
    public static function get(string $key): ?string;
    public static function set(string $key, string $value): bool;
    public static function delete(string $key): bool;
    public static function clear(): bool;
}
```

## API Contracts
```php
// App\Core\FileCache
public static function get(string $key): ?string;
public static function set(string $key, string $value): bool;
public static function delete(string $key): bool;

// App\Seo\SitemapGenerator
public function generate(): string;
public function cachedGenerate(): string;

// App\Seo\RobotsBuilder
public function generate(): string;
public function cachedGenerate(): string;
```

## Flow Diagrams
### Read Flow
1. Requisição chega a `/sitemap.xml`.
2. `SiteController::sitemap()` chama `SitemapGenerator::cachedGenerate()`.
3. `FileCache::get('sitemap.xml')` verifica arquivo.
4. Se existir, retorna; senão, gera e armazena.

### Invalidation Flow
1. Admin salva uma página.
2. `PagesController::save()` chama `FileCache::delete('sitemap.xml')`.
3. Próxima requisição regenera o cache.

## State Management
- Estado persistido em arquivos em `config/cache/`.

## Error Handling
- Falha de escrita: log silencioso, retorna conteúdo dinâmico.
- Arquivo corrompido: regenera se conteúdo vazio.

## Performance Considerations
- Reduz consultas ao SQLite para recursos frequentemente requisitados por crawlers.
- Ideal para sites com dezenas/centenas de páginas.

## Security Considerations
- Cache directory fora de `public/` para evitar acesso direto via web (usar `config/cache/`).
- Sanitizar `key` para evitar path traversal.
