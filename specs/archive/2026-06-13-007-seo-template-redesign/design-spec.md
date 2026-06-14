# Design Specification — SEO Template Redesign

## 1. Aesthetic Direction Statement

**Direction:** Refined B2B SaaS with organic momentum.

A landing page e painel admin com estética de produto SaaS moderno, voltado para empresas que querem um site otimizado para SEO sem complexidade. A direção combina estrutura limpa e espaçamento generoso (confiança B2B) com uma paleta verde/teal vibrante e movimento ousado (energia de crescimento/SEO). A inspiração vem de apresentações de ferramentas como Webflow e Shopify: conversão clara, hierarquia forte, prova social implícita e um toque de sofisticação visual.

## 2. Color System

### Semantic tokens
```css
:root {
  /* Primary — verde/teal crescimento */
  --color-primary: #10b981;        /* emerald-500 */
  --color-primary-dark: #059669;   /* emerald-600 */
  --color-primary-light: #34d399;  /* emerald-400 */
  --color-primary-soft: #d1fae5;   /* emerald-100 */

  /* Secondary accent — tech blue */
  --color-accent: #0ea5e9;         /* sky-500 */
  --color-accent-dark: #0284c7;    /* sky-600 */
  --color-accent-soft: #e0f2fe;    /* sky-100 */

  /* Neutrals — slate */
  --color-bg: #ffffff;
  --color-surface: #f8fafc;        /* slate-50 */
  --color-surface-raised: #ffffff;
  --color-border: #e2e8f0;         /* slate-200 */
  --color-text: #0f172a;           /* slate-900 */
  --color-text-secondary: #475569; /* slate-600 */
  --color-text-muted: #94a3b8;     /* slate-400 */

  /* Semantic states */
  --color-success: #22c55e;
  --color-danger: #ef4444;
  --color-warning: #f59e0b;

  /* Gradients */
  --gradient-hero: linear-gradient(135deg, #0f172a 0%, #134e4a 50%, #0d9488 100%);
  --gradient-brand: linear-gradient(135deg, #10b981 0%, #0ea5e9 100%);
  --gradient-soft: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
}

@media (prefers-color-scheme: dark) {
  :root {
    --color-bg: #0f172a;             /* slate-900 */
    --color-surface: #1e293b;        /* slate-800 */
    --color-surface-raised: #334155; /* slate-700 */
    --color-border: #334155;
    --color-text: #f8fafc;           /* slate-50 */
    --color-text-secondary: #cbd5e1; /* slate-300 */
    --color-text-muted: #64748b;     /* slate-500 */
    --gradient-hero: linear-gradient(135deg, #020617 0%, #115e59 50%, #0f766e 100%);
    --gradient-soft: linear-gradient(180deg, #064e3b 0%, #0f172a 100%);
  }
}
```

### Usage rules
- **Hero:** fundo `gradient-hero` com texto branco.
- **Features:** fundo `gradient-soft` (claro) ou `--color-surface` (escuro).
- **CTA final:** fundo `gradient-brand` com texto branco.
- **Admin:** usar `--color-primary` como cor de destaque principal em vez do preto/azul atual.

## 3. Typography System

### Font families
- **Display / Headings:** `Syne` (Google Fonts). Geometric sans-serif moderna com personalidade. Peso 700-800.
- **Body / UI:** `Inter` (já em uso no admin). Neutra, legível, peso 400-500.
- **Monospace (badges, labels técnicos):** `JetBrains Mono` ou `SF Mono` fallback.

### Type scale
| Token | Size desktop | Size mobile | Weight | Line-height | Use |
|-------|-------------|-------------|--------|-------------|-----|
| `display` | `clamp(2.75rem, 6vw, 4.5rem)` | `2.25rem` | 800 | 1.05 | Hero headline |
| `title-1` | `clamp(2rem, 4vw, 3rem)` | `1.75rem` | 700 | 1.1 | Section titles |
| `title-2` | `1.5rem` | `1.25rem` | 700 | 1.2 | Card titles |
| `title-3` | `1.125rem` | `1rem` | 600 | 1.3 | Subsection |
| `body` | `1rem` (16px) | `1rem` | 400 | 1.7 | Paragraphs |
| `body-large` | `1.25rem` | `1.125rem` | 400 | 1.6 | Hero subheadline |
| `label` | `0.875rem` | `0.875rem` | 600 | 1.4 | Buttons, labels |
| `caption` | `0.75rem` | `0.75rem` | 500 | 1.4 | Captions, meta |

