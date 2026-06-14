# Design Refinements — SEO Template Redesign

## Issues identified in implemented version

1. **Hero subtitle has insufficient contrast**
   - The subtitle "Template SEO completo com painel administrativo..." is rendering in `--color-text-secondary` (slate) instead of white, making it nearly illegible against the dark teal hero background.

2. **Scroll-reveal elements are invisible before JavaScript runs / on static screenshots**
   - Elements with `[data-reveal]` start with `opacity: 0` in CSS. If JS fails or the page is rendered statically (screenshots, crawlers), the features and contact sections appear blank.

3. **Feature cards lack visual separation**
   - White cards on a very light gradient background look washed out. They need stronger shadows or a more defined surface treatment.

## Refinements

### 1. Hero subtitle

**Change in `public/assets/style.css`:**

```css
.hero-subtitle {
    margin-bottom: 2rem;
    color: rgba(255, 255, 255, 0.92);
    max-width: 540px;
}
```

Remove `opacity: 0.9`. Use explicit near-white color. This ensures the subtitle is readable (contrast ~10:1 against hero background).

### 2. No-JS fallback for reveal animations

**Change in `public/assets/style.css`:**

```css
[data-reveal] {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 700ms var(--ease-out-expo), transform 700ms var(--ease-out-expo);
}

html:not(.js-animations) [data-reveal] {
    opacity: 1;
    transform: none;
}

[data-reveal].is-visible {
    opacity: 1;
    transform: translateY(0);
}
```

**Change in `public/assets/animations.js`:**

Add `document.documentElement.classList.add('js-animations');` at the start of the IIFE before any other logic. This ensures animations only hide elements when JS is actually running.

### 3. Feature cards visual separation

**Change in `public/assets/style.css`:**

```css
.feature-card {
    background: var(--color-surface-raised);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
}

.feature-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: var(--color-primary-light);
}
```

Add `box-shadow: var(--shadow-sm)` to default state so cards lift off the light background.

### 4. Contact section visibility

**Change in `public/assets/style.css`:**

```css
.contact {
    background: var(--color-surface);
}

.contact-form {
    background: var(--color-surface-raised);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
}
```

Wrap the contact form in a surfaced card so it is visually distinct from the section background.

**Change in `templates/public/home.php`:**

Wrap the form element in `<div class="contact-form">`:

```php
<div class="contact-form" data-reveal>
    <form action="/contact" method="POST" class="form">
        ...
    </form>
</div>
```

Remove `data-reveal` from the inner form if present.

### 5. Hero visual card refinement

Keep the existing card, but increase its shadow and border prominence slightly:

```css
.hero-card {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-lg);
    padding: 2rem;
    backdrop-filter: blur(12px);
    box-shadow: 0 32px 64px rgba(0, 0, 0, 0.25);
    animation: heroFloat 6s ease-in-out infinite;
}
```

## Pre-implementation checklist

- [ ] Hero subtitle is white/light with sufficient contrast
- [ ] `[data-reveal]` elements are visible when JS is disabled
- [ ] Feature cards have visible shadows/borders
- [ ] Contact form is wrapped in a surfaced card
- [ ] Reduced-motion fallback still works
