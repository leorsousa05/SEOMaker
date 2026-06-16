# Spec Delta: Per-Page Open Graph/Twitter Image

## Current State
- `SeoManager::metaTags()` usa `Config::get('og_image')` para `og:image` e `twitter:image`.
- Não há relação entre `pages` e `media` para imagem social.

## Changes

### ADDED
- Coluna `og_image_id INTEGER` (nullable) na tabela `pages`.
- Propriedade `?int $ogImageId` no modelo `Page`.
- Método `Page::ogImageUrl(): ?string` para resolver URL da imagem.
- Modal de seleção de mídia no formulário de página (reutilizando o existente para blocos).
- Testes em `tests/php/PageOgImageTest.php`.

### MODIFIED
- `src/Core/Seeder.php`: adicionar `ALTER TABLE pages ADD COLUMN og_image_id INTEGER`.
- `src/Models/Page.php`: incluir `og_image_id` e método de resolução.
- `src/Admin/PagesController.php`: persistir `og_image_id`.
- `src/Seo/SeoManager.php`: priorizar imagem da página sobre a global.
- `templates/admin/pages_edit.php`: adicionar campo com botão de seleção de mídia.
- `tests/run.php`: incluir novo teste.

### REMOVED
- Nada removido.

## Migration Notes
- `ALTER TABLE` no `Seeder::run()`.
- Coluna nullable; fallback para configuração global.

## Backward Compatibility
- Total. Páginas sem imagem social específica continuam usando `settings.og_image`.
