# Proposal: SEO Template PHP

## WHY
Cliente precisa de um template PHP completo para SEO, com backend administrativo para gerenciar configurações de site, páginas, schemas e sitemap. Foco inicial: landing page funcional com base sólida para expansão futura.

## Scope
- Routing PHP nativo (sem framework pesado)
- Configurações de website (título, descrição, favicon, analytics)
- Gerador de Schema.org JSON-LD
- Gerador de sitemap.xml
- Envio de email (contact form)
- Painel admin simplificado (login, configurações, páginas)
- Landing page demo
- Estrutura extensível para futuras features

## Constraints
- PHP 8.1+ puro, sem frameworks
- SQLite para simplicidade inicial
- HTML/CSS/JS vanilla no frontend público
- Painel admin com auth básica (session-based)
