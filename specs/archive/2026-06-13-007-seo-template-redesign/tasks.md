# Tarefas — Redesign do Template SEO

## Fase 1: Design system e identidade
- [x] Definir nome de marca final e logo SVG inline.
- [x] Criar/atualizar variáveis CSS em `public/assets/style.css` (paleta verde/tech + tema escuro).
- [x] Ajustar variáveis de cor em `public/assets/admin.css` para nova paleta verde/tech.
- [x] Criar `public/assets/animations.js` com Intersection Observer e respeito a `prefers-reduced-motion`.

## Fase 2: Site público
- [x] Reescrever `templates/public/layout.php` com nova navbar, footer e estrutura semântica.
- [x] Reescrever `templates/public/home.php` com hero, recursos e CTA final.
- [x] Atualizar `src/Public/SiteController.php` com novo copy/features da homepage.
- [x] Aplicar novo design em `templates/public/page.php`.
- [x] Aplicar novo design em `templates/public/404.php`.

## Fase 3: Painel administrativo
- [x] Atualizar `templates/admin/login.php` com nova identidade visual.
- [x] Atualizar `templates/admin/layout.php` (logo, cores, tema escuro).
- [x] Ajustar `templates/admin/dashboard.php` com novo visual de stats e CTA.
- [x] Ajustes visuais automáticos via variáveis CSS nas demais telas admin (páginas, mídia, mensagens, redirecionamentos, configurações).

## Fase 4: Verificação
- [x] Rodar `php tests/run.php` e corrigir regressões.
- [x] Testar homepage em mobile, tablet e desktop (via CSS responsivo).
- [x] Verificar modo escuro no site público e no admin.
- [x] Verificar `prefers-reduced-motion`.
- [x] Verificar rotas do admin (login, dashboard, páginas).
