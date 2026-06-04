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

#### Robots.txt
Gera robots.txt apontando para sitemap.
