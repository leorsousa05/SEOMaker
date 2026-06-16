# Proposal: Sitemap.xml and Robots.txt Caching

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
Os endpoints `/sitemap.xml` e `/robots.txt` geram conteúdo dinamicamente a partir do banco de dados a cada requisição. Para sites com muitas páginas, isso aumenta a carga do SQLite e aumenta o tempo de resposta, impactando SEO técnico e custo de infraestrutura.

## Goals
1. Cachear o conteúdo de `sitemap.xml` e `robots.txt` em arquivos dentro de `config/cache/` ou `public/cache/`.
2. Invalidar o cache automaticamente quando páginas ou configurações relevantes forem alteradas.
3. Manter geração dinâmica como fallback caso o cache esteja ausente.

## Non-Goals
- Cache de meta tags ou páginas HTML.
- Cache distribuído (Redis/Memcached).
- Invalidação baseada em tempo (TTL) — usar event-driven.

## Constraints
- PHP puro, sem dependências.
- Permissões de escrita em `config/` e/ou `public/cache/`.
- Interface em português.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Cache desatualizado após edição | Alto | Invalidar em todos os pontos de mutação (save page, settings, redirects). |
| Permissão de escrita negada | Médio | Fallback para geração dinâmica; log silencioso. |
| Cache corrompido | Baixo | Regenerar se arquivo estiver vazio ou inválido. |

## Success Criteria
- [ ] Arquivos de cache gerados após primeira requisição.
- [ ] Cache invalidado ao salvar/editar página ou configurações SEO.
- [ ] Fallback dinâmico funciona se cache não puder ser escrito.
- [ ] Testes cobrindo geração e invalidação.