### Typographic rules
- Headlines: letter-spacing `-0.02em`.
- Body text: max-width `65ch` para legibilidade.
- Labels e buttons: `letter-spacing: 0.01em`.

## 4. Component Specs

### Buttons
```css
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.875rem 1.75rem;
  border-radius: 9999px; /* pill shape */
  font-family: 'Inter', sans-serif;
  font-size: 0.9375rem;
  font-weight: 600;
  text-decoration: none;
  border: 2px solid transparent;
  cursor: pointer;
  transition: transform 0.2s ease-out, box-shadow 0.2s ease-out, background 0.2s ease;
}

.btn-primary {
  background: var(--gradient-brand);
  color: #fff;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(16, 185, 129, 0.45);
}
.btn-primary:focus-visible {
  outline: 3px solid var(--color-accent-soft);
  outline-offset: 2px;
}

.btn-secondary {
  background: transparent;
  color: var(--color-text);
  border-color: var(--color-border);
}
.btn-secondary:hover {
  border-color: var(--color-primary);
  color: var(--color-primary);
  transform: translateY(-2px);
}

.btn-white {
  background: #fff;
  color: var(--color-primary-dark);
}
.btn-white:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}
```

### Cards de recurso
```css
.feature-card {
  background: var(--color-surface-raised);
  border: 1px solid var(--color-border);
  border-radius: 1.25rem;
  padding: 2rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}
.feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
  border-color: var(--color-primary-light);
}
```
- Ícone: círculo 56px com fundo `gradient-brand` ou `--color-primary-soft`, ícone branco ou `--color-primary`.
- Título: `title-2`, cor `--color-text`.
- Descrição: `body`, cor `--color-text-secondary`.

### Navbar
- Posição: fixed, full-width, z-index 50.
- Estado inicial (topo): fundo transparente, texto branco (sobre hero).
- Estado scrolled: fundo `rgba(255,255,255,0.85)` com `backdrop-filter: blur(12px)`, texto `--color-text`, sombra sutil.
- Altura: 72px desktop, 64px mobile.
- Logo: ícone SVG + texto "SEO Core" em Syne 700.
- Links: Inter 500, gap 2rem.
- CTA direito: botão `.btn-primary` compacto.

### Footer
- Fundo `--color-surface`.
- Layout 4 colunas desktop: marca + descrição, links rápidos, recursos, contato.
- 1 coluna mobile.
- Borda superior sutil e copyright na base.

## 5. Layout Structure

### Homepage

```
┌─────────────────────────────────────────────┐
│  NAVBAR (fixed, transparent → glass on scroll)│
├─────────────────────────────────────────────┤
│                                             │
│  HERO                                       │
│  ┌─────────────────────────────────────┐   │
│  │  Headline grande (2-3 linhas)       │   │
│  │  Subheadline + 2 CTAs               │   │
│  │  [visual abstrato / mockup direita] │   │
│  └─────────────────────────────────────┘   │
│  Height: 100vh min, conteúdo centralizado   │
│                                             │
├─────────────────────────────────────────────┤
│  FEATURES                                   │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐       │
│  │ Card 1  │ │ Card 2  │ │ Card 3  │       │
│  └─────────┘ └─────────┘ └─────────┘       │
│  Título de seção + grid 3 colunas           │
├─────────────────────────────────────────────┤
│  CTA FINAL                                  │
│  Fundo gradiente brand, texto branco        │
│  Headline + botão branco                    │
├─────────────────────────────────────────────┤
│  FOOTER (4 colunas → 1 coluna mobile)       │
└─────────────────────────────────────────────┘
```

### Admin
- Manter estrutura sidebar + main existente.
- Aplicar nova identidade: logo, cores, tipografia.
- Dashboard: cards de estatísticas com ícones e gradientes sutis.
- Tabelas: manter estrutura, ajustar cores para nova paleta.
- Forms: inputs com bordas arredondadas, foco verde.

