# Design: Lazy Loading and Required Alt Text for Images

## Overview
Modificar o `BlockEditor` para renderizar todas as imagens com estratégia de loading adequada e exigir texto alternativo nos blocos de imagem e galeria. A primeira imagem da página usa `loading="eager"`; as demais, `loading="lazy"`.

## Proposed Directory & File Structure
```
/home/arch/codes/template-seo/
├── src/
│   ├── Content/
│   │   └── BlockEditor.php         (Modified)
│   └── Admin/
│       └── PagesController.php     (Modified)
├── templates/
│   └── admin/
│       └── pages_edit.php          (Modified)
├── public/
│   └── assets/
│       └── block-editor.js         (Modified)
├── tests/
│   ├── php/
│   │   └── ImageLazyAltTest.php    (New)
│   └── run.php                     (Modified)
└── specs/changes/011-image-lazy-alt/
    └── ...
```

## Code Architecture & Design Patterns
- **Validation at boundary:** validação no client-side e reforço no server-side.
- **Counter Pattern:** tracking da primeira imagem para decidir eager vs lazy.

## Data Model
```php
class BlockEditor
{
    private static int $imageCounter = 0;

    public static function renderBlock(array $block): string;
    public static function validateBlocks(array $blocks): array; // NEW
    public static function requiresAltText(string $blockType): bool; // NEW
}
```

## API Contracts
```php
// App\Content\BlockEditor
public static function render(array $blocks): string;
public static function renderBlock(array $block): string;
public static function validateBlocks(array $blocks): array;

// App\Admin\PagesController
public function save(): void;
```

## Flow Diagrams
### Save Flow
1. Admin preenche blocos; JS valida alt antes de submit.
2. `PagesController::save()` decoda JSON e chama `BlockEditor::validateBlocks()`.
3. Se houver erros, retorna ao formulário com mensagens.
4. Se válido, persiste no banco.

### Render Flow
1. `BlockEditor::render()` itera blocos.
2. Para cada bloco `image`/`gallery`, incrementa contador de imagens.
3. Primeira imagem: `loading="eager"`; demais: `loading="lazy"`.
4. `alt` é escapado e inserido.

## State Management
- Contador estático `$imageCounter` no renderizador (stateless entre requisições).

## Error Handling
- Erros de validação retornam array associativo: `['blockIndex' => 'Mensagem']`.
- Mensagens em português.

## Performance Considerations
- Lazy loading reduz LCP e melhora CWV.
- Primeira imagem eager garante LCP não degradado.

## Security Considerations
- Escapar `alt` com `htmlspecialchars()`.
- Rejeitar `alt` vazio para evitar imagens inacessíveis.

## UI/UX Design Specification

### Aesthetic Direction
Estados de erro discretos, usando a cor danger (`--accent-red`) do admin. Nada de alertas agressivos — mensagens inline ao lado do campo.

### Layout
No block editor, cada bloco de imagem e galeria já possui campo "Texto alternativo". Marcar esse campo como obrigatório visualmente.

```
[image-block-editor]
  label: Texto alternativo *
  input[name=alt]
  [error-text] "Descreva a imagem para acessibilidade."
  help-text: "Obrigatório. Ex: 'Fachada da loja em São Paulo'."
```

### Component Spec
- Label com asterisco vermelho (`color: var(--accent-red)`).
- Input com borda vermelha quando inválido (`border-color: var(--accent-red)`).
- Ícone de alerta (triangle) antes da mensagem de erro.
- No submit do formulário, scrollar/focar o primeiro campo inválido.

### Accessibility
- Mensagem de erro com `role="alert"`.
- Input inválido com `aria-invalid="true"` e `aria-describedby` apontando para erro.
- Não usar apenas cor para indicar erro.

### Dark Mode
- Borda erro: `var(--accent-red)` com leve glow (`box-shadow: 0 0 0 2px rgba(239,68,68,0.2)`).

### Motion
- Shake sutil (translateX ±3px, 200ms) no campo inválido ao tentar salvar.
- Desabilitar animação se `prefers-reduced-motion`.

### Responsive
- Mensagens de erro quebram linha naturalmente em mobile.
