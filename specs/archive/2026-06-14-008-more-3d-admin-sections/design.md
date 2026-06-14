# Design Specification — Additional 3D Admin Sections

## 1. Aesthetic Direction Statement

**Direction:** Industrial/Utilitarian developer tool, refined.

A landing page for a PHP + SQLite CMS that speaks directly to developers. The visual language is clean, structural and technical: monospace labels, precise grids, layered 3D interface mockups and a green/blue palette that signals "growth + tech". Unlike generic SaaS landing pages, the page proves the product is real by showing the actual admin interface in perspective — not abstract illustrations. Every 3D composition is built with CSS transforms only, keeping the stack pure and aligned with the product's philosophy.

## 2. Color System

Reuse the existing public design system. New sections must remain inside these tokens.

```css
:root {
  --color-primary: #10b981;
  --color-primary-dark: #059669;
  --color-primary-light: #34d399;
  --color-primary-soft: #d1fae5;

  --color-accent: #0ea5e9;
  --color-accent-dark: #0284c7;
  --color-accent-soft: #e0f2fe;

  --color-bg: #ffffff;
  --color-surface: #f8fafc;
  --color-surface-raised: #ffffff;
  --color-border: #e2e8f0;
  --color-text: #0f172a;
  --color-text-secondary: #475569;
  --color-text-muted: #94a3b8;

  --gradient-hero: linear-gradient(135deg, #0f172a 0%, #134e4a 50%, #0d9488 100%);
  --gradient-brand: linear-gradient(135deg, #10b981 0%, #0ea5e9 100%);
  --gradient-soft: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);
}
```

Dark mode follows the existing `prefers-color-scheme` block.

### Section backgrounds
- **Hero:** `gradient-hero` (dark) — existing 3D dashboard mockup.
- **Page editor section:** `color-bg` (white/light) or subtle `gradient-soft`.
- **SEO section:** `gradient-hero` (dark) to create rhythm and make the 3D mockup glass cards pop.
- **CTA/contact:** keep existing.

## 3. Typography System

Reuse existing tokens.

| Token | Desktop | Mobile | Weight | Use |
|-------|---------|--------|--------|-----|
| `display` | `clamp(2rem, 6vw, 4rem)` | `2rem` | 800 | Hero headline |
| `title-1` | `clamp(1.75rem, 3vw, 2.25rem)` | `1.75rem` | 700 | Section titles |
| `title-2` | `clamp(1.25rem, 2vw, 1.5rem)` | `1.25rem` | 700 | Card titles |
| `body` | `1rem` | `1rem` | 400 | Paragraphs |
| `lead` | `clamp(1.125rem, 2vw, 1.375rem)` | `1.125rem` | 400 | Section descriptions |
| `label` | `0.875rem` | `0.875rem` | 600 | Badges, labels |
| `mono` | `0.8125rem` | `0.8125rem` | 500 | Code-like labels inside mockups |

## 4. Component Specs

### 3D mockup container
```css
.admin-mockup-3d {
    position: relative;
    width: 100%;
    max-width: 560px;
    perspective: 1200px;
    transform-style: preserve-3d;
    margin: 0 auto;
}
```

### Base panel
```css
.mockup-base {
    position: relative;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 1.25rem;
    padding: 0.75rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.35);
    transform: rotateX(6deg) rotateY(-10deg);
    transform-style: preserve-3d;
    animation: mockupFloat 8s ease-in-out infinite;
}
```

On **light sections**, invert the base to dark-ish glass so it remains visible:
```css
.mockup-base--light {
    background: rgba(15, 23, 42, 0.75);
    border-color: rgba(255, 255, 255, 0.12);
    box-shadow: 0 40px 80px rgba(15, 23, 42, 0.25);
}
```

### Floating cards
```css
.mockup-float-card {
    position: absolute;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 0.75rem;
    padding: 0.625rem 1rem;
    color: #fff;
    font-size: 0.8125rem;
    font-weight: 600;
    backdrop-filter: blur(8px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.25);
    animation: cardFloat 6s ease-in-out infinite;
    white-space: nowrap;
    transform: translateZ(60px);
}
```

### Section layout wrapper
```css
.showcase-section {
    padding: 6rem 0;
    overflow: hidden;
}

.showcase-section .container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
    align-items: center;
}

@media (min-width: 1024px) {
    .showcase-section .container {
        grid-template-columns: 1fr 1fr;
    }

    .showcase-section--reverse .container {
        direction: rtl;
    }

    .showcase-section--reverse .showcase-content,
    .showcase-section--reverse .showcase-visual {
        direction: ltr;
    }
}
```

## 5. Layout Structure

### New page flow
```
┌─────────────────────────────────────────────┐
│  NAVBAR                                     │
├─────────────────────────────────────────────┤
│  HERO (existing 3D dashboard mockup)        │
├─────────────────────────────────────────────┤
│  FEATURES (existing 6 technical cards)      │
├─────────────────────────────────────────────┤
│  SHOWCASE 1 — Editor de Páginas             │
│  [text left]  [3D page editor mockup right] │
├─────────────────────────────────────────────┤
│  SHOWCASE 2 — Painel SEO                    │
│  [3D SEO mockup left]  [text right]         │
├─────────────────────────────────────────────┤
│  CTA + CONTACT                              │
├─────────────────────────────────────────────┤
│  FOOTER                                     │
└─────────────────────────────────────────────┘
```