### Responsive breakpoints
- Mobile: 375px
- Tablet: 768px
- Desktop: 1024px
- Wide: 1440px

## 6. Motion Choreography

### Page load sequence (hero)
1. Background gradient fade-in: `opacity 0 → 1`, 600ms, ease-out.
2. Navbar slide-down: `translateY(-20px) → 0`, 400ms, ease-out, delay 200ms.
3. Headline words: `translateY(30px), opacity 0 → translateY(0), opacity 1`, 600ms, ease-out, stagger 80ms.
4. Subheadline: fade-up, 500ms, delay 400ms.
5. CTA buttons: fade-up + scale `0.95 → 1`, 400ms, stagger 100ms, delay 600ms.
6. Hero visual: fade-in + subtle float animation, 800ms.

### Scroll reveal
- Use Intersection Observer.
- Elements start with `opacity: 0; transform: translateY(40px)`.
- On enter viewport: `opacity: 1; transform: translateY(0)`.
- Duration: 700ms, easing: `cubic-bezier(0.22, 1, 0.36, 1)`.
- Stagger between cards: 100ms.

### Hover effects
- Buttons: `translateY(-2px)` + shadow increase, 200ms.
- Cards: `translateY(-6px)` + shadow + border-color, 300ms.
- Links: color transition to primary, 150ms.

### Continuous ambient motion
- Hero visual: subtle `translateY(-8px) ↔ translateY(8px)` float, 6s, ease-in-out, infinite.
- Gradient background (optional): subtle background-position shift, 15s, linear, infinite.

### Reduced motion fallback
```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

## 7. Asset List

### Icons
- Use **Phosphor Icons** (https://phosphoricons.com) ou **Lucide** via CDN/SVG inline.
- Ícones necessários:
  - Navbar: menu (mobile), close.
  - Hero: rocket, chart-line-up, magnifying-glass (ou similar).
  - Features: globe, files, gear, chart-bar, sitemap, shield-check.
  - Footer: social icons (opcional).
  - Admin: manter SVGs existentes ou substituir por Phosphor/Lucide.

### Images
- Hero visual: criar composição abstrata com CSS/SVG (círculos, grids, blur blobs) para evitar dependência de imagem externa. Opcionalmente permitir upload via config no futuro.
- Logo: SVG inline.

### Textures/effects
- Noise overlay sutil no hero (2% opacity).
- Blur blobs verdes/azuis no background do hero.
- Glassmorphism na navbar scrolled.

## 8. Copy de exemplo para homepage

### Hero
- **Headline:** "Seu site otimizado para crescer no Google"
- **Subheadline:** "Template SEO completo com painel administrativo, schema.org, sitemap automático e tudo que sua empresa precisa para ranquear — sem depender de agências."
- **CTA primário:** "Começar agora"
- **CTA secundário:** "Ver demonstração"

### Features
1. **SEO técnico pronto**
   "Sitemap.xml, robots.txt, meta tags e schema.org configurados automaticamente."
2. **Painel intuitivo**
   "Gerencie páginas, imagens, redirecionamentos e mensagens em uma interface limpa."
3. **Rápido e leve**
   "Código PHP enxuto, sem bloat. Performance que o Google valoriza."

### CTA final
- **Headline:** "Pronto para transformar sua presença online?"
- **Botão:** "Usar o template grátis"

## 9. Pre-Implementation Checklist

- [x] Aesthetic direction committed
- [x] Color system defined with light/dark variants
- [x] Typography system defined with font families and scale
- [x] Component specs include default, hover, focus states
- [x] Layout structure includes mobile adaptation
- [x] Motion choreography includes reduced-motion fallback
- [x] Asset list defined
- [x] Contrast ratios verified conceptually (emerald #10b981 on white = ~3.0:1 for large text; body text uses slate #475569 on white = ~6.5:1)
- [ ] Engineer to verify final contrast ratios with tooling
- [ ] Touch targets ≥44px
- [ ] Focus states implemented
- [ ] No emoji used as structural icons
