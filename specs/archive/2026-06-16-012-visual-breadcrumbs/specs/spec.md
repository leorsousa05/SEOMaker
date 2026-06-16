# Spec Delta: Visual Breadcrumbs + Schema

## Current State
- `SeoManager::breadcrumbSchema()` gera JSON-LD `BreadcrumbList`.
- Não há representação visual de breadcrumbs nos templates públicos.

## Changes

### ADDED
- Partial `templates/public/partials/_breadcrumbs.php`.
- Função helper `SeoManager::breadcrumbItems(Page $page): array`.
- Estilos CSS em `public/assets/style.css`.
- Testes em `tests/php/BreadcrumbTemplateTest.php`.

### MODIFIED
- `src/Seo/SeoManager.php`: refatorar `breadcrumbSchema()` para usar `breadcrumbItems()`.
- `templates/public/layout.php`: incluir partial de breadcrumbs após `<header>`.
- `public/assets/style.css`: adicionar classes `.breadcrumb`, `.breadcrumb__list`, `.breadcrumb__item`, `.breadcrumb__link`, `.breadcrumb__current`.
- `tests/run.php`: incluir novo teste.

### REMOVED
- Nada removido.

## Migration Notes
- Nenhuma alteração de banco.

## Backward Compatibility
- Total. Adição pura de UI; schema existente mantido.
