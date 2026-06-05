# Pages — Spec Delta

## ADDED
- `Page::generateSlug(string $title): string`
- `Page::isDuplicateSlug(string $slug, ?int $excludeId): bool`
- `Page::validate(array $data, ?int $excludeId): array`
- Validação server-side no `PagesController::save()`
- Exibição de erros no template `pages_edit.php`
- Checkbox "gerar slug automaticamente" no form

## MODIFIED
- `PagesController::save()` gera slug automaticamente quando vazio
- `PagesController::save()` valida antes de persistir

## REMOVED
- Nada
