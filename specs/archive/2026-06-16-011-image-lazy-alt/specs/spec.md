# Spec Delta: Lazy Loading and Required Alt Text for Images

## Current State
- Bloco `image` renderiza `<img>` com `alt` opcional.
- Bloco `gallery` renderiza múltiplas `<img>` sem lazy loading consistente.
- Não há validação obrigatória de `alt`.

## Changes

### ADDED
- Constante/classe helper `BlockEditor::DEFAULT_IMAGE_LOADING`.
- Método `BlockEditor::requiresAltText(string $blockType): bool`.
- Validação de `alt` em `BlockEditor::renderBlock()` e no controller.
- Testes em `tests/php/ImageLazyAltTest.php`.

### MODIFIED
- `src/Content/BlockEditor.php`: adicionar `loading="lazy"`/`eager` e validar `alt`.
- `public/assets/block-editor.js`: marcar `alt` como obrigatório e validar antes de salvar.
- `src/Admin/PagesController.php`: rejeitar salvamento se blocos de imagem/galeria estiverem sem `alt`.
- `templates/admin/pages_edit.php`: exibir erros de validação de blocos.
- `tests/run.php`: incluir novo teste.

### REMOVED
- Nada removido.

## Migration Notes
- Blocos existentes sem alt continuarão renderizando até serem editados; na edição, o usuário deve preencher o alt.

## Backward Compatibility
- Parcial. Renderização de blocos antigos continua funcionando, mas novos salvamentos exigem alt.
