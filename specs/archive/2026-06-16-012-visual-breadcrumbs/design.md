# Design: Visual Breadcrumbs + Schema

## Overview
Criar um partial reutilizável de breadcrumbs que consuma a mesma fonte de dados usada pelo schema JSON-LD. O componente será renderizado abaixo do header em todas as páginas públicas, exceto homepage.

## Proposed Directory & File Structure
```
/home/arch/codes/template-seo/
├── src/
│   └── Seo/
│       └── SeoManager.php          (Modified)
├── templates/
│   └── public/
│       ├── layout.php              (Modified)
│       └── partials/
│           └── _breadcrumbs.php    (New)
├── public/
│   └── assets/
│       └── style.css               (Modified)
├── tests/
│   ├── php/
│   │   └── BreadcrumbTemplateTest.php (New)
│   └── run.php                     (Modified)
└── specs/changes/012-visual-breadcrumbs/
    └── ...
```

## Code Architecture & Design Patterns
- **Shared Source Pattern:** visual e schema leem do mesmo helper.
- **Partial Pattern:** template isolado para reutilização.

## Data Model
```php
// Itens de breadcrumb retornados por SeoManager::breadcrumbItems()
[
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Título da Página', 'url' => '/page/slug'],
]
```

## API Contracts
```php
// App\Seo\SeoManager
public static function breadcrumbItems(Page $page): array;
public static function breadcrumbSchema(Page $page): string;
```

## Flow Diagrams
### Render Flow
1. `layout.php` carrega `templates/public/partials/_breadcrumbs.php`.
2. O partial chama `SeoManager::breadcrumbItems($page)`.
3. Itera itens, renderizando `<nav><ol><li>...</li></ol></nav>`.
4. `SeoManager::breadcrumbSchema()` usa `breadcrumbItems()` para gerar JSON-LD.

## State Management
- Nenhum estado; dados derivados do modelo `Page` e configurações.

## Error Handling
- Página nula: não renderizar breadcrumbs.
- Homepage: não renderizar breadcrumbs.

## Performance Considerations
- Renderização trivial.

## Security Considerations
- Escapar URLs e textos com `htmlspecialchars()`.

## UI/UX Design Specification

### Aesthetic Direction
Breadcrumb minimalista, discreto, posicionado abaixo do header fixo. Tom de texto secundário (`--color-text-secondary`) com separador sutil.

### Layout
```
<main>
  <nav class="breadcrumb" aria-label="Navegação">
    <ol class="breadcrumb__list">
      <li class="breadcrumb__item"><a href="/">Início</a></li>
      <li class="breadcrumb__separator" aria-hidden="true">/</li>
      <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">Título</li>
    </ol>
  </nav>
  <div class="page-content">...</div>
</main>
```

### Component Spec
- Container com padding vertical 0.75rem, horizontal do container (1.5rem).
- Fonte: Inter 0.875rem, weight 500.
- Links: cor `--color-text-secondary`, hover `--color-primary`.
- Item atual: cor `--color-text`, weight 600.
- Separador: `/` em `--color-text-muted`.

### Accessibility
- `nav` com `aria-label="Navegação"`.
- `ol`/`li` para semântica de lista ordenada.
- Último item com `aria-current="page"`.
- Skip breadcrumb para screen readers via `aria-label`.

### Dark Mode
- Texto secundário: `--color-text-secondary` (dark: `#cbd5e1`).
- Hover: `--color-primary-light`.

### Motion
- Hover nos links: transição de cor 150ms.
- Sem animação de entrada (evitar CLS).

### Responsive
- Em mobile, truncar item atual com `text-overflow: ellipsis` se exceder 60% da largura.
- Permitir scroll horizontal como fallback, mas sem scrollbar visível.
