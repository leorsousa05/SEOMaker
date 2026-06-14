# Design Técnico — Redesign do Template SEO

## Sistema visual

### Identidade de marca
- **Nome sugerido:** "SEO Core" (pode ser alterado pelo usuário via Config → site_title).
- **Logo:** ícone geométrico abstrato combinando seta de crescimento (SEO/rank) + estrutura de página. Representação textual inicial usando SVG inline ou caractere unicode; nenhuma dependência de asset externo obrigatória.
- **Tom de voz:** profissional, direto, orientado a resultados.

### Paleta de cores (verde/tech)

#### Site público
```css
:root {
  --color-primary: #10b981;       /* emerald-500 */
  --color-primary-dark: #059669;  /* emerald-600 */
  --color-primary-light: #34d399; /* emerald-400 */
  --color-accent: #0ea5e9;        /* sky-500 - cor de destaque secundária */
  --color-bg: #ffffff;
  --color-bg-soft: #f8fafc;       /* slate-50 */
  --color-bg-muted: #f1f5f9;      /* slate-100 */
  --color-text: #0f172a;          /* slate-900 */
  --color-text-secondary: #475569; /* slate-600 */
  --color-text-muted: #94a3b8;    /* slate-400 */
  --color-border: #e2e8f0;        /* slate-200 */
  --color-success: #22c55e;
  --color-danger: #ef4444;
}

@media (prefers-color-scheme: dark) {
  :root {
    --color-bg: #0f172a;
    --color-bg-soft: #1e293b;
    --color-bg-muted: #334155;
    --color-text: #f8fafc;
    --color-text-secondary: #cbd5e1;
    --color-text-muted: #64748b;
    --color-border: #334155;
  }
}
```

#### Painel admin
Manter a estrutura de variáveis existente em `admin.css`, mas migrar acentos para verde/tech:
```css
:root {
  --accent: #10b981;
  --accent-hover: #059669;
  --accent-blue: #0ea5e9;
  --accent-blue-hover: #0284c7;
  --accent-red: #ef4444;
  /* ... demais variáveis mantidas */
}
```

### Tipografia
- **Site público:** `Inter` ou `Plus Jakarta Sans` via Google Fonts; pesos 400, 500, 600, 700, 800.
- **Admin:** já usa `Inter`; manter.
- **Escala:**
  - Hero headline: `clamp(2.5rem, 5vw, 4rem)`, peso 800, line-height 1.1.
  - Hero subheadline: `clamp(1.125rem, 2vw, 1.375rem)`, peso 400, line-height 1.6.
  - Section title: `2rem`, peso 700.
  - Body: `1rem` (16px), line-height 1.7.

### Espaçamento
- Container máximo: `1200px` no site público; `1200px` mantido no admin.
- Padding vertical de seções: `6rem` (96px) desktop, `4rem` mobile.
- Grid de recursos: 3 colunas desktop, 2 tablet, 1 mobile, gap `2rem`.
- Radius: `12px` para cards, `999px` para botões/pills, `8px` para inputs.

### Componentes

#### Botões
- `.btn-primary`: fundo primary, texto branco, hover com translateY(-2px) e shadow.
- `.btn-secondary`: fundo transparente, borda primary, texto primary.
- `.btn-lg`: maior padding para hero.

#### Cards de recurso
- Fundo `bg-soft`, borda sutil, ícone em círculo com gradiente verde.
- Hover: elevação, brilho sutil, transição `transform 0.3s ease`.

#### Navbar
- Versão A (transparente sobre hero): logo + links + CTA, torna-se branca/blur ao rolar.
- Versão B (fixa branca): mais simples, shadow sutil.
- **Decisão inicial:** usar navbar fixa com fundo blur/transparente sobre hero para impacto visual ousado.

## Arquitetura

### Padrões aplicados
- **SDD (Spec-Driven Development):** esta spec guia a implementação.
- **DDD (Domain-Driven Design):** fronteiras mantidas — `templates/public/` e `templates/admin/` são contexts separados.
- **Context Engineering:** nomes semânticos (`.hero`, `.feature-card`, `.admin-header`) permitem entender o propósito sem abrir múltiplos arquivos.

### Estrutura de arquivos esperada após implementação

