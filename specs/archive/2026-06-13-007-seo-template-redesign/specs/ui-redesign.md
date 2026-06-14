# Especificação do Redesign UI

## Estado atual

### Site público
- `templates/public/layout.php`: estrutura HTML5 básica, navbar escura fixa, footer simples.
- `templates/public/home.php`: hero com fundo gradiente azul, grid de 3 cards de funcionalidades, formulário de contato.
- `templates/public/page.php`: renderiza conteúdo de página com blocos de texto, imagem, galeria, vídeo, mapa, CTA, FAQ.
- `public/assets/style.css`: aproximadamente 400 linhas, design visual básico com variáveis azuis, tipografia system-ui.

### Painel administrativo
- `templates/admin/layout.php`: layout com sidebar fixa à esquerda, header sticky, toggle de tema (já funcional via `localStorage` + `prefers-color-scheme`).
- `templates/admin/login.php`: tela de login simples com logo "S" e nome "SEO Template".
- Telas admin existentes: dashboard, pages, pages_edit, media, messages, redirects, redirects_edit, settings.
- `public/assets/admin.css`: aproximadamente 1600 linhas, design system Vercel-like em preto/branco com acentos azuis.

## Mudanças propostas

### ADDED
- Novo design system visual unificado para site público e admin:
  - Paleta verde/tech com tons de teal, emerald e slate neutro.
  - Tipografia Inter para admin e outra fonte moderna para site público (a definir no design.md).
  - Variáveis CSS para tema claro/escuro em ambos os CSS.
- Novo arquivo `public/assets/animations.js`:
  - Controla animações de entrada (fade-up, stagger).
  - Implementa observador de interseção para animações de scroll.
  - Respeita `prefers-reduced-motion` desabilitando animações.
- Novo logo/identidade visual simbólica (texto + ícone) aplicável a navbar, admin, login e footer.
- Novos componentes visuais no site público:
  - Hero com headline grande, subtítulo, CTA primário e secundário, possível grid/gradiente de fundo.
  - Seção de recursos com ícones/ilustrações e cards com hover animado.
  - Seção CTA final antes do footer.
  - Footer expandido com links e informações de marca.

### MODIFIED
- `templates/public/layout.php`:
  - Atualizar navbar para estilo moderno (possivelmente transparente sobre hero ou branco com sombra).
  - Reorganizar `<head>` para carregar novo CSS e JS de animações.
  - Melhorar footer com layout de colunas.
- `templates/public/home.php`:
  - Reestruturar hero com copy de apresentação do template.
  - Substituir cards de funcionalidades por cards mais ricos visualmente.
  - Adicionar CTA final.
  - Manter formulário de contato (pode ser movido para final da página ou mantido como seção).
- `templates/public/page.php` e `templates/public/404.php`:
  - Aplicar novo design system.
  - Garantir legibilidade e espaçamento consistentes.
- `public/assets/style.css`:
  - Reescrever do zero ou refatorar completamente para novo design system.
  - Adicionar variáveis de cor para tema escuro via `prefers-color-scheme`.
  - Adicionar classes utilitárias para animações e layout.
- `templates/admin/layout.php`:
  - Atualizar marca/logo para nova identidade.
  - Ajustar paleta para verde/tech (sem perder legibilidade).
  - Manter funcionalidade de tema escuro.
- `templates/admin/login.php`:
  - Aplicar nova identidade visual.
  - Manter formulário e credenciais de demo.
- `public/assets/admin.css`:
  - Ajustar variáveis de cor para nova paleta verde/tech.
  - Revisar contrastes no modo escuro.
- `src/Public/SiteController.php`:
  - Atualizar `getLandingFeatures()` para refletir nova proposta de valor (template para empresas).
  - Possivelmente adicionar dados adicionais para hero/CTA se necessário.

### REMOVED
- Estilo visual azul genérico do site público.
- Apelo genérico de "SEO Template PHP" no hero — substituído por copy orientada a empresas.
- Inconsistências visuais entre site público e painel admin.

## Comportamento esperado

1. Ao acessar `/`, o usuário vê uma landing page moderna apresentando o template SEO.
2. Ao acessar `/admin`, o login reflete a mesma identidade visual.
3. Dentro do admin, todas as telas usam o design system atualizado.
4. O tema escuro é ativado automaticamente quando o sistema do usuário está em modo escuro.
5. Usuários com `prefers-reduced-motion` não veem animações de entrada/scroll.
6. O admin continua 100% funcional: CRUD de páginas, uploads, mensagens, redirecionamentos, configurações.
