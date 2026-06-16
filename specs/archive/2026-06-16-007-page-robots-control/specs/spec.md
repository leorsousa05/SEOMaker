# Spec Delta: Page-Level Robots Control

## Current State
- A tabela `pages` não possui coluna para diretivas de robots.
- `SeoManager::metaTags()` renderiza title, description, canonical, Open Graph e Twitter Cards, mas nenhuma meta tag `robots`.
- Não há UI no admin para controlar indexação.

## Changes

### ADDED
- Coluna `meta_robots TEXT DEFAULT 'index, follow'` na tabela `pages`.
- Propriedade `string $metaRobots` no modelo `App\Models\Page`.
- Método auxiliar `Page::defaultMetaRobots(): string` retornando `'index, follow'`.
- Renderização da tag `<meta name="robots" content="...">` em `SeoManager::metaTags()`.
- Campo de seleção múltipla/checkbox na aba SEO de `templates/admin/pages_edit.php`.
- Testes em `tests/php/PageRobotsTest.php`.

### MODIFIED
- `src/Core/Seeder.php`: adicionar `ALTER TABLE pages ADD COLUMN meta_robots TEXT DEFAULT 'index, follow'` na seção de migrations.
- `src/Models/Page.php`: incluir `meta_robots` em `fromArray()`, `toArray()` e `validate()`.
- `src/Admin/PagesController.php`: persistir `meta_robots` no array `$data` enviado ao banco.
- `src/Seo/SeoManager.php`: concatenar a meta tag robots à string de meta tags.
- `tests/run.php`: incluir `tests/php/PageRobotsTest.php`.

### REMOVED
- Nada removido.

## Migration Notes
- O `Seeder::run()` executa `ALTER TABLE` dentro de `try/catch`. Se a coluna já existir, o erro é ignorado.
- Para páginas existentes, o default `index, follow` garante comportamento inalterado.

## Backward Compatibility
- Totalmente retrocompatível. Páginas sem valor explícito assumem `index, follow`.
