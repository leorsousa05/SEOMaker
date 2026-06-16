# Proposal: Lazy Loading and Required Alt Text for Images

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
Imagens sem atributo `alt` e sem lazy loading prejudicam a acessibilidade e os Core Web Vitals. O SEO Template PHP já renderiza algumas imagens com `loading="lazy"`, mas não de forma consistente em todos os blocos (galeria, imagem única, mapas/embeds). Além disso, o campo `alt` no bloco de imagem é opcional, permitindo imagens sem descrição textual.

## Goals
1. Garantir que todas as imagens renderizadas pelo BlockEditor tenham `loading="lazy"`, exceto a primeira imagem acima da dobra (hero/first contentful paint).
2. Tornar o campo `alt` obrigatório no bloco de imagem e galeria.
3. Adicionar validação no client-side e server-side para impedir salvamento sem `alt`.

## Non-Goals
- Otimização automática de tamanho/formato de imagem (fora de escopo).
- Lazy loading de iframes/vídeos.
- Geração automática de alt text por IA.

## Constraints
- Manter compatibilidade com blocos existentes.
- PHP puro, vanilla JS.
- Interface em português.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Imagens existentes sem alt quebrarem save | Médio | Validação com mensagem clara; exigir preenchimento ao editar. |
| Primeira imagem do site com lazy loading (LCP ruim) | Alto | Primeira imagem da página recebe `loading="eager"` ou nenhum atributo. |
| Galerias quebrarem visualmente | Baixo | Apenas adicionar atributo; não alterar CSS. |

## Success Criteria
- [ ] Todas as imagens dos blocos têm `loading="lazy"` ou `loading="eager"`.
- [ ] Blocos de imagem/galeria não salvam sem `alt`.
- [ ] Mensagem de erro em português quando alt está vazio.
- [ ] Testes cobrindo renderização e validação.
