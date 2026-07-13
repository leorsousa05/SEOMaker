# Design Spec: Public Product Rendering Refactor

> 🎨 **Designer Spec Suite**
> This document defines the visual system, interaction rules, motion, and shared product-card behavior for the public product rendering refactor in SEOMaker.

## 1. Brand Narrative & Case-Study Frame

- **Problem:** Product content exists in two public contexts, but the current implementation makes the homepage catalog and product-detail related section feel like separate UI fragments. That increases maintenance cost and makes the storefront feel less coherent.
- **Audience:** Visitors landing on the public site to evaluate an offering quickly, compare products, and decide whether to click through or contact the business. They are scanning, not browsing casually.
- **Insight:** Product cards are not just summaries; they are conversion objects. When the same visual language, pricing behavior, and CTA treatment repeat across the homepage and detail page, the catalog feels trustworthy and intentional.
- **Solution:** Build a shared public product-card system with one visual grammar, one interaction model, and one price/CTA logic so the storefront reads like a deliberate merchandising surface instead of duplicated templates.

## 2. Aesthetic Direction Statement

The visual direction is **Bento Grid / Modular** with **Linear-like Minimalist** restraint. The product area should feel compact, high-signal, and curated, with strong card boundaries, disciplined spacing, and a premium but practical hierarchy. The goal is not decoration; it is to make product scanning feel efficient while still looking polished enough to support trust and conversion.

The catalog should avoid noisy merchandising tropes. Instead, it should use precise alignment, restrained accents, and consistent card states so the public homepage and product detail page feel like one connected storefront system.

### Implementation Boundary Snapshot
- `SiteController` owns public product data assembly and hands normalized products to views.
- `Product` exposes display helpers for pricing, CTA labels, and public URLs.
- `templates/public/_product_card.php` is the single reusable visual unit for product cards.
- `home.php` and `product.php` compose sections and page hierarchy, but do not own card-level presentation rules.
- No admin templates, admin controllers, or schema changes are part of this design.

## 3. Color System

All color tokens are custom-derived for this product-rendering layer. The palette leans neutral with a controlled violet accent, so product cards can stand out without hijacking the rest of the site.

| Token | Light mode | Dark mode | Usage |
|-------|------------|-----------|-------|
| `--bg-primary` | `0 0% 98%` | `222 18% 7%` | Page background behind product surfaces |
| `--bg-surface` | `0 0% 100%` | `222 18% 10%` | Cards, product tiles, hover surfaces |
| `--bg-elevated` | `0 0% 100%` | `222 18% 13%` | Popovers, expanded states, overlays |
| `--text-primary` | `222 18% 10%` | `0 0% 98%` | Titles, prices, key product names |
| `--text-secondary` | `222 10% 36%` | `220 9% 68%` | Descriptions, supporting metadata |
| `--text-muted` | `222 9% 53%` | `220 8% 52%` | Labels, placeholders, microcopy |
| `--accent` | `257 72% 54%` | `257 78% 62%` | Primary interactive color, promo emphasis |
| `--success` | `152 57% 38%` | `152 48% 42%` | Positive inventory or success states |
| `--warning` | `36 93% 52%` | `36 93% 58%` | Attention states, limited stock, caution |
| `--error` | `0 78% 58%` | `0 78% 62%` | Validation and failure states |
| `--info` | `199 88% 46%` | `199 90% 58%` | Informational hints and product notes |
| `--border` | `222 12% 88%` | `222 12% 20%` | Card borders, dividers, grid lines |
| `--focus` | `257 72% 54%` | `257 78% 62%` | Keyboard focus rings |
| `--overlay` | `222 18% 6% / 0.72` | `222 18% 4% / 0.78` | Modal/backdrop overlay if needed |

### Color Usage Rules
- Use `--accent` sparingly on CTA buttons, promo badges, discount text, and active focus states.
- Keep card backgrounds neutral so product images and pricing become the visual anchors.
- Reserve warning/error colors for genuine state changes only; do not use them decoratively.
- Maintain contrast-friendly borders to keep the product grid legible in both themes.

## 4. Typography System

