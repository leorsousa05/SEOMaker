# Proposal: Page-Level Robots Control

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
O SEO Template PHP gera meta tags Open Graph, Twitter Cards, canonical e JSON-LD, mas não oferece controle granular sobre indexação de páginas individuais. Hoje, todas as páginas ativas são indexadas pelos motores de busca. Isso é problemático para páginas finas (thin pages), como políticas de privacidade, termos de uso, páginas de agradecimento pós-contato ou landing pages temporárias de campanha, que podem diluir a autoridade do site nos resultados de busca.

## Goals
1. Permitir que o administrador defina, por página, diretivas `robots` (`index`/`noindex`, `follow`/`nofollow`).
2. Renderizar a meta tag `<meta name="robots" content="...">` dinamicamente no front-end público.
3. Manter o comportamento padrão `index, follow` para páginas existentes, garantindo retrocompatibilidade.

## Non-Goals
- Alterar o comportamento global do `robots.txt`.
- Adicionar outras diretivas como `noarchive`, `nosnippet`, `max-snippet` nesta mudança.
- Implementar controle de indexação em massa ou por categorias.

## Constraints
- Manter PHP 8.1+ puro, SQLite e stack vanilla.
- Interface e mensagens em português (pt-BR).
- Preservar arquitetura MVC e padrões existentes do projeto.
- Não introduzir dependências externas.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Página importante marcada como `noindex` por engano | Alto | Default `index, follow`; rótulos claros no admin; preview SEO mantido. |
| Migração de dados existentes falhar | Baixo | `ALTER TABLE` em `Seeder::run()` com try/catch; default preenchido. |
| Duplicação de lógica de meta tags | Médio | Centralizar em `SeoManager::metaTags()`. |

## Success Criteria
- [ ] Campo "Indexação" visível na aba SEO da edição de página.
- [ ] Meta tag `robots` renderizada corretamente para todas as páginas públicas.
- [ ] Páginas existentes continuam com `index, follow`.
- [ ] Testes unitários cobrando `SeoManager::metaTags()` com `noindex`.
