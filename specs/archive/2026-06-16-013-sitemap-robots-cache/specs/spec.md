# Spec Delta: Sitemap.xml and Robots.txt Caching

## Current State
- `SitemapGenerator::generate()` consulta o banco a cada requisição.
- `RobotsBuilder::generate()` lê configurações a cada requisição.
- Não há mecanismo de cache para esses recursos.

## Changes

### ADDED
- Classe `App\Core\FileCache` com métodos `get()`, `set()`, `delete()`, `clear()`.
- Constante `CACHE_DIR` apontando para `config/cache/`.
- Helpers `SitemapGenerator::cachedGenerate()` e `RobotsBuilder::cachedGenerate()`.
- Invalidação de cache em `PagesController::save/delete`, `SettingsController::index` (POST) e `RedirectsController`.
- Testes em `tests/php/SitemapRobotsCacheTest.php`.

### MODIFIED
- `src/Core/Seeder.php`: criar diretório `config/cache/` e garantir permissões.
- `src/Public/SiteController.php`: usar métodos cached dos geradores.
- `src/Seo/SitemapGenerator.php`: adicionar `cachedGenerate()`.
- `src/Seo/RobotsBuilder.php`: adicionar `cachedGenerate()`.
- `src/Admin/PagesController.php`: invalidar cache após mutação.
- `src/Admin/SettingsController.php`: invalidar cache após POST.
- `src/Admin/RedirectsController.php`: invalidar cache após mutação.
- `tests/run.php`: incluir novo teste.

### REMOVED
- Nada removido.

## Migration Notes
- O diretório `config/cache/` é criado automaticamente pelo Seeder.
- Se não houver permissão de escrita, o sistema fallback para geração dinâmica.

## Backward Compatibility
- Total. Comportamento visível inalterado; apenas performance melhorada.