The public site already leans toward a refined sans pairing. This refactor keeps that logic but makes the product cards more compact and readable under scan conditions.

| Level | Font | Size | Line-height | Letter-spacing | Weight | Usage |
|-------|------|------|-------------|----------------|--------|-------|
| H1 | `Outfit` | `3.5rem / 56px` | `1.05` | `-0.04em` | `800` | Hero and top product-page title |
| H2 | `Outfit` | `2.25rem / 36px` | `1.15` | `-0.03em` | `700` | Section titles like "Nossos Produtos" |
| H3 | `Outfit` | `1.25rem / 20px` | `1.2` | `-0.02em` | `650` | Product card names and supporting headings |
| Body | `Inter` | `1rem / 16px` | `1.55` | `0` | `400` | Descriptions and supporting copy |
| Body-sm | `Inter` | `0.875rem / 14px` | `1.45` | `0` | `400` | Metadata, SKU, stock, helper text |
| Label | `Inter` | `0.75rem / 12px` | `1` | `0.08em` | `700` | Badges, category pills, promo labels |
| Button | `Inter` | `0.9375rem / 15px` | `1` | `-0.01em` | `600` | Primary and secondary CTAs |

### Typography Rules
- Product names should clamp cleanly to two lines in card layouts.
- Prices should remain visually dominant, with line-through pricing smaller and muted.
- Category pills and stock labels should use uppercase tracking only when they support quick scanning.

## 5. Design Tokens

### Spacing Scale (4px base)

| Token | Value |
|-------|-------|
| `--space-xs` | 4px / 0.25rem |
| `--space-sm` | 8px / 0.5rem |
| `--space-md` | 16px / 1rem |
| `--space-lg` | 24px / 1.5rem |
| `--space-xl` | 32px / 2rem |
| `--space-2xl` | 48px / 3rem |
| `--space-3xl` | 64px / 4rem |
| `--space-4xl` | 96px / 6rem |

### Border Radius Scale

| Token | Value | Usage |
|-------|-------|-------|
| `--radius-none` | `0` | Strict tables and image edges |
| `--radius-sm` | `6px / 0.375rem` | Small tags, chips, inline badges |
| `--radius-md` | `10px / 0.625rem` | Buttons, small cards, thumbnail frames |
| `--radius-lg` | `16px / 1rem` | Product cards, panels |
| `--radius-xl` | `20px / 1.25rem` | Featured cards, large surfaces |
| `--radius-2xl` | `28px / 1.75rem` | Large media containers |
| `--radius-full` | `9999px` | Pills, promo labels, avatars |

### Elevation / Shadow Scale

| Token | Value | Usage |
|-------|-------|-------|
| `--shadow-0` | `none` | Flat/default resting surfaces |
| `--shadow-1` | `0 1px 2px rgba(0,0,0,0.05)` | Light resting cards |
| `--shadow-2` | `0 8px 24px rgba(0,0,0,0.08)` | Hovering product cards |
| `--shadow-3` | `0 16px 40px rgba(0,0,0,0.10)` | Expanded surfaces, overlays |
| `--shadow-4` | `0 24px 64px rgba(0,0,0,0.14)` | Modal-like emphasis if needed |

## 6. Component Specs

### 6.1 Shared Product Card

The shared card is the core design object in this refactor. It must work in two places:
- Homepage featured products
- Related products in the detail page

#### Anatomy
- Image frame
- Category pill
- Promo pill or discount marker
- Product name
- Short description when available
- Price row
- CTA button

#### Visual Treatment
- Resting cards use `--bg-surface`, a 1px border, and a soft shadow.
- Images fill a square frame with a muted fallback icon when missing.
- Promo indicators use the accent color but stay small and unobtrusive.
- CTA buttons are full-width, with a strong contrast background and a subtle accent hover.

#### States
- **Default:** clean card, subtle border, no aggressive glow.
- **Hover:** translate up slightly, deepen shadow, and scale the image by a small amount only.
- **Active:** button compresses with a brief scale reduction.
- **Disabled:** reduced opacity and no hover translation.
- **Focus:** visible `--focus` ring around the CTA and any interactive card-level element.

