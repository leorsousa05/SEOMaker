# Spec Delta: Manual Canonical URL per Page

## Current State
- `SeoManager::metaTags()` gera a tag `<link rel="canonical">` sempre a partir do slug da página.
- Não há campo no admin para sobrescrever esse valor.

## Changes

### ADDED
- Coluna `canonical_url TEXT` (nullable) na tabela `pages`.
- Propriedade `string $canonicalUrl` no modelo `App\Models\Page`.
- Campo de texto na aba SEO de `templates/admin/pages_edit.php`.
- Testes em `tests/php/PageCanonicalTest.php`.

### MODIFIED
- `src/Core/Seeder.php`: adicionar `ALTER TABLE pages ADD COLUMN canonical_url TEXT`.
- `src/Models/Page.php`: incluir `canonical_url` em `fromArray()`, `toArray()` e sanitização.
- `src/Admin/PagesController.php`: persistir `canonical_url`.
- `src/Seo/SeoManager.php`: priorizar `$page->canonicalUrl` sobre a geração automática.
- `tests/run.php`: incluir novo teste.

### REMOVED
- Nada removido.

## Migration Notes
- `ALTER TABLE` dentro de `try/catch` no `Seeder::run()`.
- Coluna nullable; páginas existentes terão `canonical_url = NULL` e usarão fallback automático.

## Backward Compatibility
- Total. Fallback automático preserva URLs canônicas atuais.
