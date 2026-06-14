# 007 — Redesign visual do template SEO para apresentação comercial

## Motivação

O projeto `seo/template` nasceu como um CMS genérico em PHP+SQLite com foco técnico em SEO. A interface pública atual é funcional, mas visualmente parece um template de administração genérico: navbar escura, hero azul, cards simples e formulário de contato. Isso não comunica o valor comercial da ferramenta para empresas que buscam um template SEO profissional.

O objetivo é reposicionar o site público como uma **landing page de apresentação do próprio template**, com aparência de produto SaaS moderno, enquanto mantém — e melhora — a capacidade de o usuário final customizar tudo para o site da sua empresa. O painel administrativo também será redesenhado para compartilhar a mesma identidade visual, criando uma experiência coesa entre marketing e uso do produto.

## Escopo

### Dentro do escopo
- Redesign completo do site público:
  - `templates/public/layout.php` (estrutura base, navegação, footer)
  - `templates/public/home.php` (hero, recursos, CTA)
  - `templates/public/page.php` (página de conteúdo interno)
  - `templates/public/404.php` (página de erro)
  - `public/assets/style.css` (novo design system CSS)
- Redesign de todas as telas do painel administrativo:
  - Login (`templates/admin/login.php`)
  - Layout base (`templates/admin/layout.php`)
  - Dashboard, páginas, editor de páginas, mídia, mensagens, redirecionamentos, configurações
  - `public/assets/admin.css` (unificar e modernizar design system)
- Criação de identidade visual: nome de marca, paleta verde/tech, tipografia, logo simbólico.
- Animações ousadas com fallback para `prefers-reduced-motion`.
- Suporte a modo escuro via `prefers-color-scheme`.
- Copy de exemplo para a homepage.

### Fora do escopo
- Criar novas páginas de marketing além da homepage.
- Alterar a arquitetura backend, rotas ou modelos de dados.
- Implementar toggle manual de tema (apenas detecção de preferência do sistema).
- Fornecer assets de marca pelo usuário.

## Restrições

- Manter PHP 8+ e SQLite.
- Preservar funcionalidade existente: rotas, controllers, models, SEO meta tags, sitemap, robots, formulário de contato, painel admin.
- Templates devem continuar usando o sistema `App\Core\View` existente.
- O CSS deve ser vanilla (sem build step adicional além do já existente com npm/jsdom/playwright).
- Deve funcionar no servidor embutido do PHP (`php -S localhost:8000 -t public public/router.php`).
- Respeitar `prefers-reduced-motion` para acessibilidade.

## Critérios de sucesso

1. A homepage comunica claramente que se trata de um template SEO para empresas.
2. O design visual é coeso entre site público e painel admin.
3. O site funciona em desktop, tablet e mobile.
4. As animações são ousadas mas respeitam usuários com preferência por movimento reduzido.
5. Os testes existentes continuam passando.
6. O usuário consegue, via admin, alterar título, descrição, conteúdo e imagens para customizar o site.
