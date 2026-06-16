# Design: Canonical WWW/Non-WWW and Trailing Slash Redirects

## Overview
Adicionar um componente de normalização de URL que, com base em configurações do admin, decide se a requisição atual deve ser redirecionada 301 para a versão canônica (www/não-www e trailing slash). O redirecionamento ocorre no bootstrap, antes do dispatch do router.

## Proposed Directory & File Structure
```
/home/arch/codes/template-seo/
├── src/
│   ├── Core/
│   │   ├── Seeder.php              (Modified)
│   │   └── CanonicalRedirect.php   (New)
│   └── Admin/
│       └── SettingsController.php  (Modified)
├── templates/
│   └── admin/
│       └── settings.php            (Modified)
├── public/
│   └── index.php                   (Modified)
├── tests/
│   ├── php/
│   │   └── CanonicalRedirectTest.php (New)
│   └── run.php                     (Modified)
└── specs/changes/014-canonical-url-redirects/
    └── ...
```

## Code Architecture & Design Patterns
- **Value Object Pattern:** `CanonicalRedirect` encapsula toda a lógica de decisão.
- **Early Return Pattern:** bootstrap verifica redirecionamento antes de instanciar controllers.

## Data Model
```php
namespace App\Core;

class CanonicalRedirect
{
    public function __construct(
        private string $canonicalHost = 'auto', // 'www' | 'non-www' | 'auto'
        private string $forceTrailingSlash = 'auto', // '1' | '0' | 'auto'
    ) {}

    public function shouldRedirect(string $host, string $path): ?string;
}
```

## API Contracts
```php
// App\Core\CanonicalRedirect
public function __construct(string $canonicalHost, string $forceTrailingSlash);
public function shouldRedirect(string $host, string $path): ?string;

// Config keys
// settings.canonical_host
// settings.force_trailing_slash
```

## Flow Diagrams
### Request Flow
1. `public/index.php` bootstrap session, config, seeder.
2. Instancia `CanonicalRedirect` com valores de `Config::get()`.
3. Chama `shouldRedirect($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'])`.
4. Se retornar URL, emite `header('Location: ...', true, 301)` e `exit`.
5. Caso contrário, continua para verificação de redirects e roteamento.

## State Management
- Configurações em `settings` table.

## Error Handling
- Valores de configuração inválidos tratados como `auto` (sem redirecionamento).
- Evitar loops comparando host/path atual com target.

## Performance Considerations
- Operação trivial; executada uma vez por requisição.

## Security Considerations
- Validar que target URL pertence ao mesmo domínio/site_url.
- Não redirecionar para hosts externos.

## UI/UX Design Specification

### Aesthetic Direction
Dois selects simples na aba SEO das configurações, seguindo o padrão de formulário do admin.

### Layout
Inserir no grupo `seo` após o campo `og_image`.

```
[form-group]
  label: Versão canônica do domínio
  select[name=canonical_host]
    option: Automático (não forçar)
    option: Forçar www
    option: Forçar sem www
  help-text: "Redireciona 301 todas as requisições para a versão escolhida."

[form-group]
  label: Barra final na URL
  select[name=force_trailing_slash]
    option: Automático (não forçar)
    option: Sempre com barra (/)
    option: Sempre sem barra
  help-text: "Força redirecionamento 301 para URLs com ou sem barra final."
```

### Component Spec
- Selects com seta customizada (SVG chevron) via CSS, cor `--text-muted`.
- Fundo `--bg-input`, borda `--border-input`, radius 8px.
- Estado hover/focus: borda emerald.

### Accessibility
- Labels explícitos com `for`.
- Help-text vinculado via `aria-describedby`.

### Dark Mode
- Background do select: `--bg-input`.
- Seta: `--text-muted`.

### Motion
- Transição de borda 150ms no focus.

### Responsive
- Largura total em todos os breakpoints.
