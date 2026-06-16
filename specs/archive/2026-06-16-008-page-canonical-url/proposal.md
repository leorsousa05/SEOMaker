# Proposal: Manual Canonical URL per Page

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
O SEO Template PHP gera automaticamente a URL canônica a partir do slug da página (`site_url/` para homepage e `site_url/page/{slug}` para as demais). No entanto, em campanhas de marketing, testes A/B ou quando existem variações de URL com parâmetros UTM, é necessário poder definir uma URL canônica manual. Sem isso, motores de busca podem indexar múltiplas versões da mesma página, diluindo sinais de ranking.

## Goals
1. Adicionar um campo opcional `canonical_url` em cada página.
2. Quando preenchido, usá-lo como `<link rel="canonical">`.
3. Quando vazio, manter o comportamento atual de geração automática.

## Non-Goals
- Validação de URL canônica em tempo real contra a página atual.
- Suporte a múltiplos canônicos (hreflang) — fora do escopo desta mudança.
- Alterar o gerador de sitemap para respeitar canônicos manuais.

## Constraints
- PHP 8.1+ puro, SQLite, vanilla stack.
- Interface em português.
- Retrocompatibilidade obrigatória.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Canonical apontando para URL externa por engano | Médio | Aceitar URLs absolutas e relativas; documentar uso interno. |
| Migração de dados | Baixo | Coluna TEXT nullable; default nulo. |
| Duplicação com slug | Baixo | Fallback claro: manual > automático. |

## Success Criteria
- [ ] Campo "URL canônica" na aba SEO.
- [ ] `<link rel="canonical">` renderiza valor manual quando preenchido.
- [ ] Páginas existentes mantêm canonical automático.
- [ ] Testes cobrindo fallback e URL manual.
