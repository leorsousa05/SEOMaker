# Design Update — 3D Admin Panel Hero (Developer-Focused)

## Direction change

Reposicionar o hero da landing page para **desenvolvedores e profissionais técnicos**, mostrando o painel administrativo como um produto real e funcional. A apresentação visual deve transmitir: "isso é um sistema pronto, não só uma landing page".

A identidade visual continua verde/tech, mas o tom de voz muda de B2B corporativo para **técnico/pragmático**.

## Novo conceito de hero

Substituir o card abstrato de "Performance SEO" por uma **mockup 3D do painel administrador** construída apenas com CSS 3D transforms. A mockup mostra múltiplas camadas da interface admin flutuando em perspectiva.

### Composição visual

```
┌────────────────────────────────────────────────────────────┐
│                                                            │
│   [headline técnica]                                       │
│   [subheadline para devs]                                  │
│   [CTAs]                                                   │
│                                                            │
│                    ┌─────────────────────┐                 │
│                   ╱│  Admin Dashboard    │╲                │
│                  ╱ │  ┌─────┐ ┌─────┐    │ ╲               │
│                 ╱  │  │stats│ │stats│    │  ╲              │
│    ┌───────────┐   │  └──┬──┘ └──┬──┘    │   ┌──────────┐  │
│    │  Sidebar  │   │     │       │       │   │ SEO Card │  │
│    │  • Pages  │   │  ┌──┴───────┴──┐    │   │ schema   │  │
│    │  • Media  │   │  │  Tabela de   │    │   │ sitemap  │  │
│    │  • SEO    │   │  │  Páginas     │    │   └──────────┘  │
│    └───────────┘   │  └──────────────┘    │                 │
│                 ╲  │                      │  ╱                │
│                  ╲ │                      │ ╱                 │
│                   ╲└─────────────────────┘╱                  │
│                    └─────────────────────┘                   │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### Estrutura 3D

- **Container principal:** `perspective: 1200px`, com a mockup rotacionada levemente (`rotateX(12deg) rotateY(-18deg) rotateZ(0deg)`).
- **Camadas flutuantes:**
  1. **Painel base:** retângulo representando a tela do admin com sidebar + header + conteúdo.
  2. **Sidebar:** sobreposta à esquerda, com itens de menu (Páginas, Mídia, SEO, Redirects).
  3. **Tabela de páginas:** no centro, mostrando linhas com título, slug e status.
  4. **Cards flutuantes:** pequenos painéis ao redor mostrando schema.org, sitemap.xml, editor de SEO.
- **Efeitos:** sombras em camadas, bordas sutis, glassmorphism nos cards flutuantes.
- **Animação:** float suave no container 3D e nos cards flutuantes (respeita reduced-motion).

## Novo copy (foco em desenvolvedores)

### Headline
> "Um CMS em PHP + SQLite pronto para usar e customizar"

### Subheadline
> "Sem dependências pesadas, sem frameworks mágicos. Código limpo em PHP 8, SQLite, templates PHP e CSS vanilla. Ideal para quem quer entregar rápido e manter o controle."

### CTAs
- Primário: "Ver o código em ação" → link `/admin`
- Secundário: "Documentação" → link `#recursos` (ou `/page/sobre` se existir)

### Features (mais técnicas)

1. **Código PHP 8 puro**
   "Sem Laravel, sem Symfony. Autoloading PSR-4, strict_types e arquitetura modular."

2. **Banco SQLite embutido**
   "Sem configurar PostgreSQL ou MySQL. O banco fica em um único arquivo."

3. **Admin funcional de verdade**
   "CRUD de páginas, upload de mídia, redirecionamentos 301/302, mensagens de contato e configurações."

4. **SEO técnico automático**
   "Meta tags, Open Graph, Twitter Cards, Schema.org, sitemap.xml e robots.txt gerados dinamicamente."

5. **Templates PHP simples**
   "Sistema de View com layouts e partials. Fácil de tematizar e estender."

6. **Testes incluídos**
   "Testes PHP e JS cobrindo SEO, schemas, páginas, redirects e mais."

## Componentes do mockup 3D

### HTML/CSS

```html
<div class="admin-mockup-3d">
    <div class="mockup-base">
        <div class="mockup-sidebar">
            <div class="mockup-logo"></div>
            <div class="mockup-nav-item"></div>
            <div class="mockup-nav-item"></div>
            <div class="mockup-nav-item"></div>
        </div>
        <div class="mockup-main">
            <div class="mockup-header"></div>
            <div class="mockup-content">
                <div class="mockup-row"></div>
                <div class="mockup-row"></div>
                <div class="mockup-row"></div>
            </div>
        </div>
    </div>
    <div class="mockup-float-card mockup-float-card--schema">
        <span>Schema.org</span>
    </div>
    <div class="mockup-float-card mockup-float-card--sitemap">
        <span>sitemap.xml</span>
    </div>
    <div class="mockup-float-card mockup-float-card--meta">
        <span>Meta tags</span>
    </div>
</div>
```

```css
.admin-mockup-3d {
    perspective: 1200px;
    transform-style: preserve-3d;
}

.mockup-base {
    transform: rotateX(12deg) rotateY(-18deg);
    transform-style: preserve-3d;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 1rem;
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.35);
    animation: mockupFloat 8s ease-in-out infinite;
}

.mockup-float-card {
    position: absolute;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(8px);
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    color: #fff;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.2);
    animation: cardFloat 6s ease-in-out infinite;
}
```

## Files to modify

- `templates/public/home.php` — novo hero com mockup 3D e copy para devs
- `public/assets/style.css` — estilos do mockup 3D, animações, cores do subtítulo
- `src/Public/SiteController.php` — novos textos das features (6 cards técnicos)
- `public/assets/animations.js` — adicionar classe `js-animations` para fallback no-JS

## Notas de implementação

- Manter tudo em CSS 3D transforms. Não usar WebGL, Three.js ou Canvas.
- As camadas devem ser construídas com `div`s estilizadas, não imagens.
- Garantir que o mockup não quebre em mobile: em telas pequenas, reduzir escala (`transform: scale(0.7)`) ou ocultar cards flutuantes secundários.
- Respeitar `prefers-reduced-motion`: desabilitar float e rotação 3D, mostrar mockup estático frontal.

## Pre-implementation checklist

- [ ] Mockup 3D construído apenas com CSS transforms
- [ ] Hero copy atualizado para linguagem técnica
- [ ] Features atualizadas para 6 itens técnicos
- [ ] Mobile: mockup escalado ou simplificado
- [ ] Reduced-motion: mockup estático sem rotação
- [ ] No-JS fallback: elementos visíveis por padrão