#### Size Variants
- **Standard:** homepage catalog cards, with full description and prominent CTA.
- **Compact:** related-product cards, with reduced description density and tighter spacing.

### 6.2 Price Display

#### Anatomy
- Optional struck-through original price.
- Current price emphasized as the dominant numeric value.
- Optional promo badge or percentage chip.

#### Rules
- If no promo price exists, show only the current price.
- If a promo price exists, preserve the old/new price relationship clearly.
- Do not let discount badges overpower the product name.

### 6.3 Category / Promo Pills

#### Treatment
- Pills should be uppercase, compact, and border-backed.
- Category pills sit in the top-right of cards or near the product title on detail pages.
- Promo pills use the accent color and should feel informational, not loud.

### 6.4 Product Detail Media Gallery

#### Treatment
- Main image sits in a 1:1 frame with soft rounding and a controlled border.
- Thumbnails are compact, evenly spaced, and visually connected to the main image.
- Selected thumbnail uses accent ring treatment, not heavy fill.

#### States
- Default thumbnail: muted border.
- Selected thumbnail: accent border + subtle ring.
- Missing image: neutral fallback icon on surface background.

### 6.5 Related Products Strip

#### Treatment
- Related products should reuse the same shared card, but scale slightly tighter than homepage cards.
- The section should read as a curated continuation, not a separate marketplace module.
- Keep spacing dense enough for scanning but not so dense that the cards feel crowded.

## 7. Layout Structure

This refactor does not change the full landing-page sequence; it sharpens the storefront sections that already exist.

### Homepage Product Section

```text
┌────────────────────────────────────────────────────────────────────┐
│ Hero                                                               │
└────────────────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────────────────┐
│ Product Strip                                                      │
│  [Card] [Card] [Card] [Card]                                       │
│  [Card] [Card] [Card] [Card]                                       │
│  Responsive collapse: 4 → 3 → 2 → 1 columns                        │
└────────────────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────────────────┐
│ Social proof / features / pricing / contact                        │
└────────────────────────────────────────────────────────────────────┘
```

### Product Detail Page

```text
┌────────────────────────────────────────────────────────────────────┐
│ Breadcrumb                                                         │
└────────────────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────────────────┐
│ Gallery | Product title                                            │
│        | Price / stock / CTA                                        │
│        | Tags / description                                         │
└────────────────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────────────────┐
│ Description                                                        │
└────────────────────────────────────────────────────────────────────┘
┌────────────────────────────────────────────────────────────────────┐
│ Related products                                                   │
│  [Card] [Card] [Card] [Card]                                       │
└────────────────────────────────────────────────────────────────────┘
```

### Responsive Behavior
- Desktop: 4-column product grid when space allows.
- Tablet: 2-3 columns depending on viewport width.
- Mobile: single-column stack with generous vertical rhythm and full-width CTA buttons.

## 8. Real-State Specs

### Loading
- Homepage cards: skeleton tiles with image block, title line, price line, and CTA bar.
- Detail page: gallery skeleton on the left, text-line skeleton on the right.
- Keep loading states light and non-blocking.

### Empty
- If there are no featured products, omit the section entirely rather than showing a dead module.
- If related products are unavailable, hide the section to avoid empty chrome.

### Error
- If a product image fails to load, show the existing fallback icon treatment on the card or detail gallery.
- Do not collapse the card layout when an image is missing.

### Skeleton
- Skeleton blocks should mirror the final card geometry exactly.
- Use neutral surface tones and shimmer only if the implementation already supports it cleanly.

### Success
- Successful CTA behavior should be reflected by stable, confident button states rather than celebratory graphics.
- The visual language should communicate reliability, not gamification.

### Offline
- Offline or failed image fetches should degrade to the same fallback icon used for missing media.
- Product cards must remain readable and navigable even when imagery is unavailable.

## 9. Presentation Mockups

### Browser-Frame Mockup
The desktop product grid should feel like a merchandise table inside a polished storefront browser window. Cards sit in a tidy rhythm with equal-height surfaces, strong image framing, and enough breathing room for prices to scan instantly.

