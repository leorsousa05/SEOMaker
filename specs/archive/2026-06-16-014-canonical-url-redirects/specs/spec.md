# Spec Delta: Canonical WWW/Non-WWW and Trailing Slash Redirects

## Current State
- `public/index.php` verifica redirecionamentos da tabela `redirects` antes do roteamento.
- Não há normalização de host (www/não-www) nem de trailing slash.

## Changes

### ADDED
- Configurações `canonical_host` (`www` | `non-www` | `auto`) e `force_trailing_slash` (`1` | `0` | `auto`) na tabela `settings`.
- Classe `App\Core\CanonicalRedirect` com método `shouldRedirect(string $host, string $path): ?string`.
- Lógica de redirecionamento 301 em `public/index.php`.
- Testes em `tests/php/CanonicalRedirectTest.php`.

### MODIFIED
- `src/Core/Seeder.php`: seedar configurações padrão `auto` (desabilitado).
- `src/Admin/SettingsController.php`: adicionar campos nas abas SEO.
- `templates/admin/settings.php`: renderizar selects de configuração.
- `public/index.php`: chamar `CanonicalRedirect::shouldRedirect()` e emitir 301 se necessário.
- `tests/run.php`: incluir novo teste.

### REMOVED
- Nada removido.

## Migration Notes
- Valores padrão `auto` significam "não forçar".
- `Seeder::run()` faz upsert das configurações.

## Backward Compatibility
- Total quando configurações estão em `auto`. Ativação é opt-in pelo admin.
