# Proposal: Visual Breadcrumbs + Schema

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
O SEO Template PHP já injeta o schema `BreadcrumbList` via JSON-LD, mas não exibe navegação visual de breadcrumbs na página pública. Breadcrumbs visuais melhoram a usabilidade, reduzem a taxa de rejeição e reforçam o sinal de estrutura do site para motores de busca.

## Goals
1. Renderizar uma navegação de breadcrumbs visível no topo das páginas públicas.
2. Manter o schema `BreadcrumbList` JSON-LD existente, garantindo consistência.
3. Tornar o componente acessível (ARIA) e responsivo.

## Non-Goals
- Hierarquia multinível profunda (apenas Home > Página atual, pois o site é plano).
- Breadcrumbs dinâmicos baseados em categorias/tags.
- Edição manual do breadcrumb no admin.

## Constraints
- PHP puro, templates PHP.
- Design system existente (Syne/Inter, emerald).
- Interface em português.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Design quebrar em mobile | Médio | CSS responsivo com truncamento/scroll horizontal. |
| Duplicação de lógica entre visual e schema | Baixo | Usar mesmo helper `SeoManager::breadcrumbSchema()`. |
| Conflito com estilos existentes | Baixo | Usar classes prefixadas `breadcrumb__*`. |

## Success Criteria
- [ ] Breadcrumbs visíveis em todas as páginas públicas exceto homepage.
- [ ] Schema JSON-LD continua presente e consistente.
- [ ] Componente acessível (`nav aria-label="Breadcrumb"`).
- [ ] Testes de template cobrindo renderização.
