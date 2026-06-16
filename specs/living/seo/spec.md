# Spec: SEO Engine

## ADDED

### Seo\SeoManager

#### Meta Tags
Gera tags HTML completas:
- `<title>` — usa page.meta_title ou page.title + site_title suffix
- `<meta name="description">`
- `<link rel="canonical">`
- Open Graph: og:title, og:description, og:url, og:type
- Twitter Card: twitter:card, twitter:title, twitter:description

#### Schema.org JSON-LD
Suporta tipos:
- WebSite
- WebPage
- Organization
- ContactPage
- FAQPage (futuro)

Método: `schemaJsonLd(Page $page): string` retorna `<script type="application/ld+json">`

#### Sitemap XML
Gera sitemap.xml dinâmico com todas as páginas ativas.
Inclui lastmod, changefreq, priority.
O conteúdo é cacheado em arquivo (`config/cache/sitemap.xml`) via `SitemapGenerator::cachedGenerate()`.
Invalidação ocorre ao salvar/deletar páginas, alterar configurações ou modificar redirects.

#### Robots.txt
Gera robots.txt apontando para sitemap.
O conteúdo é cacheado em arquivo (`config/cache/robots.txt`) via `RobotsBuilder::cachedGenerate()`.
Invalidação ocorre nas mesmas mutações do sitemap, já que depende da URL do site.
