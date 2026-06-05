# Tarefas — Parte 6

## BreadcrumbList
- [ ] `SeoManager::breadcrumbSchema()` gera JSON-LD válido
- [ ] Adicionar ao `templates/public/layout.php`
- [ ] Testes: estrutura BreadcrumbList, 2 items, URLs corretas

## Redirects — Banco
- [ ] Migration tabela `redirects` no Seeder
- [ ] Model `App\Models\Redirect`

## Redirects — Admin
- [ ] `RedirectsController` (index, save, delete)
- [ ] Templates `redirects.php` e `redirects_edit.php`
- [ ] Rotas no router
- [ ] Link no sidebar

## Redirects — Intercept
- [ ] Checar redirect antes do routing em `public/index.php`
- [ ] Testes: redirect ativo, redirect inativo, loop prevention

## Testes
- [ ] Testes PHP
- [ ] Rodar suite completa
- [ ] Commit
