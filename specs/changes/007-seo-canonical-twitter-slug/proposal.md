# Proposal — Parte 5

## WHY
O template já possui meta tags básicas, Open Graph, sitemap e schema.org. Esta parte adiciona os elementos SEO faltantes de alto impacto: canonical URL completo, Twitter Cards com imagem, e geração/validação automática de slugs para melhorar UX do admin e prevenir URLs duplicadas.

## Scope
- Reforçar `<link rel="canonical">` no `SeoManager` (já existe, garantir comportamento correto)
- Adicionar `twitter:image` ao Twitter Cards no `SeoManager`
- Gerar slug automaticamente a partir do título quando não preenchido
- Validar slug duplicado no create/update com feedback de erro
- Normalizar slug (lowercase, hífens, remove acentos, caracteres especiais)
- Adicionar testes para slug e meta tags

## Constraints
- Manter compatibilidade com slug vazio = homepage
- Não quebrar páginas existentes
- Validação server-side; JS opcional para UX