### Showcase 1 — Editor de Páginas
**Text side:**
- Label: "Editor"
- Title: "Crie páginas com blocos, sem sair do admin"
- Lead: "Editor visual com blocos de texto, chamadas, FAQ, vídeo e espaçadores. O conteúdo é salvo em JSON, renderizado em PHP e otimizado para SEO."
- Bullet list (3 items):
  - Blocos prontos: texto, CTA, FAQ, vídeo, espaçador
  - Ordenamento simples e sanitização de HTML
  - Preview imediato no front-end

**Visual side — 3D mockup composition:**
```
┌──────────────────────────────────────────┐
│  Editor de Página                        │
│  ┌────────────────────────────────────┐  │
│  │ Título da página                   │  │
│  │ /sobre-nos                         │  │
│  ├────────────────────────────────────┤  │
│  │ ┌──────────────────────────────┐   │  │
│  │ │ Bloco de Texto               │   │  │
│  │ │ ──────────────────────────   │   │  │
│  │ │ ─────────────────────        │   │  │
│  │ └──────────────────────────────┘   │  │
│  │ ┌──────────────────────────────┐   │  │
│  │ │ CTA                          │   │  │
│  │ │ [Botão principal]            │   │  │
│  │ └──────────────────────────────┘   │  │
│  └────────────────────────────────────┘  │
└──────────────────────────────────────────┘
        ↘        ↙
   [publish]  [draft]
```
- Base panel: dark glass on light background (`mockup-base--light`).
- Floating cards:
  - "Blocos JSON" — top right
  - "Sanitize HTML" — bottom left
  - "Reorder" — bottom right

### Showcase 2 — Painel SEO
**Text side:**
- Label: "SEO"
- Title: "Schema.org, sitemap e meta tags no mesmo lugar"
- Lead: "Configure títulos, descrições, Open Graph, Twitter Cards e dados estruturados sem tocar no código. O CMS gera sitemap.xml e robots.txt automaticamente."
- Bullet list (3 items):
  - Meta tags por página
  - Schema.org: LocalBusiness, Organization, Product, FAQ
  - Sitemap.xml e robots.txt dinâmicos

**Visual side — 3D mockup composition:**
```
┌──────────────────────────────────────────┐
│  SEO da Página                           │
│  ┌────────────────────────────────────┐  │
│  │ Meta title                         │  │
│  │ Meta description                   │  │
│  ├────────────────────────────────────┤  │
│  │ Schema.org: LocalBusiness          │  │
│  │ ┌──────────┐ ┌──────────┐          │  │
│  │ │ name     │ │ phone    │          │  │
│  │ │ address  │ │ geo      │          │  │
│  │ └──────────┘ └──────────┘          │  │
│  └────────────────────────────────────┘  │
└──────────────────────────────────────────┘
        ↘        ↙
   [sitemap.xml]  [robots.txt]
```
- Base panel: dark glass on dark background (same as hero).
- Floating cards:
  - "Meta tags" — top left
  - "Schema.org" — top right
  - "sitemap.xml" — bottom right

### Responsive behavior
- **Desktop (≥1024px):** text + visual side by side; alternate reversed layout.
- **Tablet (768–1023px):** stack text above visual; visual max-width 480px.
- **Mobile (<768px):** visual simplified — hide secondary floating cards, scale mockup to 0.75, reduce rotation to 0deg.

## 6. Motion Choreography

### Scroll reveal
- Sections and text use existing `[data-reveal]` pattern.
- 3D mockups fade up with `translateY(40px) → 0`, 700ms, `ease-out-expo`.
- Floating cards stagger in with 100ms delay between each.

### Continuous ambient motion
- Base panel: `mockupFloat` — `translateY(0) ↔ translateY(-10px)`, 8s, ease-in-out.
- Cards: `cardFloat` — `translateZ(60px) translateY(0) ↔ translateZ(60px) translateY(-8px)`, 6s, ease-in-out, staggered delays.

### Hover / focus
- Section CTAs: same button hover behavior as existing.
- Mockups are decorative, no hover state required.

### Reduced motion
```css
.reduce-motion .mockup-base,
.reduce-motion .mockup-float-card {
    animation: none;
}

.reduce-motion .mockup-base {
    transform: rotateX(4deg) rotateY(-6deg);
}

.reduce-motion .mockup-float-card {
    transform: translateZ(30px);
}
```

## 7. Asset List

### Icons
Reuse the existing inline SVG icon style. New icons needed:
- Editor section: `layout` / `columns` icon
- SEO section: `search` / `magnifying-glass` icon
- Bullet list items: small check icons (can reuse existing checkmark)

### 3D mockup graphics
All built with CSS; no images.

### Fonts
Existing: Syne (headings), Inter (body).

## 8. Pre-Implementation Checklist

- [ ] New showcase sections added to `templates/public/home.php`
- [ ] 3D mockup variants styled in `public/assets/style.css`
- [ ] Light-background variant of mockup base (`mockup-base--light`) created
- [ ] Floating cards positioned to avoid clipping on all viewports
- [ ] Mobile simplification implemented (hide secondary cards, scale mockup)
- [ ] Reduced motion fallback preserved
- [ ] Scroll reveal attributes (`data-reveal`) added to new sections
- [ ] Contrast ratios verified for light-section mockup text
- [ ] No horizontal scroll on mobile
- [ ] Tests pass after changes
