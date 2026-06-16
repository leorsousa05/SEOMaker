# Spec Delta: Auto FAQPage Schema from FAQ Block

## Current State
- Bloco FAQ existe em `src/Content/BlockEditor.php` e `public/assets/block-editor.js`.
- Renderiza HTML sem schema estruturado.
- `SchemaFormBuilder` suporta `FAQPage` manual, mas não é automaticamente derivado do bloco.

## Changes

### ADDED
- Método `SeoManager::faqSchema(Page $page): ?array` que parseia `content_blocks`.
- Método privado `SeoManager::extractFaqItems(array $blocks): array`.
- Injeção do schema na saída de `SeoManager::schemaJsonLd()` ou novo método dedicado.
- Testes em `tests/php/FaqSchemaTest.php`.

### MODIFIED
- `src/Seo/SeoManager.php`: adicionar lógica de extração e renderização de FAQPage.
- `templates/public/layout.php`: chamar novo método de schema FAQ junto aos outros schemas.
- `tests/run.php`: incluir novo teste.

### REMOVED
- Nada removido.

## Migration Notes
- Nenhuma alteração de banco. A fonte de dados é `pages.content_blocks` (JSON).

## Backward Compatibility
- Total. Páginas sem bloco FAQ mantêm comportamento atual.
