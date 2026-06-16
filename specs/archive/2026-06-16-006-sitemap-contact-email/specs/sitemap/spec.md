# SitemapBuilder Spec

## Interface
```php
namespace App\Seo;

class SitemapBuilder
{
    public static function generate(): string;
}
```

## Requisitos

1. Deve retornar string XML válida começando com `<?xml version="1.0" encoding="UTF-8"?>`
2. Deve usar wrapper `<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">`
3. Deve incluir a homepage (slug vazio) sempre que houver página ativa com slug vazio
4. Deve incluir todas as páginas com `is_active = 1`
5. Para cada URL deve gerar:
   - `<loc>`: `site_url + / + slug` (sem barra dupla)
   - `<lastmod>`: `updated_at` no formato `YYYY-MM-DD`
   - `<changefreq>`: `weekly`
   - `<priority>`: 1.0 para homepage, 0.8 para outras
6. Deve escapar caracteres XML (`&`, `<`, `>`)
7. Se `site_url` não estiver configurado, retorna XML vazio (apenas urlset)

## Exemplo
```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://example.com/</loc>
    <lastmod>2026-06-05</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://example.com/sobre</loc>
    <lastmod>2026-06-05</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
</urlset>
```

## Testes
- `testGeneratesValidXml()`
- `testHomepageHasPriorityOne()`
- `testInactivePagesExcluded()`
- `testEscapesSpecialCharacters()`
- `testEmptyWhenNoSiteUrl()`