### Device Mockup
On mobile, the product section becomes a single vertical stream of cards. The CTA remains full-width, the price stays close to the product name, and the related-products section mirrors the same pattern to avoid cognitive mode-switching.

### Before/After
- **Before:** repeated inline pricing logic and duplicated card markup risk slight inconsistencies in spacing, CTA labels, and badge placement.
- **After:** one product-card visual language controls all public merchandising surfaces, so the storefront feels like one system instead of two separate templates.

## 10. Motion Choreography

All motion must be transform- and opacity-based. No width/height animation.

| Animation | Trigger | Property | Duration | Easing | Stagger | Reduced-motion fallback |
|-----------|---------|----------|----------|--------|---------|------------------------|
| Page load reveal | load | opacity, translateY | 360ms | `cubic-bezier(0.25, 1, 0.5, 1)` | `index * 50ms + 80ms` | immediate opacity show |
| Product card hover lift | hover | translateY, shadow | 180ms | `cubic-bezier(0.25, 1, 0.5, 1)` | none | no lift, border accent only |
| Image hover zoom | hover | scale | 240ms | `ease-out` | none | no zoom |
| Button press | click | scale | 120ms | `cubic-bezier(0.34, 1.56, 0.64, 1)` | none | instant state swap |
| Detail gallery swap | click | opacity | 160ms | `ease-out` | none | instant image swap |
| Skeleton shimmer | loading | opacity/gradient shift | 1200ms loop | linear | none | static placeholder |

### Cursor-Driven Effects
- Allow subtle hover feedback on cards and buttons only.
- Do not add cursor-follow glows or parallax noise; they are unnecessary for this conversion-focused surface.

## 11. Asset List

- **Icons:** Minimal outline SVGs for image fallback, promo badges, stock indicators, and CTA arrows.
- **Images:** Product photography cropped for square and near-square presentation; subject centered for safe crop behavior.
- **Textures/effects:** Very light grain only if used elsewhere in the site; no decorative clutter on product cards.

## 12. Asset Export Spec

| Asset type | Sizes/formats | Naming convention |
|------------|---------------|-------------------|
| Icons | 16/20/24/32/48px SVG | `icon-{name}.svg` |
| Fallback illustrations | SVG | `product-fallback.svg` |
| Thumbnails | WebP + JPEG fallback | `product-{slug}-{size}.webp` |
| Card previews | PNG for documentation mockups | `product-card-{variant}.png` |

## 13. User Flow & Interaction Spec

- Homepage visitors should be able to identify featured products, understand price/discount state, and click through with one clear CTA.
- Product detail visitors should be able to inspect the gallery, read the description, and convert via external link or contact CTA without losing context.
- The same card interaction language should apply in both the homepage and related-products module to reinforce familiarity.

## 14. Accessibility & Quality Gates

- Touch targets must stay at or above 44px.
- Focus states must be visible on every interactive card/button element.
- Product name truncation should not hide the only identifying text.
- Promo and price contrast must remain readable in both light and dark themes.
- Skeletons and fallback states must preserve layout stability.

## 15. Pre-Implementation Checklist

- [ ] Brand narrative connects product cards to conversion and trust.
- [ ] Aesthetic direction is consistent, modular, and public-facing.
- [ ] Color tokens are custom and suited to the storefront use case.
- [ ] Typography preserves scanning speed and brand character.
- [ ] Shared card component is specified for both homepage and related products.
- [ ] Loading, empty, error, skeleton, success, and offline states are defined.
- [ ] Responsive behavior is clear across desktop, tablet, and mobile.
- [ ] Motion uses transform/opacity only with reduced-motion fallbacks.
- [ ] No admin or backend UI assumptions are introduced.
- [ ] The design keeps the storefront cohesive rather than turning it into a generic catalog.

## Traceability

- Addressed: shared public product card system.
- Addressed: homepage catalog and related-product presentation.
- Addressed: product detail media and price hierarchy.
- Addressed: loading/empty/error/skeleton states.
- Deferred: admin/internal UI changes, because this refactor is public-facing only.
