# Proposal: Canonical WWW/Non-WWW and Trailing Slash Redirects

## Status
- **State:** draft
- **Created:** 2026-06-16
- **Author:** @architect

## Problem Statement
O SEO Template PHP não força uma versão canônica de URL. A mesma página pode ser acessada com ou sem `www` e com ou sem barra final (`/`), criando conteúdo duplicado e dividindo sinais de ranking entre múltiplas URLs.

## Goals
1. Adicionar configuração em `settings` para escolher a versão canônica: `www` ou `non-www`.
2. Adicionar configuração para forçar trailing slash ou não.
3. Redirecionar 301 todas as requisições não canônicas para a versão correta no ponto de entrada `public/index.php`.

## Non-Goals
- Redirecionamentos de HTTP para HTTPS (geralmente feito no servidor web).
- Redirecionamentos de slug antigo (já coberto pela tabela `redirects`).
- Suporte a múltiplos domínios/idiomas.

## Constraints
- PHP puro, sem dependências.
- Deve funcionar com servidor embutido e Apache/Nginx.
- Interface em português.

## Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
- Loop de redirecionamento | Alto | Verificar `HTTP_HOST` antes de redirecionar; nunca redirecionar se já estiver canônico. |
| Quebra de ambientes locais | Médio | Configuração desabilitada por padrão. |
- Conflito com redirecionamentos existentes | Médio | Aplicar canonical redirects antes de verificar `redirects` table, ou depois conforme prioridade. |

## Success Criteria
- [ ] Configurações "Versão canônica" e "Barra final" disponíveis no admin.
- [ ] Redirecionamento 301 funciona para www/não-www e trailing slash.
- [ ] Não há loop de redirecionamento.
- [ ] Testes cobrindo combinações de configuração.
