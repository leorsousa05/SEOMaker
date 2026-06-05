# Admin — Spec Delta

## ADDED
- Tabela `redirects`
- Model `App\Models\Redirect`
- `RedirectsController` (index, save, delete)
- Template `templates/admin/redirects.php`
- Template `templates/admin/redirects_edit.php`
- Link no sidebar "Redirects"
- Interceptador de redirects no `public/index.php`

## MODIFIED
- `public/index.php` — checa redirect antes de routing
- `templates/admin/layout.php` — novo item no sidebar

## REMOVED
- Nada
