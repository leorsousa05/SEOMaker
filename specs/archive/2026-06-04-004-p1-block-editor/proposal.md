# Proposal: Template SEO Completo — Admin Profissional

## WHY
O template atual funciona tecnicamente mas não é usável por clientes leigos. Um cliente real precisa:
- Criar páginas sem saber HTML
- Gerenciar imagens de forma visual
- Informar endereço do negócio para aparecer no Google Maps
- Ter um painel que pareça profissional e confiável

## Scope
### 1. Editor Visual de Conteúdo (Block Editor)
Blocos arrastáveis/reordenáveis:
- **Texto** — rich text com toolbar (bold, italic, links, listas, headings)
- **Imagem** — seleciona da galeria ou upload direto
- **Galeria** — grid de múltiplas imagens
- **Vídeo** — embed YouTube/Vimeo por URL
- **Mapa** — Google Maps embed por endereço
- **CTA** — botão com link e texto customizável
- **FAQ** — acordeão de perguntas/respostas
- **Espaçador** — controle de margem entre blocos

### 2. Sistema de Endereço / Geolocalização
- Campos: rua, número, complemento, bairro, cidade, estado, CEP, país
- Schema LocalBusiness com endereço estruturado
- Geração automática de link Google Maps
- Schema geo coordinates (lat/lng) — opcional

### 3. Galeria de Mídia
- Upload múltiplo de imagens (drag & drop)
- Armazenamento em `public/uploads/YYYY/MM/`
- Thumbnails automáticos
- Grid visual no admin
- Seleção no editor de conteúdo
- Delete com confirmação

### 4. Painel Admin Profissional
- Design system moderno (indigo/slate)
- Dark/Light mode
- Sidebar com ícones SVG
- Dashboard com métricas
- Tabelas com busca, filtros, paginação
- Toasts, modais, tooltips
- Mobile responsivo

## Constraints
- PHP 8.1+ puro, SQLite, HTML/CSS/JS vanilla
- Sem frameworks frontend
- Uploads limitados a 5MB por imagem
- Formatos: jpg, png, gif, webp
