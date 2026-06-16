# RobotsBuilder Spec

## Interface
```php
namespace App\Seo;

class RobotsBuilder
{
    public static function generate(): string;
}
```

## Requisitos

1. Se `Config::get('robots_txt_custom')` não estiver vazio, retorna esse valor tal qual
2. Caso contrário, gera:
   ```
   User-agent: *
   Allow: /
   
   Sitemap: {site_url}/sitemap.xml
   ```
3. Se `site_url` não estiver configurado, omite a linha Sitemap
4. Sempre termina com newline

## Testes
- `testReturnsCustomConfig()`
- `testGeneratesDefaultRobots()`
- `testIncludesSitemapWhenSiteUrlSet()`
- `testOmitsSitemapWhenNoSiteUrl()`
