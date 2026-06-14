# Spec: Public Site / Landing Page

## ADDED

### Landing Page
- Hero section com headline de apresentação do template SEO, subtítulo e 2 CTAs
- Visual card abstrato de performance SEO no hero
- Features section com 3 cards de destaque (ícones SVG inline)
- CTA final com gradiente de marca
- Contact form (nome, email, telefone opcional, mensagem)
- Footer expandido com marca, links e contato
- Usa schema WebPage + Organization
- Animações de entrada e scroll reveal via Intersection Observer
- Suporte a tema escuro via `prefers-color-scheme`
- Respeita `prefers-reduced-motion`

### Generic Page
- Renderiza qualquer página do DB pelo slug
- Usa page.content_html ou content_blocks via BlockEditor
- Injeta meta tags e schema via SeoManager
- Layout consistente com a landing page

### Contact Endpoint
- POST /contact
- Valida nome, email, mensagem
- Envia email para contact_email configurado
- Retorna redirect com flash message

## DESIGN

- Identidade visual: "SEO Core" (default, customizável via site_title)
- Tipografia: Syne (headings) + Inter (body)
- Paleta: emerald + sky gradient sobre base slate
- Botões: formato pill, sombras de marca
- Navbar: fixa, transparente sobre hero, glassmorphism ao rolar