```
public/
  assets/
    style.css           # design system público (reescrição)
    admin.css           # design system admin (ajuste de paleta)
    animations.js       # animações de entrada/scroll + reduced-motion
    tabs.js             # existente, mantido
    media.js            # existente, mantido
    block-editor.js     # existente, mantido
    schema-editor.js    # existente, mantido
templates/
  public/
    layout.php          # base HTML com novo design
    home.php            # hero + features + CTA + contato
    page.php            # página de conteúdo
    404.php             # erro estilizado
  admin/
    layout.php          # base admin com nova identidade
    login.php           # tela de login com nova identidade
    dashboard.php       # dashboard estilizado
    pages.php           # listagem estilizada
    pages_edit.php      # editor estilizado
    media.php           # galeria estilizada
    messages.php        # mensagens estilizadas
    redirects.php       # redirects estilizado
    redirects_edit.php  # editor de redirect estilizado
    settings.php        # configurações estilizadas
    _media_modal.php    # mantido, estilizado
    _address_form.php   # mantido, estilizado
    _seo_preview.php    # mantido, estilizado
src/
  Public/
    SiteController.php  # ajuste no copy/features da home
```

### Fluxo de dados
1. Requisição chega ao `Router` → `SiteController::home()`.
2. Controller busca página com slug vazio e features no método privado.
3. `View::render('public/home')` injeta `$page` e `$features` no template.
4. Template usa variáveis e chama `SeoManager` para meta tags.
5. CSS e JS são servidos estaticamente de `public/assets/`.

Nenhuma mudança de contrato PHP é necessária; apenas o conteúdo renderizado e os assets mudam.

## Estratégia de animação

### Biblioteca
- **Nenhuma dependência externa de animação.** Usar CSS transitions + Intersection Observer via `animations.js` vanilla.

### Animações ousadas (padrão)
- Hero: fade-in + slide-up stagger no título, subtítulo e botões (delay 0.1s entre elementos).
- Cards de recurso: fade-up ao entrar no viewport, stagger entre itens.
- CTA final: scale-in suave ao entrar no viewport.
- Hover em botões: `translateY(-2px)` + shadow aumentada.
- Hover em cards: `translateY(-4px)` + brilho sutil.
- Gradiente animado no hero (opcional): subtle background-position shift ou mesh gradient.

### Fallback de acessibilidade
```js
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (prefersReducedMotion) {
  document.documentElement.classList.add('reduce-motion');
}
```
CSS:
```css
.reduce-motion *,
.reduce-motion *::before,
.reduce-motion *::after {
  animation-duration: 0.01ms !important;
  animation-iteration-count: 1 !important;
  transition-duration: 0.01ms !important;
}
```

## Acessibilidade
- Contraste mínimo 4.5:1 para texto normal, 3:1 para componentes grandes.
- Foco visível em todos os elementos interativos (`:focus-visible`).
- Estrutura semântica (`<header>`, `<main>`, `<section>`, `<footer>`).
- Textos alternativos para ícones decorativos (`aria-hidden="true"`).
- `prefers-reduced-motion` respeitado.

## Testes

### Testes manuais recomendados
1. Visualizar homepage em 320px, 768px, 1440px.
2. Alternar modo escuro no sistema e verificar site + admin.
3. Ativar `prefers-reduced-motion` e verificar ausência de animações.
4. Navegar por todas as telas do admin e verificar funcionalidade.
5. Enviar formulário de contato e verificar mensagem de sucesso.

### Testes automatizados
- Rodar `php tests/run.php` antes e depois das alterações.
- Verificar se sitemap e robots continuam acessíveis.
- Validar HTML semântico e contraste com ferramentas externas (recomendado, mas não obrigatório neste escopo).

## Riscos e trade-offs

| Risco | Mitigação |
|-------|-----------|
| Quebrar CSS existente do admin | Manter nomes de classes existentes; alterar apenas variáveis e detalhes visuais |
| Animações pesadas em hardware fraco | Usar `transform` e `opacity` apenas; respeitar reduced-motion |
| Modo escuro inconsistente | Centralizar variáveis CSS e testar ambos os temas |
| Copy de exemplo não refletir valor | Revisar com usuário após primeira versão |
| Tempo elevado para redesenhar todas as telas do admin | Priorizar layout, login e dashboard; manter telas internas consistentes via classes existentes |
