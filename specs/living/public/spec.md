# Spec: Public Site / Landing Page

## ADDED

### Landing Page
- Hero section com título/desc da config
- Features section
- Contact form (nome, email, mensagem)
- Footer com links
- Usa schema WebPage + Organization

### Generic Page
- Renderiza qualquer página do DB pelo slug
- Usa page.content_html
- Injeta meta tags e schema via SeoManager

### Contact Endpoint
- POST /contact
- Valida nome, email, mensagem
- Envia email para contact_email configurado
- Retorna redirect com flash message

### Product Rendering
- Página inicial pode listar produtos em destaque no bloco público.
- Página de produto renderiza detalhes, galeria e produtos relacionados.
- Cartões de produto usam o mesmo contrato visual e de CTA na home e nas recomendações.
