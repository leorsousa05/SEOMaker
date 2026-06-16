# Design: Per-Page Open Graph/Twitter Image

## Overview
Adicionar uma chave estrangeira `og_image_id` na tabela `pages` apontando para `media.id`. No admin, o usuário seleciona uma imagem da galeria existente. No front-end, `SeoManager` resolve a URL da imagem e a injeta nas meta tags sociais, com fallback para a imagem global.

## Proposed Directory & File Structure
```
/home/arch/codes/template-seo/
├── src/
│   ├── Core/
│   │   └── Seeder.php              (Modified)
│   ├── Models/
│   │   ├── Page.php                (Modified)
│   │   └── Media.php               (Modified - optional helper)
│   ├── Admin/
│   │   └── PagesController.php     (Modified)
│   └── Seo/
│       └── SeoManager.php          (Modified)
├── templates/
│   └── admin/
│       └── pages_edit.php          (Modified)
├── tests/
│   ├── php/
│   │   └── PageOgImageTest.php     (New)
│   └── run.php                     (Modified)
└── specs/changes/009-page-og-image/
    └── ...
```

## Code Architecture & Design Patterns
- **Foreign Key Pattern:** `pages.og_image_id -> media.id`.
- **Resolver Pattern:** `Page::ogImageUrl()` encapsula a lógica de busca.
- **Fallback Pattern:** página > configuração global.

## Data Model
```php
class Page
{
    public ?int $id = null;
    public ?int $ogImageId = null; // NEW
    // ... existing fields

    public function ogImageUrl(): ?string
    {
        if (!$this->ogImageId) {
            return null;
        }
        $media = Media::find($this->ogImageId);
        return $media ? $media->url() : null;
    }
}
```

## API Contracts
```php
// App\Models\Page
public static function fromArray(array $data): self;
public function toArray(): array;
public function ogImageUrl(): ?string;

// App\Seo\SeoManager
public static function metaTags(Page $page): string;
```

## Flow Diagrams
### Admin Selection Flow
1. Usuário clica "Selecionar imagem" na aba SEO.
2. Modal de mídia reutiliza endpoint `/admin/media/json`.
3. ID selecionado é armazenado em input hidden `og_image_id`.
4. `PagesController::save()` persiste o ID.

### Public Render Flow
1. `SeoManager::metaTags($page)` chama `$page->ogImageUrl()`.
2. Se retornar URL, usa-a; senão, usa `Config::get('og_image')`.
3. URL absoluta é montada com `site_url` e inserida em `og:image` e `twitter:image`.

## State Management
- `pages.og_image_id` (INTEGER, nullable).

## Error Handling
- Mídia inexistente: fallback para imagem global.
- Valor inválido: sanitizado para int ou null.

## Performance Considerations
- Uma query adicional por página para resolver mídia; aceitável para template.

## Security Considerations
- Escapar URL no output.
- Validar que `og_image_id` pertence a registro existente em `media`.

## UI/UX Design Specification

### Aesthetic Direction
Componente de seleção de mídia clean, reutilizando o modal de mídia existente do block editor.

### Layout
Inserir após o campo "URL Canônica" na aba SEO.

```
[form-group]
  label: Imagem de compartilhamento social
  [media-picker]
    [preview-thumbnail or placeholder icon]
    [btn btn-ghost] Selecionar imagem
    [btn btn-danger-sm] Remover (aparece quando há imagem)
  input[type=hidden] name="og_image_id"
  help-text: "Recomendado: 1200×630px. Usada no Facebook, WhatsApp e Twitter."
```

### Component Spec
- Placeholder: retângulo tracejado (`border: 2px dashed var(--border)`) com ícone de imagem centralizado.
- Thumbnail: imagem 120×63px com object-fit cover e radius 8px.
- Botões pequenos ao lado (altura 32px, padding horizontal 12px).
- Modal de mídia existente (`#media-modal-overlay`) reutilizado; apenas o callback de seleção muda.

### Accessibility
- Botão "Selecionar imagem" com `aria-describedby` apontando para help-text.
- Thumbnail com `alt=""` (decoração) ou `alt="Imagem social selecionada"`.
- Estado vazio anunciado por texto visível.

### Dark Mode
- Placeholder dashed border: `--border-light`.
- Background do placeholder: `--bg-muted`.

### Motion
- Preview aparece com fade-in suave (150ms) ao selecionar.
- Botão "Remover" surge com transição de opacidade.

### Responsive
- Em mobile, preview e botões empilham verticalmente.
