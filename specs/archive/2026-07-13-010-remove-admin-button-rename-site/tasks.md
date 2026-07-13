# Tasks: 010-remove-admin-button-rename-site

Checklist de tarefas para remover o botão do cabeçalho e renomear referências.

## 🧭 1. Templates Públicos e Administrativos
- [ ] Editar `templates/public/layout.php`:
  - Remover a tag `<a>` correspondente ao botão "Painel".
  - Renomear a string fallback de `'SEO Template'` para `'SEOMaker'`.
- [ ] Editar `templates/admin/layout.php`:
  - Renomear a string fallback de `'SEO Template'` para `'SEOMaker'` no `<title>`.
  - Renomear o link do brand logo `<a href="/admin">SEO Template</a>` para `<a href="/admin">SEOMaker</a>`.
- [ ] Editar `templates/admin/login.php`:
  - Renomear o título principal `<h1>SEO Template</h1>` para `<h1>SEOMaker</h1>`.

## 📦 2. Seeder do Core e Testes
- [ ] Editar `src/Core/Seeder.php`:
  - Substituir ocorrências de `'SEO Template PHP'` e `'SEO Template'` por `'SEOMaker'`.
- [ ] Editar `tests/php/BreadcrumbSchemaTest.php`:
  - Atualizar o valor mockado `'Test Site'` para `'SEOMaker'`.
- [ ] Editar `tests/php/SeoManagerTest.php`:
  - Atualizar o valor mockado `'Test Site'` para `'SEOMaker'`.

## 🧪 3. Verificação
- [ ] Rodar `php tests/run.php` para certificar integridade.
