# Design: Page-Level Robots Control

## Overview
Adicionar um campo `meta_robots` ao modelo `Page` e renderizá-lo como `<meta name="robots" content="...">` no layout público. O campo é editado na aba SEO do admin por meio de checkboxes ou select múltiplo para `index`/`noindex` e `follow`/`nofollow`.

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
│   │   └── PageRobotsTest.php      (New)
│   └── run.php                     (Modified)
└── specs/
    └── changes/007-page-robots-control/
        ├── .spec.yaml
        ├── proposal.md
        ├── specs/spec.md
        ├── design.md
        └── tasks.md
```

## Code Architecture & Design Patterns
- **MVC existente:** o modelo `Page` carrega/persiste o dado; o controller sanitiza o input; `SeoManager` renderiza a saída.
- **Default Object Pattern:** `Page::defaultMetaRobots()` centraliza o valor padrão `index, follow`.
- **Whitelist validation:** `PagesController::save()` aceita apenas combinações conhecidas de diretivas.

## Data Model
```php
namespace App\Models;

class Page
{
    public ?int $id = null;
    public string $slug = '';
    public string $title = '';
    public string $metaTitle = '';
    public string $metaDescription = '';
    public string $metaRobots = ''; // NEW
    // ... existing fields

    public static function defaultMetaRobots(): string
    {
        return 'index, follow';
    }
}
```

## API Contracts
```php
// App\Models\Page
public static function fromArray(array $data): self;
public function toArray(): array;
public function validate(): array;
public static function defaultMetaRobots(): string;

// App\Seo\SeoManager
public static function metaTags(Page $page): string;
```

## Flow Diagrams
### Admin Save Flow
1. Admin submite formulário com `meta_robots[] = ['noindex', 'nofollow']`.
2. `PagesController::save()` junta os valores em uma string sanitizada.
3. `Page::validate()` aceita apenas tokens da whitelist.
4. Banco é atualizado com `meta_robots`.

### Public Render Flow
1. `templates/public/layout.php` chama `SeoManager::metaTags($page)`.
2. `SeoManager` lê `$page->metaRobots ?: Page::defaultMetaRobots()`.
3. String é escapada com `htmlspecialchars()` e inserida no HTML.

## State Management
- Estado persistido na coluna `pages.meta_robots` (TEXT).
- Nenhum estado global ou cache necessário.

## Error Handling
- Valores inválidos de `meta_robots` são ignorados/removidos na sanitização.
- Falha no `ALTER TABLE` é capturada no `Seeder::run()`.

## Performance Considerations
- Operação trivial; sem impacto mensurável.

## Security Considerations
- Escapar output com `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- Sanitizar input para evitar injeção de atributos HTML no campo.

## UI/UX Design Specification

### Aesthetic Direction
Manter a estética atual do admin: Vercel-like, limpo, com bordas sutis e acento emerald. O novo campo deve parecer nativo na aba SEO.

### Layout
Inserir o grupo "Indexação" após o campo "Descrição para o Google" e antes de "Tipo de Informação".

```
[form-group]
  label: Indexação do Google
  [row]
    [checkbox] index / noindex
    [checkbox] follow / nofollow
  help-text: "Escolha se os motores de busca devem indexar esta página e seguir os links."
```

### Component Spec
- Dois pares de botões estilo segmented control ou dois selects simples.
- Valor padrão visual: `index` + `follow` (estado ativo discreto).
- Estado `noindex`: botão/label fica com tom âmbar (`--accent-amber`) e ícone de olho riscado (SVG inline).
- Focus state: anel emerald de 2px (`box-shadow: 0 0 0 2px rgba(16,185,129,0.3)`).

### Accessibility
- Fieldset com `legend="Indexação"`.
- Inputs com `role="switch"` ou checkbox nativo com label explícita.
- Ícones decorativos com `aria-hidden="true"`.

### Dark Mode
- Fundo do segmented control: `--bg-muted`.
- Opção ativa: `--color-primary-soft` no light, `rgba(16,185,129,0.2)` no dark.

### Motion
- Transição de cor em 150ms ease ao trocar estado.
- Sem animação em dispositivos com `prefers-reduced-motion`.

### Responsive
- Em telas < 480px, empilhar verticalmente os dois controles.
