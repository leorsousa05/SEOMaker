# Design: Manual Canonical URL per Page

## Overview
Inserir um campo opcional `canonical_url` no modelo `Page` e modificá-lo no admin. No front-end, `SeoManager::metaTags()` usa o valor manual quando presente; caso contrário, mantém a geração automática baseada no slug.

## Proposed Directory & File Structure
```
/home/arch/codes/template-seo/
├── src/
│   ├── Core/
│   │   └── Seeder.php              (Modified)
│   ├── Models/
│   │   └── Page.php                (Modified)
│   ├── Admin/
│   │   └── PagesController.php     (Modified)
│   └── Seo/
│       └── SeoManager.php          (Modified)
├── templates/
│   └── admin/
│       └── pages_edit.php          (Modified)
├── tests/
│   ├── php/
│   │   └── PageCanonicalTest.php   (New)
│   └── run.php                     (Modified)
└── specs/changes/008-page-canonical-url/
    └── ...
```

## Code Architecture & Design Patterns
- **Fallback Pattern:** manual > automático.
- **Sanitization:** input trim e escape de output.

## Data Model
```php
class Page
{
    public ?int $id = null;
    public string $slug = '';
    public string $title = '';
    public string $metaTitle = '';
    public string $metaDescription = '';
    public string $metaRobots = '';
    public ?string $canonicalUrl = null; // NEW
    // ... existing fields
}
```

## API Contracts
```php
// App\Models\Page
public static function fromArray(array $data): self;
public function toArray(): array;

// App\Seo\SeoManager
public static function metaTags(Page $page): string;
```

## Flow Diagrams
### Save Flow
1. Admin preenche `canonical_url`.
2. `PagesController::save()` faz trim e valida formato básico de URL.
3. Valor é salvo no banco.

### Render Flow
1. `SeoManager::metaTags($page)` verifica `$page->canonicalUrl`.
2. Se não vazio, usa-o; senão, gera a partir do slug.
3. Output escapado com `htmlspecialchars()`.

## State Management
- Coluna `pages.canonical_url` (TEXT, nullable).

## Error Handling
- Valor inválido é rejeitado na validação do modelo.

## Performance Considerations
- Sem impacto.

## Security Considerations
- Escapar output.
- Rejeitar `javascript:` e esquemas perigosos.

## UI/UX Design Specification

### Aesthetic Direction
Campo de texto simples, alinhado aos inputs existentes da aba SEO (bordas `var(--border-input)`, raios 8px).

### Layout
Inserir após o campo "Indexação" (spec 007) ou após "Descrição para o Google" se 007 ainda não estiver implementado.

```
[form-group]
  label: URL Canônica
  input[type=url] placeholder="https://exemplo.com/pagina"
  help-text: "Deixe em branco para usar a URL automática desta página."
```

### Component Spec
- Input `type="url"` para teclado correto em mobile.
- Placeholder cinza (`--text-muted`).
- Ícone opcional de link (chain) à esquerda, dentro do input.
- Borda foco emerald.

### Accessibility
- Label `for` vinculado ao input.
- Mensagem de erro abaixo do campo com `role="alert"`.

### Dark Mode
- Input background: `--bg-input`.
- Texto: `--text-primary`.
- Placeholder: `--text-muted`.

### Motion
- Transição de borda em 150ms ao focar.

### Responsive
- Largura total do container em todos os breakpoints.
