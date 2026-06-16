# Proposal: Per-Page Open Graph/Twitter Image

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
Atualmente o SEO Template PHP usa uma única imagem global (`settings.og_image`) para todas as tags Open Graph e Twitter Cards do site. Para sites institucionais/SaaS com múltiplas landing pages, isso reduz o CTR em redes sociais e compartilhamentos, pois a imagem não reflete o conteúdo específico de cada página.

## Goals
1. Permitir selecionar uma imagem específica do banco de mídia para cada página.
2. Usar essa imagem nas meta tags `og:image` e `twitter:image` quando definida.
3. Manter a imagem global como fallback.

## Non-Goals
- Upload de imagem direto no formulário de página (usar galeria existente).
- Geração automática de imagens sociais.
- Suporte a múltiplas imagens por página.

## Constraints
- Reutilizar `Media` e `MediaManager` existentes.
- Manter PHP/SQLite/vanilla stack.
- Interface em português.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Imagem excluída da galeria mas ainda referenciada | Médio | On delete, set page.og_image_id = NULL via foreign key handling ou listener. |
| Imagem muito grande | Baixo | Recomendar dimensões no label do campo; não redimensionar. |
| Fallback confuso | Baixo | Prioridade clara: página > configuração global. |

## Success Criteria
- [ ] Campo "Imagem social" na aba SEO com seleção da galeria.
- [ ] `og:image` e `twitter:image` usam imagem da página quando definida.
- [ ] Fallback para `settings.og_image` quando não houver imagem na página.
- [ ] Testes cobrindo seleção e fallback.
