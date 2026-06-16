# Proposal: Auto FAQPage Schema from FAQ Block

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
O editor de blocos já possui um bloco FAQ que renderiza um acordeão HTML, mas não gera o JSON-LD `FAQPage` correspondente. Sem o schema estruturado, o Google não pode exibir as perguntas como rich snippets na SERP, desperdiçando uma oportunidade de SEO de destaque.

## Goals
1. Detectar blocos do tipo `faq` em `pages.content_blocks`.
2. Gerar automaticamente schema `FAQPage` contendo `mainEntity` com `Question`/`Answer`.
3. Injetar o JSON-LD na página pública quando houver pelo menos um bloco FAQ.

## Non-Goals
- Alterar a renderização visual do FAQ.
- Criar um schema manual para FAQ; a fonte de verdade continua sendo o bloco.
- Adicionar markup Speakable ou outras extensões de schema.

## Constraints
- PHP puro, sem dependências.
- Manter compatibilidade com formato JSON existente do bloco FAQ.
- Interface em português.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Schema duplicado se usuário também adicionar FAQPage manual | Médio | Documentar que auto-schema substitui manual quando bloco FAQ existe; preferir bloco. |
| Pergunta/resposta vazia | Baixo | Filtrar itens com question ou answer vazios. |
| HTML na resposta invalida schema | Baixo | Strip tags ou sanitizar para texto plano no schema. |

## Success Criteria
- [ ] Página com bloco FAQ renderiza JSON-LD `FAQPage` válido.
- [ ] Página sem bloco FAQ não renderia schema FAQPage.
- [ ] Testes cobrindo extração de itens do bloco.
