# Proposal — Parte 6

## WHY
Breadcrumbs ajudam SEO técnico e rich snippets no Google. Redirects 301 são essenciais para manter link juice ao reestruturar URLs.

## Scope
- BreadcrumbList JSON-LD automático em todas as páginas públicas
- Tabela `redirects` para gerenciar redirecionamentos 301
- Admin CRUD para redirects
- Interceptador de redirects antes do routing

## Constraints
- Breadcrumb simples: Home → Página Atual
- Redirect ativo imediatamente; sem cache agressivo
- URLs relativas (ex: `/antiga` → `/nova`)
