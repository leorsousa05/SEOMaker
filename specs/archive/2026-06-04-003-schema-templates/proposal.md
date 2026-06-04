# Proposal: Admin Leigo-Friendly + Schema Visual Editor

## WHY
O admin atual exige conhecimento técnico: JSON para schema, campos de texto livre sem contexto, e zero feedback visual. Clientes leigos precisam de uma interface intuitiva onde selecionam opções, preenchem campos comuns e veem previews do resultado.

## Scope
### Schema Visual Editor (pages_edit.php)
- Para cada tipo de schema, renderizar campos de formulário específicos (não JSON)
- Sistema gera o JSON automaticamente ao salvar
- Templates pré-preenchidos com dados do site

### Settings Simplificadas
- Campos técnicos escondidos ou com defaults automáticos
- Toggles/selects em vez de textos livres onde possível
- Tooltips explicando cada campo

### SEO Preview
- Preview de como a página aparece no Google (title + description + URL)

### UI Polish
- Feedback visual em ações (salvar, deletar)
- Estados vazios amigáveis
- Ícones SVG consistentes (sem emoji)

## Constraints
- PHP 8.1+ puro, HTML/CSS/JS vanilla
- Manter compatibilidade com dados existentes
- Sem frameworks frontend
